<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChoiceListRequest;
use App\Models\Xlsforms\ChoiceList;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ChoiceListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChoiceListCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Xlsforms\ChoiceList::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/choice-list');
        CRUD::setEntityNameStrings('choice list', 'choice lists');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


        CRUD::column('list_name')->label('List Name <br/>(Click to view list)')->wrapper([
            'href' => function($crud, $column, $entry) {
                return backpack_url('choices-row?list_name= ' . $entry->list_name);
            }
        ]);
        CRUD::column('is_localisable')->type('boolean');
        CRUD::column('is_units')->type('boolean')->label('Is list of units?');
        CRUD::column('is_locations')->type('boolean')->label('Is a list of locations?');

        CRUD::filter('localisable')
            ->type('simple')
            ->whenActive(function() {
                CRUD::addClause('where', 'is_localisable', '=', 1);
            });

        CRUD::filter('show_location_lists')
            ->type('simple')
            ->whenActive(fn() => CRUD::addClause('where', 'is_locations', '=', 1));

        CRUD::filter('show_unit_lists')
            ->type('simple')
            ->whenActive(fn() => CRUD::addClause('where', 'is_units', '=', 1));
    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ChoiceListRequest::class);

        CRUD::field('list_name');
        CRUD::field('is_localisable')->type('checkbox');
        CRUD::field('is_locations')->type('checkbox');
        CRUD::field('is_units')->type('checkbox');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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
}
