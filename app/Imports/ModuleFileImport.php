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

    public function collection(Collection $rows)
    {
        dump($rows);
        $this->moduleVersion->updateQuietly([
            'question_count' => $rows->count(),
        ]);
        ddd($rows->count());
    }

    public function rules(): array
    {
        return [
            'module_for_import' => ['exists:modules,slug'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'module_for_import.exists' => 'The module_for_import cannot be found in the database.',
        ];
    }
}
