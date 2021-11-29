<?php

namespace App\Jobs;

use App\Models\Xlsform;
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

class DeployXlsForm implements ShouldQueue
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
        $file = file_get_contents(Storage::path($this->xlsform->xlsfile));

        if (!$this->xlsform->project->deployed) {
            $projectResponse = Http::withHeaders([
                'Authorization' => Auth::user()->jwt_token,
            ])
                ->post(
                    config('auth.auth_url') . '/api/projects/create',
                    [
                        'name' => $this->xlsform->project_name,
                        'description' => $this->xlsform->project_name . ' description'
                    ]
                )
                ->throw();

            Log::info($projectResponse);

            // TODO: update this if API is updated to properly return errors
            if ($projectResponse->body() === "Project Saved") {
                $this->xlsform->project->update(['deployed' => 1]);
            }
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Authorization' => Auth::user()->jwt_token,
            'X-XlsForm-FormId-Fallback' => Str::slug($this->xlsform->name),
        ])
            ->withBody($file, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->post(
                config('auth.auth_url') .
                "/api/forms/new?form_name=" . Str::slug($this->xlsform->name) .
                "&project_name=" . urlencode($this->xlsform->project->name) .
                "&publish=false" .
                "&form_version=1.0"
            )
            ->throw();

        Log::info($response);

    }
}
