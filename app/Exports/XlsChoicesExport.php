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

        // get any custom selected choice rows:
        $selectedRows = $this->xlsform->selectedChoicesRows->load('selectedChoicesLabels')
        ->map(function($row) {
            $labels = $row->selectedChoicesLabels;


            foreach($labels as $label) {
                foreach($this->xlsform->languages as $language) {
                    if($label->language_id === $language->id) {
                        $header = "label::" . $language->name . " (" . $language->id . ")";
                        $row->$header = $label->label;
                    }
                }
            }

            return $row;
        });

        $selectedChoiceLists = $selectedRows->pluck('list_name')->unique()->toArray();

        // handle location lists
        if($this->xlsform->location_file_url) {
            $selectedChoiceLists[] = 'Country';
            $selectedChoiceLists[] = 'region';
            $selectedChoiceLists[] = 'subregion';
            $selectedChoiceLists[] = 'village';

        }

        $optionalModulesRows = $this->xlsform->moduleVersions->map(function ($version) use ($selectedChoiceLists) {
            return $version
                ->choicesRows
                // if any localisable lists have selected items, do not get the 'defaults' for that list.
                ->whereNotIn('list_name', $selectedChoiceLists)
                ->load('choicesLabels.language');
        })->flatten();


        $defaultRows = $optionalModulesRows
            ->map(function ($row) {

                $labels = $row->choicesLabels;
                $header = "label::English (en)";
                $row->$header = '';

                foreach ($labels as $label) {
                    foreach ($this->xlsform->languages as $language) {
                        if ($label->language_id === $language->id) {
                            $header = "label::" . $language->name . " (" . $language->id . ")";
                            $row->$header = $label->label;

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

        // merge in custom selected options;
        return $selectedRows->merge($defaultRows);

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

        // add filters for locations
        $newRow[] = $choicesRow->filter;

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

        $headers[] = 'filter';

        return $headers;
    }

    public function title(): string
    {
        return "choices";
    }

}
