<?php

namespace App\Jobs;

use App\Models\Module;
use App\Models\Language;
use Illuminate\Bus\Queueable;
use App\Models\Xlsforms\SurveyRow;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportCoreRowsToSurveysTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Collection $rows){}

    /**
     * Execute the job.
     *
     * @return void
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
        $module = Module::where('slug', $moduleSlug)->first();

        foreach ($this->rows as $row) {

            //ignore empty this->rows
            if ($row['type'] == null) {
                continue;
            }

            $surveyRow = SurveyRow::create([
                'module_id' => $module->id,
                'type' => $row['type'],
                'name' => $row['name'],
                'constraint' => $row['constraint'],
                'required' => $row['required'],
                'appearance' => $row['appearance'],
                'default' => $row['default'],
                'relevant' => $row['relevant'],
                'repeat_count' => $row['repeat_count'],
                'read_only' => $row['read_only'],
                'calculation' => $row['calculation'],
                'choice_filter' => $row['choice_filter'],
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
    }
}
