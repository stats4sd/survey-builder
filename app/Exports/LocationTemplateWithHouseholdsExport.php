<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationTemplateWithHouseholdsExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public Collection $languages;

    public function __construct(Collection $languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        $headings = ['country_id'];
        foreach ($this->languages as $language) {
            $headings[] = "country_label_{$language->id}";
        }

        $headings[] = 'region_id';
        foreach ($this->languages as $language) {
            $headings[] = "region_label_{$language->id}";
        }

        $headings[] = 'subregion_id';
        foreach ($this->languages as $language) {
            $headings[] = "subregion_label_{$language->id}";
        }

        $headings[] = 'village_id';
        foreach ($this->languages as $language) {
            $headings[] = "village_label_{$language->id}";
        }

        $headings[] = 'household_id';
        foreach ($this->languages as $language) {
            $headings[] = "household_label_{$language->id}";
        }

        return $headings;
    }
}
