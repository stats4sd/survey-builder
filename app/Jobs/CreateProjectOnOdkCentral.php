<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateProjectOnOdkCentral implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->project->odk_central_id) {
            \Log::info('project already deployed');
            return;
        }

        AuthenticateWithOdkCentral::dispatchSync();

        $response = Http::withToken(Session::get('odk.token'))->post(config('services.odk_central.url').'/v1/projects', [
            'name' => $this->project->name,
        ])->json();

        \Log::info('response');
        \Log::info($response);

        if (isset($response['id'])) {
            $this->project->odk_central_id = $response['id'];
            $this->project->save();
        }
    }
}
