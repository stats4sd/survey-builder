<?php

namespace App\Imports;

use App\Models\Module;
use App\Models\ModuleVersion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ModuleFileUnpack implements WithMultipleSheets
{
    use Importable;


    public ModuleVersion $moduleVersion;

    public function __construct(ModuleVersion $moduleVersion)
    {
        $this->moduleVersion = $moduleVersion;
    }


    public function sheets(): array
    {
        return [
            'survey' => new ModuleSurveyUnpack($this->moduleVersion),
            'choices' => new ModuleChoiceUnpack($this->moduleVersion),
        ];
    }
}
