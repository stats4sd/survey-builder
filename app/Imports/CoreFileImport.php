<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CoreFileImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'survey' => new CoreSurveyImport(),
            'choices' => new CoreChoiceImport(),
        ];
    }
}
