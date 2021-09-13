<?php

namespace App\Imports;

use App\Models\Module;
use App\Models\Language;
use App\Models\CoreVersion;
use App\Models\Xlsforms\SurveyRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * The core modules are built as a single Xlsform file, with all the core modules appearing together.
 * This import class reads that main "core" file, splits up the survey rows using the 'module' column, and then creates a new version of every core module it finds.
 * If it cannot match a module found in the file, it should throw an error to the user, requesting that the module is created in the system before importing.
 */
class CoreFileImport implements ToCollection, WithValidation, WithHeadingRow
{

    public CoreVersion $coreVersion;

    public function __construct (CoreVersion $coreVersion)
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

        $collection->each(function($rows) {

            // ignore any collection where module_for_import is not explicitly set
            if(!isset($rows->first()['module_for_import']) || !$rows->first()['module_for_import']) {
                return;
            }


            // find module to import new version
            $module = Module::firstWhere('slug', $rows->first()['module_for_import']);

            $moduleVersion = $this->coreVersion->moduleVersions()->create([
                'module_id' => $module->id,
                'question_count' => $rows->count(),
                'version_name' => $this->coreVersion->version_name,
                'mini' => $this->coreVersion->mini,
                'file' => $this->coreVersion->file,
            ]);
        });
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
           'module_for_import.exists' => 'The module_for_import cannot be found in the database.',
       ];
    }




}
