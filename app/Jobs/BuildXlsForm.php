<?php

namespace App\Jobs;

use App\Events\BuildXlsFormComplete;
use App\Events\BuildXlsFormFailed;
use App\Events\DeploymentErrorOccured;
use App\Events\DeployXlsFormComplete;
use App\Exports\XlsFormExport;
use App\Models\User;
use App\Models\Xlsform;
use App\Services\PyXformService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Telescope\Telescope;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class BuildXlsForm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Xlsform $xlsform;
    public ?User $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($xlsform, User $user = null)
    {
        $this->xlsform = Xlsform::find($xlsform);
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = $this->xlsform->name . '/' . Str::slug(Carbon::now()->toISOString()) . '/' . $this->xlsform->name . '.xlsx';


        $file = Excel::store(new XlsFormExport($this->xlsform), $path);

        $this->xlsform->update([
            'xlsfile' => $path
        ]);

         test built form against pyxform standard;
         $testResult = (new PyXformService)->testXlsform($this->xlsform);

        if ($testResult !== true) {

            BuildXlsFormFailed::dispatch($this->xlsform->name, $testResult->join(', '), 500, $this->user);

        } else {


        // broadcast completion of xlsfile
        BuildXlsFormComplete::dispatch($this->xlsform->name, $this->user);
        DeployXlsForm::dispatch($this->xlsform->name, $this->user);

        }

    }

    public function failed($e): void
    {
        dd($e);
    }


}
