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
        return $this->xlsform->moduleVersions->map(function($version) {
            return $version->surveyRows;
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
        });
    }

    public function map ($surveyRow): array
    {
        $labelHeader = "label::English (en)";
        $hintHeader = "hint::English (en)";
        $constraintHeader = "constraint_message::English (en)";
        $requiredHeader = "required_message::English (en)";

        return [
            '-',
            $surveyRow->module->slug,
            $surveyRow->module->slug,
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
