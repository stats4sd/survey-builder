<?php

namespace App\Imports;

use App\Jobs\ImportCoreRowsToSurveysTable;
use App\Models\CoreVersion;
use App\Models\Module;
use App\Models\Language;
use App\Models\Xlsforms\SurveyRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CoreSurveyUnpack implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{

    public CoreVersion $coreVersion;

    public function __construct(CoreVersion $coreVersion)
    {
        $this->coreVersion = $coreVersion;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // split into modules
        $collection = $collection->groupBy('module_for_import');

        \Log::info('importing from survey');
        \Log::info($collection);

        $collection->each(function($rows) {
            ImportCoreRowsToSurveysTable::dispatch($this->coreVersion, $rows);
        });
    }
}
