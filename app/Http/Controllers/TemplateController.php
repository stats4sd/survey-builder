<?php

namespace App\Http\Controllers;

use App\Exports\LocationTemplateExport;
use App\Exports\LocationTemplateWithHouseholdsExport;
use App\Models\Xlsform;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TemplateController extends Controller
{
    public function download(Xlsform $xlsform)
    {
        $languages = $xlsform->languages;

        return Excel::download(new LocationTemplateExport($languages), 'location-template.csv');
    }

    public function downloadHousehold(Xlsform $xlsform)
    {
        $languages = $xlsform->languages;

        return Excel::download(new LocationTemplateWithHouseholdsExport($languages), 'location-template.csv');
    }
}
