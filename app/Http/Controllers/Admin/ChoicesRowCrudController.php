<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChoicesRowRequest;
use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\ChoicesLabel;
use App\Models\Xlsforms\ChoicesRow;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

/**
 * Class ChoicesRowCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChoicesRowCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Xlsforms\ChoicesRow::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/choices-row');
        CRUD::setEntityNameStrings('choices row', 'choices rows');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
protected function setupListOperation()
    {
        CRUD::setDefaultPageLength(25);

        $content = '';
       if(isset($_GET['list_name'])) {
           $listName = $_GET['list_name'];
           CRUD::setHeading("<h4>Showing Choice list: ${listName}</h4>");
       }

        Widget::add()
            ->to('before_content')
            ->type('card')
            ->content([
                'body' => "<a href='" . backpack_url('choice-list') . "'><- return to choice lists</a>",
                ],
            );

        CRUD::column('ChoiceList')->label('module')->type('relationship')->attribute('module_list_label')->limit(500);
        CRUD::column('list_name');
        CRUD::column('name');
        CRUD::column('choicesLabels')
            ->type('select_multiple')
            ->label('labels')
            ->attribute('display_label')
        ->separator("<br/>");

        CRUD::filter('list_name')
            ->type('dropdown')
            ->placeholder('View single choice list')
            ->values(ChoiceList::all()->pluck('module_list_label', 'id')->toArray())
            ->whenActive(function($entry) {
                CRUD::addClause('where', 'list_name', '=', $entry);
            });
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ChoicesRowRequest::class);



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
