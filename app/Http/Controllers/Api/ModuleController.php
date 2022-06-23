<?php

namespace App\Http\Controllers\Api;

use App\Models\Module;
use App\Models\ModuleVersion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        return $module->load('moduleVersions');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        //
    }

    public function getDetails(ModuleVersion $moduleversion)
    {
        $moduleVersion = $moduleversion->load(['surveyRows.surveyLabels','surveyRows.choiceList', 'module.indicators', 'module.authors', 'module.languages', 'module.sdgs', 'module.currentVersions']);
        $moduleVersion->indicator_list = $moduleVersion->module->indicators ? $moduleVersion->module->indicators->pluck('name')->toArray() : [];

        if(!$moduleVersion->is_current) {
            $moduleVersion->newestVersion = $moduleVersion->module->currentVersions[0]->load('surveyRows.surveyLabels', 'surveyRows.choiceList');
        }
        return $moduleVersion;
    }
}
