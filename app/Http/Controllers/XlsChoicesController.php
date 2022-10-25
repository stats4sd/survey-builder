<?php

namespace App\Http\Controllers;

use App\Models\ModuleVersion;
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


        // merge modules that are required (locked to start and end)
        $usedModuleVersions = array_merge(
            $usedModuleVersions,
            ModuleVersion::whereHas('module', function ($query) {
                $query->where('locked_to_start', 1)
                    ->orWhere('locked_to_end', 1);
            })
                ->where('is_current', 1)
                ->get()
                ->pluck('id')
                ->toArray()
        );


        return ChoiceList::whereHas('choicesRows', function ($query) use ($usedModuleVersions) {
            $query->whereHas('moduleVersion', function($query) use($usedModuleVersions) {
               $query->whereIn('module_versions.id', $usedModuleVersions);
            });
        })
            ->with([
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
            ->map(function ($list) use ($xlsform) {
                $list->choicesRows = $list->choicesRows->map(function ($item) {
                    $item->choices_labels_by_lang = $item->choicesLabels ? $item->choicesLabels->groupBy('language_id') : null;
                    return $item;
                })
                    ->unique('name');

                $list->complete = $xlsform->choiceLists->where('pivot.complete', 1)->pluck('id')->contains($list->id);

                return $list;
            })
            ->toJson();
    }


    public function getLocalisableChoiceLists()
    {
        return ChoiceList::where('is_localisable', 1)->get();
    }

}
