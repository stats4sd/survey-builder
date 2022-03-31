<?php

namespace App\Services;

use App\Events\RhomisAPiCallDidFail;
use App\Models\User;
use Illuminate\Http\Client\Response;

class RhomisApiService
{

    public static function handleApiFailure(Response $response, User $user = null): void
    {
        RhomisAPiCallDidFail::dispatch($response, $user);
    }

}
