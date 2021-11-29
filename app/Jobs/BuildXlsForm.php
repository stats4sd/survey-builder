<?php

namespace App\Jobs;

use App\Exports\XlsFormExport;
use App\Models\Xlsform;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class BuildXlsForm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Xlsform $xlsform;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Xlsform $xlsform)
    {
        $this->xlsform = $xlsform;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = $this->xlsform->id . '/' . Str::slug(Carbon::now()->toISOString()) . '/' . $this->xlsform->name . '.xlsx';

        $file = Excel::store(new XlsFormExport($this->xlsform), $path);

        $this->xlsform->update([
            'xlsfile' => $path
        ]);
    }
}
