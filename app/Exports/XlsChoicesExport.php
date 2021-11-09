<?php

namespace App\Exports;

use App\Models\Xlsform;
use App\Models\Xlsforms\ChoicesRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use stdClass;

class XlsChoicesExport implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    public function __construct(public Xlsform $xlsform){}

    public function collection()
    {

        $coreOptionRows = ChoicesRow::where('module_id', null)->with('choicesLabels.language')->get();

        $optionalModulesRows = $this->xlsform->moduleVersions->map(function($version) {
            return $version->choicesRows->load('choicesLabels.language');
        })->flatten();

        return $coreOptionRows
        ->merge($optionalModulesRows)
        ->map(function($row) {


        $labels = $row->choicesLabels;
        $header = "label::English (en)";
        $row->$header = '';

            foreach($labels as $label) {
                // $header = $label->type . ":: " . $label->language->name . " (" . $label->language_id . ")";

                if($label->language_id == "en" && $row->$header == '') {
                    $row->$header = $label->label;
                }
            }
            return $row;
        })
        // make sure no duplicates from different modules exist.
        ->unique(function($row) {
            return $row['list_name'].$row['name'];
        });


    }

    public function map ($choicesRow): array
    {

        $labelHeader = "label::English (en)";

       return [
           $choicesRow->list_name,
           $choicesRow->name,
           $choicesRow->$labelHeader
       ];
    }

    public function headings (): array
    {
        return [
            'list_name',
            'name',
            'label::English (en)',
        ];
    }

    public function title (): string
    {
       return "choices";
    }

}
