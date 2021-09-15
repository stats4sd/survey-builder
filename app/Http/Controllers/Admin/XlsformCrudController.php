<?php

namespace App\Http\Controllers\Admin;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
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

        // Fields here only used to choose which input values are kept in the getStrippedSaveRequest() method on store:
        CRUD::field('project_id');
        CRUD::field('title');
        CRUD::field('user_id');
        CRUD::field('themes')->type('relationship');
        CRUD::field('moduleVersions')->type('relationship');


        CRUD::setCreateView('forms.create');
    }

    public function create ()
    {
        return $this->setupView();
    }

    public function edit ($id)
    {
       return $this->setupView($id);
    }

    public function setupView ($id = null)
    {
        $projects = Auth::user()->projects;
        $themes = Theme::all();
        $modules = ModuleVersion::with('module')->where('is_current', true)->get();
        $xlsform = null;

        if($id) {
            $xlsform = Xlsform::find($id)->load('themes', 'moduleVersions.module');
            $xlsform->modules = $xlsform->moduleVersions;
            // $xlsform->moduleVersions = $xlsform->modules->map(fn($version) => $version->id);
            // $xlsform->themes = $xlsform->themes->map(fn($theme) => $theme->id);
        }

        return view('forms.create', compact('projects', 'modules', 'themes', 'xlsform'));
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function deploy(Xlsform $xlsform)
    {
        // coding it here for now instead of in a job so we can get the response immediately instead of going via server events...

        if ($xlsform->status === 'live') {
            return;
        }

        AuthenticateWithOdkCentral::dispatchSync();

        $file = file_get_contents(Storage::path($xlsform->xlsfile));

        try {

            $response = Http::withToken(Session::get('odk.token'))
            ->withHeaders([
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'X-XlsForm-FormId-Fallback' => Str::slug($xlsform->title),
            ])
            ->withBody($file, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->post(config('services.odk_central.url').'/v1/projects/'.$xlsform->project->odk_central_id.'/forms?ignoreWarnings=true')
            ->throw()
            ->json();
        } catch(\Illuminate\Http\Client\RequestException $e) {
            dd($e);
            if($message = $e->getMessage()) {
                return response($message, 500);
            }
        }

        return response('', 200);
    }

    public function build (Xlsform $xlsform)
    {
        $path = $xlsform->id . '/' . Carbon::now()->toISOString() . '.xlsx';

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
