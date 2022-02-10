<?php

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use App\Imports\CoreFileImport;
use App\Http\Requests\ModuleRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CoreImportRequest;
use App\Http\Controllers\Operations\ImportOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

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
        CRUD::column('requires')->type('array');
        CRUD::column('requires_before')->type('array');
        CRUD::column('current_version_name')->label('Current Version')->wrapper([
            'href' => function ($crud, $column, $entry, $related_key) {
                if ($entry->current_version) {
                    return Storage::url($entry->current_version->file);
                }
                return '#';
            }
        ]);
        CRUD::column('minutes');
        CRUD::column('core')->type('boolean');
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
