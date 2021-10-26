<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Http\Requests\ProjectRequest;
use App\Jobs\CreateProjectOnOdkCentral;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProjectCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Project::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/project');
        CRUD::setEntityNameStrings('project', 'projects');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->query = $this->crud->query->withCount('xlsforms', 'users');
        CRUD::column('name');
        CRUD::column('xlsforms_count');
//        CRUD::column('odk_central_id')->label('ODK Central ID')->wrapper([
//            'href' => function ($crud, $column, $entry, $key) {
//                return 'https://central.rhomis.cgiar.org/#/projects/' .$entry->odk_central_id;
//            },
//        ]);
        CRUD::column('users_count')->label('# of Users');

        // CRUD::button('deploy_project')->type('view')->stack('line')->view('backpack::crud.buttons.deploy');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProjectRequest::class);

        CRUD::field('name')->label('Enter Project Name');
        CRUD::field('embago')->label('Enter the standard embago time period for data collected with this project'); // make it a date?
        CRUD::field('global')->label('Should project data be included in the anonymised RHoMIS global dataset?'); // make it an opt-out?
        CRUD::field('authors')->label('Enter information of collaborators for the project. This information will be used to include authorship information for any published results based on this project\'s activities');
        CRUD::field('users')->type('relationship')->label('Add users to the project')->pivot(true);
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

    public function deploy(Project $project)
    {
        CreateProjectOnOdkCentral::dispatchSync($project);

        return response('', 200);
    }
}
