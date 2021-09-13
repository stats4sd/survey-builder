<?php

namespace App\Imports;

use App\Models\Module;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ModuleFileUnpack implements WithMultipleSheets
{
    public Module $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }


    public function sheets(): array
    {
        return [
            'survey' => new ModuleSurveyUnpack($this->module),
            'choices' => new ModuleChoiceUnpack($this->module),
        ];
    }
}
