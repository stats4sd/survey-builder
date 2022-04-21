<?php

namespace App\Console\Commands;

use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\ChoicesRow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateChoiceListsFromXlsChoicesRows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-choice-lists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks through the xls_choices_rows table for new list_names, and adds the corresponding entries into the choice_lists table';

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
        $choices = ChoicesRow::select('list_name', DB::raw("max(`is_localisable`) as `is_localisable`"))->groupBy('list_name')->get();

        foreach ($choices as $choice) {
            $list = ChoiceList::updateOrCreate(
                ['list_name' => $choice->list_name],
                ['is_localisable' => $choice->is_localisable ?? 0],
            );
        }

        $this->info('choice lists updated. ' . $choices->count() . ' list names found in Xls Choice Rows');

    }
}
