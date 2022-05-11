<?php

namespace App\Jobs;

use App\Events\BuildXlsFormFailed;
use App\Events\DeployXlsFormComplete;
use App\Events\DeployXlsFormFailed;
use App\Events\FinaliseXlsFormComplete;
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

class FinaliseXlsForm implements ShouldQueue
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
        // at this point, the form and the project should already exist on Rhomis and ODK Central. This is just to make the existing form 'live' / finalised.

        $xlsformResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $this->user->jwt_token,
        ])
            ->post(
                config('auth.auth_url') .
                "/api/forms/publish?form_name=" . Str::slug($this->xlsform->name) .
                "&project_name=" . urlencode($this->xlsform->project->name)
            )
            ->throw(function ($response) {
                RhomisApiService::handleApiFailure($response, $this->user);
            });


        if ($xlsformResponse->successful()) {
            $this->xlsform->update([
                'draft' => 0,
                'complete' => 1,
            ]);
        }

        FinaliseXlsFormComplete::dispatch($this->xlsform->name, $this->user);

    }

    public function failed(Throwable $e): void
    {
        DeployXlsFormFailed::dispatch($e->getMessage(), $e->getCode(), $this->xlsform->name, $this->user);
    }
}
