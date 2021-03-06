<?php

namespace App\Jobs;

use App\Models\CoreVersion;
use App\Models\Module;
use App\Models\Language;
use App\Models\ModuleVersion;
use App\Models\Xlsforms\ChoiceList;
use Illuminate\Bus\Queueable;
use App\Models\Xlsforms\SurveyRow;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Str;

/** Accepts a collection of rows imported from an XLSform that have the SAME module_for_import value.
 * Will import the entire collection as new SurveyRow entries for the matching module
 */
class ImportCoreRowsToSurveysTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Collection $rows;
    public CoreVersion $coreVersion;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CoreVersion $coreVersion, Collection $rows){
        $this->coreVersion = $coreVersion;
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \JsonException
     */
    public function handle()
    {
        \Log::info('new sub - collection');
        // ignore any collection where module_for_import is not explicitly set
        if(!isset($this->rows->first()['module_for_import']) || !$this->rows->first()['module_for_import']) {
            return;
        }
        \Log::info('module_for_import found!');
        \Log::info($this->rows->first()['module_for_import']);

        $moduleSlug = $this->rows->first()['module_for_import'];
        $moduleVersion = ModuleVersion::whereHas('module', function($query) use ($moduleSlug) {
            $query->where('slug', $moduleSlug);
        })
            ->where('core_version_id', $this->coreVersion->id)
            ->firstOrFail();

        // start by assuming the module version is not localisable
        $moduleVersionLocalisable = false;

        $choiceLists = ChoiceList::all();

        foreach ($this->rows as $row) {

            //ignore empty this->rows
            if (!$row['type']) {
                continue;
            }

            $is_localisable = $row['localisable'] === "TRUE" ? true : ($row['localisable'] ?? false);
            if($is_localisable) {
                $moduleVersionLocalisable = true;
            }


            $choiceList = null;
            // for select questions, extract the choice list to allow linking
            if(Str::contains($row['type'], ['select_one', 'select_multiple'])) {

                    $choiceList = Str::replace(['select_one ', 'select_multiple '], ['', ''],$row['type']);
                // check choice list exists in database:
                if($choiceLists->pluck('list_name')->doesntContain($choiceList)) {
                    ChoiceList::updateOrCreate(['list_name' => $choiceList]);
                }

            }

            $surveyRow = SurveyRow::create([
                'module_version_id' => $moduleVersion->id,
                'type' => $row['type'],
                'name' => $row['name'] ?? '',
                'constraint' => $row['constraint'],
                'required' => $row['required'],
                'appearance' => $row['appearance'],
                'default' => $row['default'],
                'relevant' => $row['relevant'],
                'repeat_count' => $row['repeat_count'],
                'read_only' => $row['read_only'],
                'calculation' => $row['calculation'],
                'choice_filter' => $row['choice_filter'],
                'is_localisable' => $is_localisable,
                'localise_what' => json_encode(explode(', ', $row['localise_what']), JSON_THROW_ON_ERROR),
                'choice_list' => $choiceList,
            ]);

            foreach ($row as $header => $value) {
                if (preg_match('/(.+)::(.+) \((.+)\)/', $header, $matches) && $value !== null) {
                    $type = $matches[1];
                    $language = $matches[2];
                    $language_id = $matches[3];
                    $label = $value;

                    // check if language exists. If not, create it;
                    Language::firstOrCreate(
                        ['id' => $language_id],
                        ['name' => $language],
                    );

                    $surveyRow->surveyLabels()->create([
                        'type' => $type,
                        'language_id' => $language_id,
                        'label' => $label,
                    ]);
                }
            }

        }

        $moduleVersion->update([
            'is_localisable' => $moduleVersionLocalisable,
        ]);

        \Log::info('reached the end of module import for ' . $this->rows->first()['module_for_import']);
    }
}
