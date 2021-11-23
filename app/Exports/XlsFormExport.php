<?php

namespace App\Exports;

use App\Models\Xlsform;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class XlsFormExport implements WithMultipleSheets
{

    public Xlsform $xlsform;

    public function __construct (Xlsform $xlsform){
        $this->xlsform = $xlsform;
    }

    public function sheets(): array
    {
        return [
            'survey' => new XlsSurveyExport($this->xlsform),
            'choices' => new XlsChoicesExport($this->xlsform),
            'settings' => new XlsSettingsExport($this->xlsform),
        ];
    }
}
