<?php

namespace App\Imports;

use App\Models\Module;
use App\Models\ModuleVersion;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ModuleFileValidation implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{

    public ModuleVersion $moduleVersion;

    public function __construct(ModuleVersion $moduleVersion)
    {
        $this->moduleVersion = $moduleVersion;
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    public function prepareForValidation ($data, $index)
    {
        if(isset($data['module_for_import'])) {
            return $data;
        }

        // otherwise, the row is ignored on import, so can be ignored for validation
        return ['name' => 'pass', 'type' => 'pass'];
    }


    public function rules (): array
    {
        return [
            'module_for_import' => [
                Rule::in([$this->moduleVersion->module->slug])
            ],
            'type' => 'required',
        ];
    }

    public function customValidationMessages ()
    {
       return [
           'module_for_import.in' => 'The module_for_import found in the worksheet does not match the current module.',
       ];
    }
}
