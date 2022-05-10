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

    public function __construct(Xlsform $xlsform)
    {
        $this->xlsform = $xlsform;
    }


    public function collection()
    {
        // get module versions in correct order
        // dd($this->xlsform->moduleVersions->sortBy('pivot.order')->pluck('module_id'));
        $collection = $this->xlsform->moduleVersions->sortBy('pivot.order')->map(function ($version) {
            return $version->surveyRows->map(function ($row) use ($version) {
                $row->order = $version->pivot->order;
                return $row;
            });


        })->flatten()
            // merge in language labels:
            ->map(function ($row) {

                $labels = $row->surveyLabels->load('language');


                foreach ($this->xlsform->languages as $language) {
                    foreach ($labels as $label) {
                        if ($label->language_id == $language->id) {
                            $header = $label->type . "::" . $label->language->name . " (" . $label->language_id . ")";
                            $row->$header = $label->label;

                        }
                    }
                }
                return $row;
            })->groupBy('name');

        //ddd($collection['HFIAS_module'], $collection['crops_all'], $collection['food_environments_module']);

        // handle duplicate question names:
        // -
        $collection = $collection->map(function ($name) {
            // include all entries where name is unique
            if (count($name) === 1) {
                return $name;
            }

            // include all entries where name is null (should all be notes)
            if ($name[0]['name'] === "" || $name[0]['name'] === null) {
                return $name;
            }

            // check if duplicated names are begin + end groups or not
            return $name->sortByDesc('module_version_id')->filter(function ($row, $key) {
                if (str_starts_with($row['type'], 'begin') || str_starts_with($row['type'], 'end')) {
                    return true;
                }

                // if the row is from a core module, it should be overridden by the row from the optional module.
                // if multiple rows with the same name are from optional modules, all will be returned - this will result in an XLSform compilation error, but it should continue to highlight the error to the user + Rhomis team.
                if ($row['is_core']) {
                    return false;
                }
                return true;
            });
        })->flatten()->sortBy([
            ['order', 'asc'],
            ['id', 'asc'],
        ]);

        $idsToRemove = [];
        // finally, check if there are begin and end groups or repeats that are now empty due to the removal of duplicate question names:
        foreach($collection as $index => $row) {
            if (str_starts_with($row['type'], 'begin') && str_starts_with($collection[$index+1]['type'], 'end')) {
                $idsToRemove[] = $row['id'];
                $idsToRemove[] = $collection[$index+1]['id'];
            }
        }
        $collection = $collection->whereNotIn('id', $idsToRemove);

        return $collection;

    }

    public function map($surveyRow): array
    {
        $labelHeaders = [];
        $hintHeaders = [];
        $constraintHeaders = [];
        $requiredHeaders = [];


        foreach ($this->xlsform->languages as $language) {
            $labelHeaders[] = "label::" . $language->name . ' (' . $language->id . ')';
            $hintHeaders[] = "hint::" . $language->name . ' (' . $language->id . ')';
            $constraintHeaders[] = "constraint_message::" . $language->name . ' (' . $language->id . ')';
            $requiredHeaders[] = "required_message::" . $language->name . ' (' . $language->id . ')';
        }

        $newRow = [
            '-',
            $surveyRow->moduleVersion->module->slug,
            $surveyRow->moduleVersion->module->slug,
            $surveyRow->type,
            $surveyRow->name,
        ];

        foreach ($labelHeaders as $labelHeader) {
            $newRow[] = $surveyRow->$labelHeader;
        }

        foreach ($hintHeaders as $hintHeader) {
            $newRow[] = $surveyRow->$hintHeader;
        }


        $newRow[] = $surveyRow->constraint;

        foreach ($constraintHeaders as $constraintHeader) {
            $newRow[] = $surveyRow->$constraintHeader;
        }

        $newRow[] = $surveyRow->required;

        foreach ($requiredHeaders as $requiredHeader) {
            $newRow[] = $surveyRow->$requiredHeader;
        }

        $newRow[] = $surveyRow->appearance;
        $newRow[] = $surveyRow->default;
        $newRow[] = $surveyRow->relevant;
        $newRow[] = $surveyRow->repeat_count;
        $newRow[] = $surveyRow->read_only;
        $newRow[] = $surveyRow->calculation;
        $newRow[] = $surveyRow->choice_filter;

        return $newRow;
    }


    public function headings(): array
    {
        $labelHeaders = [];
        $hintHeaders = [];
        $constraintHeaders = [];
        $requiredHeaders = [];


        foreach ($this->xlsform->languages as $language) {
            $labelHeaders[] = "label::" . $language->name . ' (' . $language->id . ')';
            $hintHeaders[] = "hint::" . $language->name . ' (' . $language->id . ')';
            $constraintHeaders[] = "constraint_message::" . $language->name . ' (' . $language->id . ')';
            $requiredHeaders[] = "required_message::" . $language->name . ' (' . $language->id . ')';
        }


        $headers = [
            'localisable',
            'module_for_import',
            'module_name',
            'type',
            'name'
        ];

        foreach ($labelHeaders as $labelHeader) {
            $headers[] = $labelHeader;
        }
        foreach ($hintHeaders as $hintHeader) {
            $headers[] = $hintHeader;
        }
        $headers[] = 'constraint';
        foreach ($constraintHeaders as $constraintHeader) {
            $headers[] = $constraintHeader;
        }
        $headers[] = 'required';
        foreach ($requiredHeaders as $requiredHeader) {
            $headers[] = $requiredHeader;
        }
        $headers[] = 'appearance';
        $headers[] = 'default';
        $headers[] = 'relevant';
        $headers[] = 'repeat_count';
        $headers[] = 'read_only';
        $headers[] = 'calculation';
        $headers[] = 'choice_filter';
        $headers[] = 'body::accuracyThreshold';

        return $headers;
    }

    public function title(): string
    {
        return "survey";
    }

}
