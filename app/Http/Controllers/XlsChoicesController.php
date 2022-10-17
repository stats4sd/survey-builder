<?php

namespace App\Http\Controllers;

use App\Models\Xlsform;
use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\ChoicesRow;

class XlsChoicesController extends Controller
{
    /** return all xlschoices from 'core' modules for demo */
    public function index(Xlsform $xlsform)
    {
        // get any customised lists for the current form; merge in any un-customised lists;
        //$lists = $xlsform->choiceLists;
        $usedModuleVersions = $xlsform->moduleVersions->pluck('id')->toArray();

        return ChoiceList::with([
            'choicesRows' => function ($query) use ($usedModuleVersions) {
                $query->whereHas('moduleVersion', function ($query) use ($usedModuleVersions) {
                    $query->where('is_current', 1)
                        ->whereIn('id', $usedModuleVersions);
                });
            },
            'choicesRows.choicesLabels' => function ($query) use ($usedModuleVersions) {
                $query->whereHas('choicesRow', function ($query) use ($usedModuleVersions) {
                    $query->whereHas('moduleVersion', function ($query) use ($usedModuleVersions) {
                        $query->where('is_current', 1)
                            ->whereIn('id', $usedModuleVersions);

                    });
                });
            },
            'surveyRows' => function ($query) use ($usedModuleVersions) {
                $query->whereHas('moduleVersion', function ($query) use ($usedModuleVersions) {
                    $query->where('is_current', 1)
                        ->whereIn('id', $usedModuleVersions);
                });
            },
        ])
            ->get()
            ->map(function ($list) {
                $list->choicesRows = $list->choicesRows->map(function ($item) {
                    $item->choices_labels_by_lang = $item->choicesLabels ? $item->choicesLabels->groupBy('language_id') : null;
                    return $item;
                });
                $list->complete = false;

                return $list;
            })
            ->toJson();
    }

}
