<?php

namespace App\Exports;

use App\Models\Xlsform;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class XlsSurveyExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{

    public Xlsform $xlsform;

    public function __construct (Xlsform $xlsform){
        $this->xlsform = $xlsform;
    }


    public function collection()
    {
        // get module versions in correct order
       // dd($this->xlsform->moduleVersions->sortBy('pivot.order')->pluck('module_id'));
        $collection = $this->xlsform->moduleVersions->sortBy('pivot.order')->map(function($version) {
            return $version->surveyRows->map(function($row) use ($version) {
                $row->order = $version->pivot->order;
                return $row;
            });


        })->flatten()
        // merge in language labels:
        ->map(function($row) {
            // hard code EN only for now
            $labels = $row->surveyLabels->load('language');

            foreach($labels as $label) {
                if($label->language_id == "en") {
                    $header = $label->type .  "::" . $label->language->name . " (" . $label->language_id . ")";
                    $row->$header = $label->label;
                }
            }
            return $row;
        })->groupBy('name');

       //ddd($collection['HFIAS_module'], $collection['crops_all'], $collection['food_environments_module']);

        // handle duplicate question names:
        // -
        $collection = $collection->map(function($name) {
            // include all entries where name is unique
            if(count($name) === 1) {
                return $name;
            }

            // include all entries where name is null (should all be notes)
            if($name[0]['name'] === "" || $name[0]['name'] === null) {
                return $name;
            }

            // check if duplicated names are begin + end groups or not
            return $name->sortByDesc('module_version_id')->filter(function($row, $key) {
                if(str_starts_with($row['type'], 'begin') || str_starts_with($row['type'], 'end')) {
                    return true;
                }

                // if the row is from a core module, it should be overridden by the row from the optional module.
                // if multiple rows with the same name are from optional modules, all will be returned - this will result in an XLSform compilation error, but it should continue to highlight the error to the user + Rhomis team.
                if($row['is_core']) {
                    return false;
                }
                return true;
            });
        })->flatten()->sortBy([
            ['order', 'asc'],
            ['id', 'asc'],
        ]);

        return $collection;

    }

    public function map ($surveyRow): array
    {
        $labelHeader = "label::English (en)";
        $hintHeader = "hint::English (en)";
        $constraintHeader = "constraint_message::English (en)";
        $requiredHeader = "required_message::English (en)";

        return [
            '-',
            $surveyRow->moduleVersion->module->slug,
            $surveyRow->moduleVersion->module->slug,
            $surveyRow->type,
            $surveyRow->name,
            $surveyRow->$labelHeader,
            $surveyRow->$hintHeader,
            $surveyRow->constraint,
            $surveyRow->$constraintHeader,
            $surveyRow->required,
            $surveyRow->$requiredHeader,
            $surveyRow->appearance,
            $surveyRow->default,
            $surveyRow->relevant,
            $surveyRow->repeat_count,
            $surveyRow->read_only,
            $surveyRow->calculation,
            $surveyRow->choice_filter,
        ];
    }


    public function headings (): array
    {
        $headers = [
            'localisable',
            'module_for_import',
            'module_name',
            'type',
            'name',
            'label::English (en)',
            'hint::English (en)',
            'constraint',
            'constraint_message::English (en)',
            'required',
            'required_message::English (en)',
            'appearance',
            'default',
            'relevant',
            'repeat_count',
            'read_only',
            'calculation',
            'choice_filter',
            'body::accuracyThreshold',
        ];

        return $headers;
    }

    public function title (): string
    {
       return "survey";
    }

}
