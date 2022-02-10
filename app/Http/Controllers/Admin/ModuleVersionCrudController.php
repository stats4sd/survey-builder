<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ModuleVersionUpdateRequest;
use App\Models\Module;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\ModuleVersionRequest;
use App\Models\ModuleVersion;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
        Route::get($segment.'/createformodule/{module}', [
            'as'        => $routeName.'.createformodule',
            'uses'      => $controller.'@createForModule',
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

        \Alert::add('success', 'Module Version '.$moduleversion->version_name.' has been successfully published at '.$published)->flash();

        return back();
    }
}
