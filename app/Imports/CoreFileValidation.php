<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CoreFileValidation implements ToCollection, WithHeadingRow, WithValidation
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
        ];
    }

    public function customValidationMessages ()
    {
       return [
           'module_for_import.exists' => 'The module_for_import canot be found in the database.',
       ];
    }
}
