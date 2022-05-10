<?php

namespace App\Imports;

use App\Models\Xlsform;
use App\Models\Xlsforms\SelectedChoicesRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $countries = $collection->pluck('country_id')->map(function($name) {
            return [
                'list_name' => 'Country',
                'name' => $name,
                'is_custom' => 1,
                'xlsform_name' => $this->xlsform->name,
            ];
        })->values()->unique();

        $regions = $collection->pluck( 'country_id', 'region_id')->map(function($country_id, $name){
            return [
                'list_name' => 'region',
                'name' => $name,
                'is_custom' => 1,
                'xlsform_name' => $this->xlsform->name,
                'filter' => $country_id,
            ];
        })->values()->unique();

        $subregions = $collection->pluck( 'region_id', 'subregion_id')->map(function($region_id, $name){
            return [
                'list_name' => 'subregion',
                'name' => $name,
                'is_custom' => 1,
                'xlsform_name' => $this->xlsform->name,
                'filter' => $region_id,
            ];
        })->values()->unique();

        $villages = $collection->pluck( 'subregion_id', 'village_id')->map(function($subregion_id, $name){
            return [
                'list_name' => 'village',
                'name' => $name,
                'is_custom' => 1,
                'xlsform_name' => $this->xlsform->name,
                'filter' => $subregion_id,
            ];
        })->values()->unique();


        // households are optional
        $households = collect();
        if($collection[0]->has('household_id')) {
            $households = $collection->pluck( 'village_id', 'household_id')->map(function($village_id, $name){
                return [
                    'list_name' => 'household',
                    'name' => $name,
                    'is_custom' => 1,
                    'xlsform_name' => $this->xlsform->name,
                    'filter' => $village_id,
                ];
            })->values()->unique();
        }

        $locationOptions = $countries->merge($regions)->merge($subregions)->merge($villages)->merge($households);


       foreach($locationOptions as $option) {
           $result = SelectedChoicesRow::create($option);
       }


        // ($locationOptions->toArray(), ['list_name', 'name', 'filter']);



        // find the languages
        $languages = [];
        foreach($collection[0] as $header => $value) {
            if(Str::contains($header, '_label_')) {
                $languages[] = Str::after($header, '_label_');
            }
        }

        $languages = collect($languages)->unique();

        // handle labels


        return ;

    }
}
