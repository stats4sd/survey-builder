<?php

namespace App\Http\Controllers;

use App\Http\Requests\XlsformCreateRequest;
use App\Http\Requests\XlsformUpdateRequest;
use App\Jobs\BuildXlsForm;
use App\Jobs\DeployXlsForm;
use App\Models\Country;
use App\Models\Language;
use App\Models\Project;
use Carbon\Carbon;
use App\Models\Theme;
use App\Models\Module;
use App\Models\Xlsform;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ModuleVersion;
use App\Exports\XlsFormExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
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

class XlsformController extends CrudController
{

    public function create()
    {
        $data = $this->setupView(null);

        return view('xlsforms.create', $data);
    }

    public function edit(Xlsform $xlsform)
    {
        $data = $this->setupView($xlsform);

        return view('xlsforms.edit', $data);
    }

    public function store(XlsformCreateRequest $request)
    {
        // store and post to Rhomis app
        $attributes = $request->validated();


        // If the user has entered a 'new' project name, create the project:
        if(isset($attributes['new_project_name']) && $attributes['new_project_name']) {

            if(!auth()->user()) {
                abort(403);
            }

            $project = auth()->user()->projects()->create([
                'name' => $attributes['new_project_name'],
                'global' => 0,
            ]);

            $attributes['project_name'] = $project->name;
            unset($attributes['new_project_name']);
        } else {
            unset($attributes['new_project_name']);
        }



        $xlsform = Xlsform::create($attributes);

        // handle many-many relationships
        $xlsform->themes()->sync($request->input('themes'));
        $xlsform->moduleVersions()->sync($request->input('module_versions'));
//        $xlsform->countries()->sync($request->input('countries'));
        $xlsform->languages()->sync($request->input('languages'));

        return $xlsform->toJson();

    }

    public function update(XlsformUpdateRequest $request, XLsform $xlsform)
    {
        // store and post to RHOMIS app
        $attributes = $request->validated();

        $moduleVersions = [];
        foreach($request->input('module_versions') as $key => $value) {
            $moduleVersions[$value] = [
                'order' => $key,
                ];
        }

        $xlsform->update($attributes);



        // handle many-many relationships
        $xlsform->themes()->sync($request->input('themes'));
//        $xlsform->countries()->sync($request->input('countries'));
        $xlsform->languages()->sync($request->input('languages'));

        // include ordering of module versions
        $xlsform->moduleVersions()->sync($moduleVersions);

        // build and deploy form in background
        BuildXlsForm::dispatch($xlsform->name, Auth::user());
//        DeployXlsForm::dispatch($xlsform->name, Auth::user());

        return $xlsform->toJson();
    }

    public function setupView($xlsform = null)
    {
        $projects = Auth::user()->projects;
        $themes = Theme::all();
        $languages = Language::all();
        $countries = Country::all();

        // TODO: accept the fact that there will be multiple "is_current" modules and get all modules as a collection of 'current' versions.
        // Then it will be upto the Vue component to handle picking the correct version based on user input;
        $modules = ModuleVersion::with('module')->where('is_current', true)->get();

        if ($xlsform) {
            $xlsform->modules = $xlsform->moduleVersions->load('module')->sortBy('pivot.order')->values();
            $xlsform->load('themes', 'countries', 'languages');
        }

        return [
            'projects' => $projects,
            'themes' => $themes,
            'languages' => $languages,
            'countries' => $countries,
            'xlsform' => $xlsform,
            'modules' => $modules,
        ];
    }

    public function destroy(Xlsform $xlsform)
    {
        // check user is able to delete form
        if(auth()->user()->projects->pluck('name')->doesntContain($xlsform->project_name)) {
            abort(403, "You are not a member of this form's project, so you do not have permissions to delete the form");
        }

        if($xlsform->draft || $xlsform->complete) {
            abort(400, "This form has been deployed to the main RHoMIS app, and so cannot be deleted from here. To manage your project forms, use the links in the header menu");
        }

        $xlsform->delete();
        return response("form successfully deleted",200);
    }

}
