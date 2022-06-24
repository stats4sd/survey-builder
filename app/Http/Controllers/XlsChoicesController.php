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
        return $xlsform->choiceLists()->with(['choicesRows.choicesLabels', 'surveyRows'])->get()
            ->map(function ($list) {
                $list->choicesRows = $list->choicesRows->map(function ($item) {
                        $item->choices_labels_by_lang = $item->choicesLabels ? $item->choicesLabels->groupBy('language_id') : null;
                        return $item;
                    });

                return $list;
            })
            ->toJson();
    }
}
