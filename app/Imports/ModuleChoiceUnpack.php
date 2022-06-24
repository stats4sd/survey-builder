<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\ChoicesRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ModuleChoiceUnpack implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{

    public ModuleVersion $moduleVersion;

    public function __construct(ModuleVersion $moduleVersion)
    {
        $this->moduleVersion = $moduleVersion;
    }


    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            //ignore empty rows
            if ($row['list_name'] === null) {
                continue;
            }

            // find or create the choice list entry to link to this choice.
            $choiceList = ChoiceList::firstOrCreate([
                'module_version_id' => $this->moduleVersion->id,
                'list_name' => $row['list_name'],
            ], [
                'is_localisable' => $row['localisable'] ?? 0,
                'is_units' => $row['is_units'] ?? 0,
                'is_locations' => $row['is_locations'] ?? 0,
            ]);

            $ChoicesRow = ChoicesRow::create([
                'module_version_id' => $this->moduleVersion->id,
                'list_name' => $row['list_name'],
                'name' => $row['name'],
                'is_localisable' => $row['localisable'] ?? 0,
                'list_type' => $row['list_type'] ?? null,
                'choice_list_id' => $choiceList->id,
            ]);





            foreach ($row as $header => $value) {
                if (preg_match('/(.+)::(.+) \((.+)\)/', $header, $matches) && $value !== null) {
                    $type = $matches[1];
                    $language = $matches[2];
                    $language_id = $matches[3];
                    $label = $value;

                    //check if langauge exists. If not, create it:
                    Language::firstOrCreate(
                        ['id' => $language_id],
                        ['name' => $language]
                    );

                    $ChoicesRow->ChoicesLabels()->create([
                        'type' => $type,
                        'language_id' => $language_id,
                        'label' => $label,
                    ]);
                }
            }
        }
    }
}
