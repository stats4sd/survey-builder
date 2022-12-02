<?php

namespace App\Console\Commands;

use App\Models\Xlsform;
use Illuminate\Console\Command;

class RemoveLockedModulesFromXlsForms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xlsform:remove-locked-modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlinks any modules marked as locked_to_start or end from the form. Only needed in development, to update forms to the new system where those locked modules are automatically added during build.';

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
        Xlsform::all()->each(function($xlsform) {
           $modules = $xlsform->moduleVersions->where('');
        });
    }
}
