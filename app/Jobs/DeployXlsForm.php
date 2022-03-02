<?php

namespace App\Jobs;

use App\Events\BuildXlsFormFailed;
use App\Events\DeployXlsFormComplete;
use App\Events\DeployXlsFormFailed;
use App\Models\User;
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
     */
    public function handle()
    {
        $file = file_get_contents(Storage::path($this->xlsform->xlsfile));

        // check if project has been deployed or not
        $userCheck = Http::withHeaders([
            'Authorization' => $this->user->jwt_token,
        ])
            ->get(config('auth.auth_url') . '/api/meta-data')
            ->json();

        if($userCheck['user'] && $userCheck['user']['projects'] && collect($userCheck['user']['projects'])->doesntContain($this->xlsform->project_name)) {
            $projectResponse = Http::withHeaders([
                'Authorization' => $this->user->jwt_token,
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
            } else {
                $this->fail();
            }
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Authorization' => $this->user->jwt_token,
            // 'X-XlsForm-FormId-Fallback' => Str::slug($this->xlsform->name),
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
        DeployXlsFormComplete::dispatch($this->xlsform->name, $this->user);

    }

    public function failed(Throwable $exception): void
    {
        DeployXlsFormFailed::dispatch($this->xlsform->name, $this->user);
    }
}
