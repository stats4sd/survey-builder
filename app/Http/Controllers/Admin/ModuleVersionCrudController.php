<?php

namespace App\Http\Controllers\Admin;

use App\Exports\XlsFormExport;
use App\Http\Requests\ModuleVersionUpdateRequest;
use App\Models\Module;
use App\Models\Xlsform;
use App\Services\PyXformService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\ModuleVersionRequest;
use App\Models\ModuleVersion;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ModuleVersionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ModuleVersionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ModuleVersion::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/moduleversion');
        CRUD::setEntityNameStrings('moduleversion', 'module_versions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // nothin
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ModuleVersionRequest::class);

        CRUD::field('module_id')->type('relationship');
        CRUD::field('prev_version')->type('prev_module_version');
        CRUD::field('version_name')->type('text');
        CRUD::field('mini')->type('checkbox')->label('Is this a "reduced" version of the module?');
        CRUD::field('file')->type('upload')->upload(true);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        CRUD::setValidation(ModuleVersionUpdateRequest::class);
    }


    protected function setupCreateForModuleRoutes($segment, $routeName, $controller)
    {
        Route::get($segment . '/createformodule/{module}', [
            'as' => $routeName . '.createformodule',
            'uses' => $controller . '@createForModule',
            'operation' => 'createForModule',
        ]);
    }


    /**
     * CreateForModule Operation - copies create operation, but predefines the module
     */
    public function createForModule(Module $module)
    {
        $this->setupCreateOperation();

        CRUD::modifyField('module_id', ['type' => 'hidden', 'default' => $module->id]);

        return $this->create();
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateForModuleDefaults()
    {
        $this->crud->allowAccess('create');

        $this->crud->operation('createForModule', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig('backpack.crud.operations.create');
            $this->crud->setupDefaultSaveActions();
        });

        // $this->crud->operation('list', function () {
        //     $this->crud->addButton('top', 'create', 'view', 'crud::buttons.create');
        // });
    }


    public function publish(ModuleVersion $moduleversion)
    {
        $published = $moduleversion->publish();

        \Alert::add('success', 'Module Version ' . $moduleversion->version_name . ' has been successfully published at ' . $published)->flash();

        return back();
    }

    public function unpublish(ModuleVersion $moduleversion)
    {
        // check (have any forms used this yet?)
        if ($moduleversion->xlsforms && $moduleversion->xlsforms->count() > 0) {
            \Alert::add('danger', 'Warning - Module version ' . $moduleversion->version_name . ' is currently used in  ' . $moduleversion->xlsforms->count() . ' ODK forms created by users. Therefore it cannot be unpublished.')->flash();
            return back();
        }

        $unpublished = $moduleversion->unpublish();

        \Alert::add('success', 'Module Version ' . $moduleversion->version_name . ' has bee successfully unpublished. It is no longer available for users within the survey builder.')->flash();
        return back();
    }

    public function test(ModuleVersion $moduleversion)
    {

        $randomSeed = Str::uuid();
        $xlsform = Xlsform::create([
            'name' => "test-form-{$randomSeed}",
            'user_id' => Auth::id(),
            'project_name' => config('services.odk_central.test-project'),
        ]);

        $moduleVersions = ModuleVersion::where('is_current', 1)
            ->whereHas('module', function ($query) {
                $query->where('modules.core', 1)
                    // locked modules are automatically added during build; so are not synced to the form
                    ->where('modules.locked_to_start', 0)
                    ->where('modules.locked_to_end', 0);
            })
            ->get()
            ->sortBy(function ($version) {
                return $version->module->lft;
            });

        // if current version to test is a core module, remove the 'current' version of it;
        if ($moduleversion->module->core) {
            $moduleVersions = $moduleVersions->filter(function ($version) use ($moduleversion) {
                return $version->id !== $moduleversion->id;
            });
        }

        // add the module to be tested;
        // the position of the module to be tested should not matter for the pyxform test;
        $moduleVersions = $moduleVersions->push($moduleversion);

        // prepare array to sync via belongsToMany relationship in format:
        // [
        //      $moduleVersionId => ['order' => 1],
        //      $moduleVersionId => ['order' => 2],
        //         ... etc
        // ]
        $moduleVersionsToSync = $moduleVersions
            ->pluck('id')
            ->combine($moduleVersions->map(function ($moduleVersion) {
                return ['order' => $moduleVersion->module->lft];
            }));

        $xlsform->moduleVersions()
            ->sync($moduleVersionsToSync);

        // add English to the form, otherwise no labels will be created;
        $xlsform->languages()->sync('en');

        $path = $xlsform->name . '/' . Str::slug(Carbon::now()->toISOString()) . '/' . $xlsform->name . '.xlsx';


        $file = Excel::store(new XlsFormExport($xlsform), $path);

        $xlsform->updateQuietly([
            'xlsfile' => $path
        ]);

        // test built form against pyxform standard;
        $result = (new PyXformService())->testXlsform($xlsform);

        // check for exact 'true', not just a truthy statement;
        if ($result === true) {
            $moduleversion->updateQuietly([
                'test_success' => 1,
                'test_failed' => 0,
            ]);

            return response()->json([
                'message' => "{$moduleversion->module->title} - Version: {$moduleversion->version_name} Compiled with Core modules successfully",
            ], 200);
        }
        $moduleversion->updateQuietly([
            'test_success' => 0,
            'test_failed' => 1,
        ]);

        return response()->json([
            'errors' => $result->join(','),
            'xlsform_path' => Storage::url($xlsform->xlsfile),
        ], 500);

    }

}
