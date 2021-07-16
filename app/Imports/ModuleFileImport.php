<?php

namespace App\Imports;

use App\Models\Module;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ModuleFileImport implements WithMultipleSheets
{
    public function __construct(public Module $module)
    {
    }


    public function sheets(): array
    {
        return [
            'survey' => new ModuleSurveyImport($this->module),
            'choices' => new ModuleChoiceImport($this->module),
        ];
    }
}
