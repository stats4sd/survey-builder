<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CoreFileUnpack implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            'survey' => new CoreSurveyUnpack(),
            'choices' => new CoreChoiceUnpack(),
        ];
    }
}
