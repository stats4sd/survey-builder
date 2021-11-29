<?php

namespace App\Imports;

use App\Models\CoreVersion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CoreFileUnpack implements WithMultipleSheets
{

    public CoreVersion $coreVersion;

    public function __construct(CoreVersion $coreVersion)
    {
        $this->coreVersion = $coreVersion;
    }

    public function sheets(): array
    {
        return [
            'survey' => new CoreSurveyUnpack($this->coreVersion),
            'choices' => new CoreChoiceUnpack($this->coreVersion),
        ];
    }
}
