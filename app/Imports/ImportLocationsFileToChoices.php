<?php

namespace App\Imports;

use App\Models\Xlsform;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportLocationsFileToChoices implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{

    public Xlsform $xlsform;

    public function __construct(Xlsform $xlsform)
    {
        $this->xlsform = $xlsform;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {

            // import countries
            ddd($row);


        }
    }
}
