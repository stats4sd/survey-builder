<?php

namespace App\Jobs;

use App\Models\Xlsform;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateDraftFormOnOdkCentral implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $form;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Xlsform $form)
    {
        $this->form = $form;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->form->status === 'live') {
            return;
        }

        AuthenticateWithOdkCentral::dispatchSync();

        $file = file_get_contents(Storage::path($this->form->xlsfile));

        try {

            $response = Http::withToken(Session::get('odk.token'))
            ->withHeaders([
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'X-XlsForm-FormId-Fallback' => Str::slug($this->form->title),
                ])
                ->withBody($file, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->post(config('services.odk_central.url').'/v1/projects/'.$this->form->project->odk_central_id.'/forms?ignoreWarnings=true')
                ->throw()
                ->json();
        } catch(\Illuminate\Http\Client\RequestException $e) {
            if($message = $e->getMessage()) {

            }
        }


        return;
    }
}
