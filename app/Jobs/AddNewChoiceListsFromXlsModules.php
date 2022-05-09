<?php

namespace App\Jobs;

use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\ChoicesRow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job that parses the choices_rows entries and looks for new choice_lists that are not currently in the choice_lists_table.
 */
class AddNewChoiceListsFromXlsModules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    }
}
