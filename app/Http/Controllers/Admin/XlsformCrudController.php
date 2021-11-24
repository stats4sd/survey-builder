<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\Language;
use Carbon\Carbon;
use App\Models\Theme;
use App\Models\Module;
use App\Models\Xlsform;
use Illuminate\Support\Str;
use App\Models\ModuleVersion;
use App\Exports\XlsFormExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\XlsformRequest;
use App\Jobs\CreateProjectOnOdkCentral;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AuthenticateWithOdkCentral;
use App\Jobs\CreateDraftFormOnOdkCentral;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as traitStore;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    //use FetchOperation;

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

        // Fields here only used to choose which input values are kept in the getStrippedSaveRequest() method on store:
        CRUD::field('project_id');
        CRUD::field('title');
        CRUD::field('user_id');
        CRUD::field('themes')->type('relationship');
        CRUD::field('moduleVersions')->type('relationship');
        CRUD::field('languages')->type('relationship');
        CRUD::field('default_language');
        CRUD::field('countries')->type('relationship');

        CRUD::setCreateView('forms.create');
    }

    public function create()
    {
        return $this->setupView();
    }

    public function edit($id)
    {
        return $this->setupView($id);
    }

    public function store()
    {
        $redirect = $this->traitStore();

        // send info to API;


        return $redirect;
    }

    public function update()
    {
        $redirect = $this->traitUpdate();



        return $redirect;
    }

    public function setupView($id = null)
    {
        $projects = Auth::user()->projects;
        $themes = Theme::all();
        $languages = Language::all();
        $countries = Country::all();

        // TODO: accept the fact that there will be multiple "is_current" modules and get all modules as a collection of 'current' versions.
        // Then it will be upto the Vue component to handle picking the correct version based on user input;
        $modules = ModuleVersion::with('module')->where('is_current', true)->get();
        $xlsform = null;

        if ($id) {
            $xlsform = Xlsform::find($id)->load('themes', 'moduleVersions.module', 'countries', 'languages');
            $xlsform->modules = $xlsform->moduleVersions;
            // $xlsform->moduleVersions = $xlsform->modules->map(fn($version) => $version->id);
            // $xlsform->themes = $xlsform->themes->map(fn($theme) => $theme->id);
        }
        if(CRUD::getCurrentOperation() === 'create') {
            $view = CRUD::getCreateView();
        } else {
            $view = CRUD::getEditView();
        }
        return view($view, compact('projects', 'modules', 'themes', 'xlsform', 'languages', 'countries'));
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        CRUD::setUpdateView('forms.edit');
    }

    public function deploy(Xlsform $xlsform)
    {
        // coding it here for now instead of in a job so we can get the response immediately instead of going via server events...

        if ($xlsform->status === 'live') {
            return;
        }
//
//        AuthenticateWithOdkCentral::dispatchSync();

        $file = file_get_contents(Storage::path($xlsform->xlsfile));


        // Check authentication
        try {
            $response = Http::withHeaders([
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Authorization' => Auth::user()->jwt_token,
                    'X-XlsForm-FormId-Fallback' => Str::slug($xlsform->title),
                ])
                ->withBody($file, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->post(
                    config('auth.auth_url') .
                    "/api/forms/new?form_name=" . Str::slug($xlsform->title) .
                    "&project_name=" . urlencode($xlsform->project->name) .
                    "&publish=true" .
                    "&form_version=1.0"
                )
                ->throw();

            return response($response->body(), 200);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            if ($message = $e->getMessage()) {
                return response($message, 500);
            }
        }
    }

    public function build(Xlsform $xlsform)
    {
        $path = $xlsform->id . '/' . Str::slug(Carbon::now()->toISOString()) . '/'. $xlsform->title . '.xlsx';

        $file = Excel::store(new XlsFormExport($xlsform), $path);

        $xlsform->update([
            'xlsfile' => $path
        ]);

        return back();
    }




    // public function fetchModuleVersions()
    // {
    //     $themes = collect(request()->input('form'))->filter(function ($field) {
    //         return $field['name'] == 'themes';
    //     })->pluck('value');

    //     $themes = json_decode($themes[0]);


    //     return $this->fetch([
    //         'model' => ModuleVersion::class,
    //         'query' => function ($model) use ($themes) {
    //             return $model->whereHas('module', function (Builder $query) use ($themes) {
    //                 $query->where('modules.core', false)->whereIn('theme_id', $themes);
    //             })->where('published_at', '!=', false);
    //         }
    //     ]);
    // }
}
