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
    public Xlsform $xlsform;

    public function __construct(Xlsform $xlsform)
    {
        $this->xlsform = $xlsform;
    }

    public function collection()
    {

        $coreOptionRows = ChoicesRow::where('module_version_id', null)->with('choicesLabels.language')->get();

        $optionalModulesRows = $this->xlsform->moduleVersions->map(function ($version) {
            return $version->choicesRows->load('choicesLabels.language');
        })->flatten();

        return $coreOptionRows
            ->merge($optionalModulesRows)
            ->map(function ($row) {


                $labels = $row->choicesLabels;
                $header = "label::English (en)";
                $row->$header = '';

                foreach ($labels as $label) {
                    foreach ($this->xlsform->languages as $language) {
                        if ($label->language_id === $language->id) {
                            $row->$header = "label::" . $language->name . " (" . $language->id . ")";

                            // only need one language-specific header per language for choices sheet;
                            break;
                        }
                    }
                }
                return $row;
            })
            // make sure no duplicates from different modules exist.
            ->unique(function ($row) {
                return $row['list_name'] . $row['name'];
            });


    }

    public function map($choicesRow): array
    {

        $labelHeaders = [];

        foreach ($this->xlsform->languages as $language) {
            $labelHeaders[] = "label::" . $language->name . " (" . $language->id . ")";
        }

        $newRow = [
            $choicesRow->list_name,
            $choicesRow->name
        ];

        foreach ($labelHeaders as $labelHeader) {
            $newRow[] = $choicesRow->$labelHeader;
        }

        return $newRow;

    }

    public function headings(): array
    {

        $labelHeaders = [];

        foreach ($this->xlsform->languages as $language) {
            $labelHeaders[] = "label::" . $language->name . " (" . $language->id . ")";
        }

        $headers = [
            'list_name',
            'name'
        ];

        foreach ($labelHeaders as $labelHeader) {
            $headers[] = $labelHeader;
        }

        return $headers;
    }

    public function title(): string
    {
        return "choices";
    }

}
