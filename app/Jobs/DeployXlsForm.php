<?php

namespace App\Jobs;

use App\Events\BuildXlsFormFailed;
use App\Events\DeployXlsFormComplete;
use App\Events\DeployXlsFormFailed;
use App\Models\User;
use App\Models\Xlsform;
use App\Services\RhomisApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class DeployXlsForm implements ShouldQueue
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
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function handle()
    {
        $file = file_get_contents(Storage::path($this->xlsform->xlsfile));

//        // get existing user project + form metadata
//        $metaData = Http::withHeaders([
//            'Authorization' => $this->user->jwt_token,
//        ])
//            ->post(config('auth.auth_url') . '/api/meta-data')
//            ->throw(function ($response) {
//                RhomisApiService::handleApiFailure($response, $this->user);
//            })
//            ->json();
//
//        $projects = $metaData['user']['projects'] ?? [];
//        $forms = $metaData['user']['forms'] ?? [];

//        // if the project doesn't exist on RHOMIS, create it...
//        if (collect($projects)->doesntContain($this->xlsform->project_name)) {
//
//            $projectResponse = Http::withHeaders([
//                'Authorization' => $this->user->jwt_token,
//            ])
//                ->post(
//                    config('auth.auth_url') . '/api/projects/create',
//                    [
//                        'name' => $this->xlsform->project_name,
//                        'description' => $this->xlsform->project_name . ' description'
//                    ]
//                )
//                ->throw(function ($response) {
//                    RhomisApiService::handleApiFailure($response, $this->user);
//                });
//        }
//
//        // if the form doesn't exist on RHOMIS, create it...
//        if (collect($forms)->contains($this->xlsform->name) || collect($forms)->contains(Str::replace(' ', '-', $this->xlsform->name))) {
//            $postUrl = 'new-draft';
//                } else {
//            $postUrl = 'new';
//        }


        // POST form details to endpoint for saving into the RHOMIS database
        $xlsformDataResponse = Http::withHeaders([
            'Authorization' => $this->user->jwt_token,
        ])
            ->post(config('auth.auth_url') . '/api/forms/save', [
                'name' => $this->xlsform->name,
                'moduleVersions' => $this->xlsform->moduleVersions->select('id', 'version_name', 'module_id', 'module_name'),
                'location_file' => $this->xlsform->location_file,
                'choice_lists' => $this->xlsform
            ])
        ->throw(function($response) {
            RhomisApiService::handleApiFailure($response, $this->user);
        });



//        // POST form to endpoint for sending to ODK Central
//        $xlsformResponse = Http::withHeaders([
//            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//            'Authorization' => $this->user->jwt_token,
//        ])
//            ->withBody($file, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
//            ->post(
//                config('auth.auth_url') .
//                "/api/forms/".$postUrl."?form_name=" . Str::slug($this->xlsform->name) .
//                "&project_name=" . urlencode($this->xlsform->project->name) .
//                "&publish=false" .
//                "&form_version=" . ($this->xlsform->form_version + 1)
//            )
//            ->throw(function ($response) {
//                RhomisApiService::handleApiFailure($response, $this->user);
//            });
//
//
//        if ($xlsformResponse->successful()) {
//            $this->xlsform->update([
//                'draft' => 1,
//                'form_version' => $this->xlsform->form_version+1
//            ]);
//        }

        DeployXlsFormComplete::dispatch($this->xlsform->name, $this->user);

    }

    public function failed(Throwable $e): void
    {
        DeployXlsFormFailed::dispatch($e->getMessage(), $e->getCode(), $this->xlsform->name, $this->user);
    }
}
