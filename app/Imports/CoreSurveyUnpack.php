<?php

namespace App\Imports;

use App\Models\Xlsforms\SurveyRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CoreSurveyUnpack implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        // split into modules
        $collection = $collection->groupBy('module_for_import');

        $collection->each(function($rows) {

            // ignore any collection where module_for_import is not explicitly set
            if(!isset($rows->first()['module_for_import']) || !$rows->first()['module_for_import']) {
                return;
            }

            foreach ($rows as $row) {

                //ignore empty rows
                if ($row['type'] === null) {
                    continue;
                }

                $surveyRow = SurveyRow::create([
                    'module_id' => $this->module->id,
                    'type' => $row['type'],
                    'name' => $row['name'],
                    'constraint' => $row['constraint'],
                    'required' => $row['required'],
                    'appearance' => $row['appearance'],
                    'default' => $row['default'],
                    'relevant' => $row['relevant'],
                    'repeat_count' => $row['repeat_count'],
                    'read_only' => $row['read_only'],
                    'calculation' => $row['calculation'],
                    'choice_filter' => $row['choice_filter'],
                ]);

                foreach ($row as $header => $value) {
                    if (preg_match('/(.+)::(.+) \((.+)\)/', $header, $matches) && $value !== null) {
                        $type = $matches[1];
                        $language = $matches[2];
                        $language_id = $matches[3];
                        $label = $value;

                        // check if language exists. If not, create it;
                        Language::firstOrCreate(
                            ['id' => $language_id],
                            ['name' => $language],
                        );

                        $surveyRow->surveyLabels()->create([
                            'type' => $type,
                            'language_id' => $language_id,
                            'label' => $label,
                        ]);
                    }
                }
            }
        });
    }
}
