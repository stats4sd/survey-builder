<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CoreFileValidation implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        return;
    }

    public function rules (): array
    {
        return [
            'module_for_import' => ['exists:modules,slug'],
            'name' => ['required'],
            'type' => ['required'],
        ];
    }

    public function prepareForValidation ($data, $index)
    {
        if(isset($data['module_for_import'])) {
            return $data;
        }

        // otherwise, the row is ignored on import, so can be ignored for validation
        return ['name' => 'pass', 'type' => 'pass'];
    }

    public function customValidationMessages ()
    {
       return [
           'module_for_import.exists' => 'The module_for_import cannot be found in the database.',
       ];
    }
}
