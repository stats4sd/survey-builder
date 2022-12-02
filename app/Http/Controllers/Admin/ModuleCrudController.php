<?php

namespace App\Http\Controllers\Admin;

use App\Exports\XlsFormExport;
use App\Jobs\BuildXlsForm;
use App\Models\Module;
use App\Imports\CoreFileImport;
use App\Http\Requests\ModuleRequest;
use App\Models\ModuleVersion;
use App\Models\Xlsform;
use App\Services\PyXformService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CoreImportRequest;
use App\Http\Controllers\Operations\ImportOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\equalToCanonicalizing;

/**
 * Class ModuleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ModuleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use ReorderOperation;


    /**
     *
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Module::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/module');
        CRUD::setEntityNameStrings('module', 'modules');

    }

    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements
        $this->crud->set('reorder.label', 'title');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        $this->crud->set('reorder.max_level', 0);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setDefaultPageLength(50);
        $this->crud->query = $this->crud->query->orderBy('lft', 'asc');


        CRUD::column('theme')->type('relationship');
        CRUD::column('title');
        CRUD::column('slug')->label('slug');
        CRUD::column('requires_before')->type('array');
        CRUD::column('test_success')->type('boolean')->wrapper([
            'element' => 'span',
            'class' => function ($crud, $column, $entry, $related_key) {
                if ($entry->test_success) {
                    return 'badge badge-success';
                }
                if ($entry->test_failed) {
                    return 'badge badge-danger';
                }

                return 'badge badge-default';
            },
        ]);
        CRUD::column('current_version_name')->type('array')->label('Current Version(s)')->wrapper([
            'href' => function ($crud, $column, $entry, $related_key) {
                if ($entry->current_version) {
                    return Storage::url($entry->current_version->file);
                }
                return '#';
            }
        ]);
        CRUD::column('minutes');
        CRUD::column('core')->type('boolean');

        CRUD::filter('optional')
            ->label('Show only optional modules')
            ->type('simple')
            ->whenActive(function ($value) {
                CRUD::addClause('where', 'core', 0);
            });

        CRUD::filter('core')
            ->label('Show only core modules')
            ->type('simple')
            ->whenActive(function ($value) {
                CRUD::addClause('where', 'core', 1);
            });


        CRUD::addButton('top', 'testCore', 'view', 'backpack::crud.buttons.test-core');
        CRUD::addButton('line', 'testOptionl', 'view', 'backpack::crud.buttons.test-optional');

    }

    /**
     * Builds a form and runs through pyXform to check it compiles to a valid ODK form.
     * If a module is passed as a prop, that is added to the core and then compiled. Otherwise just the core is tested.
     */
    public function testModules(Module $module = null)
    {

        // build core form
        $randomSeed = Str::uuid();
        $xlsform = Xlsform::create([
            'name' => 'test-form-' . $randomSeed,
            'user_id' => Auth::id(),
            'project_name' => config('services.odk_central.test-project'),
        ]);

        //TODO: update to check full or reduced;
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

        if ($module) {
            $versions = $module->moduleVersions()->where('is_current', 1)
                ->get()
                ->sortBy(function ($version) {
                    return $version->module->lft;
                });

            $versionsToSync = $versions
                ->pluck('id')
                ->combine($versions->map(function ($moduleVersion) {
                    return ['order' => $moduleVersion->module->lft];
                }));

            $xlsform->moduleVersions()->syncWithoutDetaching($versionsToSync);
        }

        // add English to the form, otherwise no labels will be created;
        $xlsform->languages()->sync('en');

        $path = $xlsform->name . '/' . Str::slug(Carbon::now()->toISOString()) . '/' . $xlsform->name . '.xlsx';


        $file = Excel::store(new XlsFormExport($xlsform), $path);

        $xlsform->update([
            'xlsfile' => $path
        ]);

        // test built form against pyxform standard;
        $result = (new PyXformService())->testXlsform($xlsform);

        // check for exact 'true', not just a truthy statement;
        if ($result === true) {

            Module::where('core', 1)->update([
                'test_success' => 1,
                'test_failed' => 0,

            ]);

            if ($module) {
                $module->update([
                    'test_success' => 1,
                    'test_failed' => 0,

                ]);
            }

            return response()->json([
                'message' => $module ? "{$module->title} Compiled with Core modules successfully" : "All Core modules compiled successfully!",
            ], 200);
        }

        if ($module) {
            $module->update([
                'test_success' => 0,
                'test_failed' => 1,
            ]);
        } else {
            Module::where('core', 1)->update([
                'test_success' => 0,
                'test_failed' => 1,
            ]);
        }

        return response()->json([
            'errors' => $result->join(','),
            'xlsform_path' => Storage::url($xlsform->xlsfile),
        ], 500);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ModuleRequest::class);

        CRUD::field('info')->type('section-title')->title('Basic Info');
        CRUD::field('theme_id')->type('relationship');
        CRUD::field('title');
        CRUD::field('slug')->label('No-spaces unique string to identify modules in compiled XLSforms');
        CRUD::field('minutes')->label('Approx. time to complete this part of the survey (in minutes)');

        CRUD::field('ordering-ingo')->type('section-title')->title('Module Ordering Rules');

        CRUD::field('locked_to_start')->type('boolean')->label('Should this module always appear at the start of the form?')
            ->hint('Tick yes to prevent users from re-ordering this module during form creation.');
        CRUD::field('locked_to_end')->type('boolean')->label('Should this module always appear at the start of the form?')
            ->hint('Tick yes to prevent users from re-ordering this module during form creation.');
        CRUD::field('requires_before')
            ->type('select2_from_array')
            ->allows_multiple(true)
            ->options(Module::where('locked_to_start', 0)->where('locked_to_end', 0)->get()->pluck('title', 'id')->toArray())
            ->label('Does this module require other modules to come before it?')
            ->hint('E.g., The Food Security module must come before the Dietry Diversity module, because the Dietry diversity questions reference answers given in the previous module.<br/>
                    Note that you do not need to select modules that are set to always appear at the start, such as the Metadata (start) and Demographics modules.
                    ');


        CRUD::field('property-info')->type('section-title')->title('Module Properties');
        CRUD::field('authors')->type('relationship');
        CRUD::field('indicators')->type('relationship');
        CRUD::field('sdgs')->type('relationship');
        CRUD::field('languages')->type('relationship');


        CRUD::field('core')->type('checkbox')->label('Is this module part of the core RHoMIS?');


        CRUD::field('live')->type('checkbox')->label('Is this module live and available for use?');
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
    }

    public function setupShowOperation()
    {
        CRUD::setShowView('modules.show');
    }
}
