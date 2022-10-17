<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\SurveyLabel;
use App\Models\Xlsforms\SurveyRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ModuleSurveyUnpack implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    public ModuleVersion $moduleVersion;

    public function __construct(ModuleVersion $moduleVersion)
    {
        $this->moduleVersion = $moduleVersion;
    }


    /**
     * @throws \JsonException
     */
    public function collection(Collection $rows)
    {
        //
        $localisable = false;

        $this->moduleVersion->update(
            [
                'question_count' => $rows->count(),
            ]
        );

        foreach ($rows as $row) {

            //ignore empty rows
            if ($row['module_for_import'] === null) {
                continue;
            }

            if (isset($row['localisable'])) {
                $localisable = $row['localisable'] === "TRUE" || (($row['localisable'] ?? false));
            }


            $choiceLists = ChoiceList::all();

            $choiceList = '';
            // for select questions, extract the choice list to allow linking
            if (Str::contains($row['type'], ['select_one', 'select_multiple'])) {
                $choiceList = Str::replace(['select_one ', 'select_multiple '], ['', ''], $row['type']);

                if ($choiceLists->pluck('list_name')->doesntContain($choiceList)) {
                    ChoiceList::create(['list_name' => $choiceList]);
                }
            }

            $surveyRow = SurveyRow::create([
                'module_version_id' => $this->moduleVersion->id,
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
                'is_localisable' => $localisable,
                'localise_what' => isset($row['localise_what']) ? json_encode(explode(', ', $row['localise_what']), JSON_THROW_ON_ERROR) : null,
                'choice_list' => ($choiceList !== '') ? $choiceList : null,
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
            'is_localisable' => (bool)$localisable,
        ]);
    }
}
