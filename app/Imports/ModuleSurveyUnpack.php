<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\Module;
use App\Models\Xlsforms\SurveyLabel;
use App\Models\Xlsforms\SurveyRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ModuleSurveyUnpack implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    public function __construct(public ModuleVersion $moduleVersion)
    {
    }


    /**
     * @throws \JsonException
     */
    public function collection(Collection $rows)
    {
        //
        $moduleLocalisable = false;

        foreach ($rows as $row) {

            //ignore empty rows
            if ($row['module_for_import'] === null) {
                continue;
            }

            $localisable = $row['localisable'] === "TRUE" || (($row['localisable'] ?? false));

            if($localisable) {
                $moduleLocalisable = true;
            }

            $surveyRow = SurveyRow::create([
                'module_id' => $this->moduleVersion->id,
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
                'localisable' => $localisable,
                'localise_what' => json_encode(explode(', ', $row['localise_what']), JSON_THROW_ON_ERROR),

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
        $this->moduleVersion->update([
            'is_localisable' => $moduleLocalisable
        ]);
    }
}
