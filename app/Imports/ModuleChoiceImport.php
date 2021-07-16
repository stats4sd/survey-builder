<?php

namespace App\Imports;

use App\Models\Language;
use App\Models\Module;
use App\Models\Xlsforms\ChoicesRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ModuleChoiceImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    public function __construct(public Module $module)
    {
    }


    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            //ignore empty rows
            if ($row['list_name'] === null) {
                continue;
            }

            $ChoicesRow = ChoicesRow::create([
                'module_id' => $this->module->id,
                'list_name' => $row['list_name'],
                'name' => $row['name'],
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
