<?php

namespace App\Imports;

use App\Models\CoreVersion;
use App\Models\Language;
use Illuminate\Support\Collection;
use App\Models\Xlsforms\ChoicesRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CoreChoiceUnpack implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{

    public CoreVersion $coreVersion;

    public function __construct(CoreVersion $coreVersion)
    {
        $this->coreVersion = $coreVersion;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            //ignore empty rows
            if (!$row['list_name']) {
                continue;
            }

            // get the moduleVersion for the metadata_start module linked to this core version:
            $moduleStartVersionId = $this->coreVersion->moduleVersions()->whereHas('module', function($query) {
                $query->where('slug', 'metadata_start');
            })->first()->id;

            $ChoicesRow = ChoicesRow::create([
                // link the choices to the metadata(start) of the current core version.
                'module_version_id' => $moduleStartVersionId,
                'list_name' => $row['list_name'],
                'name' => $row['name'],
                'is_localisable' => $row['localisable'] ?? 0,
                'list_type' => $row['list_type'] ?? null,
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
