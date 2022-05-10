<?php

namespace App\Console\Commands;

use App\Models\ModuleVersion;
use App\Models\Xlsforms\ChoicesRow;
use App\Models\Xlsforms\SurveyRow;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class removeSuveyElementsForUnpublishedModuleVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-old-survey-elements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $versions = ModuleVersion::whereHas('module', function(Builder $query) {
            $query->where('core', 0);
        })->where('is_current', false)->get();

        $versions->each(function($version) {
            $version->surveyRows->each(function($row) {
                $row->surveyLabels()->delete();
            });

            $version->choicesRows->each(function($row) {
                $row->choicesLabels()->delete();
            });

            $version->surveyRows()->delete();
            $version->choicesRows()->delete();

        });


        SurveyRow::whereNotIn('module_version_id', ModuleVersion::all()->pluck('id')->toArray())->delete();
        ChoicesRow::whereNotIn('module_version_id', ModuleVersion::all()->pluck('id')->toArray())->delete();
    }
}
