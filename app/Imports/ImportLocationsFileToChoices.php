<?php

namespace App\Imports;

use App\Models\Xlsform;
use App\Models\Xlsforms\SelectedChoicesLabel;
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
        $countries = $collection->pluck('country_id')->map(function ($name) {
            return [
                'list_name' => 'Country',
                'name' => $name,
                'is_custom' => 1,
                'xlsform_name' => $this->xlsform->name,
            ];
        })->values()->unique();

        $regions = $collection->pluck('country_id', 'region_id')->map(function ($country_id, $name) {
            return [
                'list_name' => 'region',
                'name' => $name,
                'is_custom' => 1,
                'xlsform_name' => $this->xlsform->name,
                'filter' => $country_id,
            ];
        })->values()->unique();

        $subregions = $collection->pluck('region_id', 'subregion_id')->map(function ($region_id, $name) {
            return [
                'list_name' => 'subregion',
                'name' => $name,
                'is_custom' => 1,
                'xlsform_name' => $this->xlsform->name,
                'filter' => $region_id,
            ];
        })->values()->unique();

        $villages = $collection->pluck('subregion_id', 'village_id')->map(function ($subregion_id, $name) {
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
        if ($collection[0]->has('household_id')) {
            $households = $collection->pluck('village_id', 'household_id')->map(function ($village_id, $name) {
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


        foreach ($locationOptions as $option) {
            $result = SelectedChoicesRow::create($option);
        }


        // ($locationOptions->toArray(), ['list_name', 'name', 'filter']);


        // find the languages
        $languages = [];
        foreach ($collection[0] as $header => $value) {
            if (Str::contains($header, '_label_')) {
                $languages[] = Str::after($header, '_label_');
            }
        }

        $languages = collect($languages)->unique();

        foreach ($languages as $language) {
            $collection->pluck('country_label_' . $language, 'country_id')->each(function ($name, $key) use ($language) {

                // find related choices row
                $choicesRow = SelectedChoicesRow::where('list_name', 'Country')->where('name', $key)->first();

                // TOCONSIDER: refactor to reduce db calls
                SelectedChoicesLabel::updateOrCreate([
                    'xlsform_selected_choice_row_id' => $choicesRow->id,
                    'language_id' => $language,
                    'label' => $name,
                ]);
            });

            $collection->pluck('region_label_' . $language, 'region_id')->each(function ($name, $key) use ($language) {

                // find related choices row
                $choicesRow = SelectedChoicesRow::where('list_name', 'region')->where('name', $key)->first();

                // TOCONSIDER: refactor to reduce db calls
                SelectedChoicesLabel::updateOrCreate([
                    'xlsform_selected_choice_row_id' => $choicesRow->id,
                    'language_id' => $language,
                    'label' => $name,
                ]);
            });
            $collection->pluck('subregion_label_' . $language, 'subregion_id')->each(function ($name, $key) use ($language) {

                // find related choices row
                $choicesRow = SelectedChoicesRow::where('list_name', 'subregion')->where('name', $key)->first();

                // TOCONSIDER: refactor to reduce db calls
                SelectedChoicesLabel::updateOrCreate([
                    'xlsform_selected_choice_row_id' => $choicesRow->id,
                    'language_id' => $language,
                    'label' => $name,
                ]);
            });

            $collection->pluck('village_label_' . $language, 'village_id')->each(function ($name, $key) use ($language) {

                // find related choices row
                $choicesRow = SelectedChoicesRow::where('list_name', 'village')->where('name', $key)->first();

                // TOCONSIDER: refactor to reduce db calls
                SelectedChoicesLabel::updateOrCreate([
                    'xlsform_selected_choice_row_id' => $choicesRow->id,
                    'language_id' => $language,
                    'label' => $name,
                ]);
            });

            if ($collection[0]->has('household_id')) {
                $collection->pluck('household_label_' . $language, 'household_id')->each(function ($name, $key) use ($language) {

                    // find related choices row
                    $choicesRow = SelectedChoicesRow::where('list_name', 'household')->where('name', $key)->where('xlsform_name', $this->xlsform->name)->first();

                    // TOCONSIDER: refactor to reduce db calls
                    SelectedChoicesLabel::updateOrCreate([
                        'xlsform_selected_choice_row_id' => $choicesRow->id,
                        'language_id' => $language,
                        'label' => $name,
                    ]);
                });
            }
        }

        // handle labels


        return;

    }
}
