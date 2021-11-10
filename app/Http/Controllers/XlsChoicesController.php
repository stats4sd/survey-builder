<?php

namespace App\Http\Controllers;

use App\Models\Xlsforms\ChoicesRow;

class XlsChoicesController extends Controller
{
    /** return all xlschoices from 'core' modules for demo */
    public function index()
    {
        return ChoicesRow::with('ChoicesLabels')->get()
            ->map(function ($item) {
                $item->choices_labels_by_lang = $item->choicesLabels?->groupBy('language_id');
                return $item;
            })
            ->groupBy('list_name')
            ->toJson();
    }
}
