<?php

namespace App\Http\Controllers;

use App\Models\ModuleVersion;
use Illuminate\Http\Request;

class XlsSurveyRowController extends Controller
{
    public function getQuestionsForModule(ModuleVersion $moduleversion)
    {
        return $moduleversion->surveyRows()->with(['surveyLabels','choiceList'])->get();
    }
}
