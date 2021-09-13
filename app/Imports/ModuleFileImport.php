<?php

namespace App\Imports;

use App\Models\Module;
use App\Models\ModuleVersion;
use CreateModuleVersionXlsformTable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ModuleFileImport implements ToCollection
{

    public ModuleVersion $moduleVersion;

    public function __construct(ModuleVersion $moduleVersion)
    {
        $this->moduleVersion = $moduleVersion;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        if(!isset($rows['module_for_import']) || !$rows['module_for_import']) {
            return;
        }

        $this->moduleVersion->question_count = $rows->count();
        $this->moduleVersion->save();

        return;
    }
}
