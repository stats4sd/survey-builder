<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthenticateWithOdkCentral implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // check if current session token is valid
        if ((new Carbon(Session::get('odk.expires')))->isFuture()) {
            return;
        }

        $loginResponse = Http::post(config('services.odk_central.url') .'/v1/sessions', [
            'email' => config('services.odk_central.admin.email'),
            'password' => config('services.odk_central.admin.password'),
        ])->json();



        \Log::info('new login expires at ' . $loginResponse['expiresAt']);


        Session::put('odk.expires', $loginResponse['expiresAt']);
        Session::put('odk.token', $loginResponse['token']);

        return;
    }
}
