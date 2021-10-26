<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     *
     * @param Project $project
     * @return void
     */
    public function created(Project $project)
    {
        // post new project to RHoMIS Auth / System as current user:
        Http::withHeaders([
            "Authorization" => Auth::user()->jwt_token,
        ])->post(config('auth.auth_url') . '/api/projects/create', [
            'name' => $project->name,
        ])->throw();

    }

    /**
     * Handle the Project "updated" event.
     *
     * @param Project $project
     * @return void
     */
    public function updated(Project $project)
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     *
     * @param Project $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     *
     * @param Project $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     *
     * @param Project $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
