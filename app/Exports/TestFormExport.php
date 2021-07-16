<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TestFormExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'survey' => new TestSurveyExport(),
            'choices' => new TestChoicesExport(),
            'settings' => new TestSettingsExport(),
        ];
    }
}
