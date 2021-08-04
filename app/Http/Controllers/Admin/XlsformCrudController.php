<?php

namespace App\Http\Controllers\Admin;

use App\Models\Theme;
use App\Models\Module;
use App\Models\Xlsform;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\XlsformRequest;
use App\Jobs\CreateProjectOnOdkCentral;
use Illuminate\Support\Facades\Storage;
use App\Jobs\CreateDraftFormOnOdkCentral;
use App\Models\ModuleVersion;
use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

/**
 * Class FormCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class XlsformCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Xlsform::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/xlsform');
        CRUD::setEntityNameStrings('form', 'forms');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('project')->type('relationship');
        CRUD::column('title')->label('Form Title');
        CRUD::column('status')->label('Form Status');
        CRUD::column('xlsfile')->wrapper(['href' => function ($crud, $column, $entry, $key) {
            return Storage::url($entry->xlsfile);
        }]);

        CRUD::button('deploy')->type('view')->stack('line')->view('backpack::crud.buttons.deploy')->makeFirst();
        CRUD::button('build')->type('view')->stack('line')->view('backpack::crud.buttons.build')->makeFirst();
    }
    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(XlsformRequest::class);

        CRUD::field('project_id')->type('relationship')->label('Select project for the new form');
        CRUD::field('user_id')->type('hidden')->default(Auth::id());

        CRUD::field('title')->label('Enter form title');

        CRUD::field('xlsform')->label('For demo - upload a complete XLSForm file')->hint('This is for testing / debugging purposes only')->type('upload')->upload(true);

        CRUD::field('theme_title')->type('section-title')->title('Form Content')->content('
            The RHoMIS Survey is made of different modules. Each module contains a set of related questions that together let you calculate a set of indicators.<br/><br/>
            You can create your form by choosing the modules that you need. Some modules are "core", which means they are always included. <br/><br/>
            First, select the themes that best fit your information needs. When you select a theme, the relevant modules will become available to you.
        ');
        CRUD::field('themes')->type('checklist')->entity('themes')->attribute('title')->model(Theme::class)->pivot(true);
        CRUD::field('moduleVersions')->type('relationship')->ajax(true)->minimum_input_length(0)->dependencies('themes')->attribute('dropdown_label');
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

    public function deploy(Xlsform $xlsform)
    {
        CreateDraftFormOnOdkCentral::dispatchSync($xlsform);

        return response('', 200);
    }

    public function fetchModuleVersions()
    {
        $themes = collect(request()->input('form'))->filter(function ($field) {
            return $field['name'] == 'themes';
        })->pluck('value');

        $themes = json_decode($themes[0]);


        return $this->fetch([
            'model' => ModuleVersion::class,
            'query' => function ($model) use ($themes) {
                return $model->whereHas('module', function (Builder $query) use ($themes) {
                    $query->where('modules.core', false)->whereIn('theme_id', $themes);
                })->where('published_at', '!=', false);
            }
        ]);
    }
}
