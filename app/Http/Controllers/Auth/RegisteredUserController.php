<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $newUser = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $response = Http::post(config('auth.jwt_url').'/api/user/register', $newUser);

        // if creation fails
        if (! $response->ok()) {
            if ($response->body() === "Email already exists") {
                throw ValidationException::withMessages(
                    ['email' => 'This email already exists. Please try logging in'],
                );
            }
        }

        $login = [
            'email' => $newUser['email'],
            'password' => $newUser['password'],
        ];

        $loginResponse = Http::post(config('auth.jwt_url').'/api/user/login', $login);

        //If cannot authenticate
        if (! $loginResponse->ok()) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }


        //If user is not in system, store:
        $user = User::updateOrCreate(
            ['email' => $login['email']],
            ['jwt_token' => $loginResponse->body()]
        );

        Auth::login($user);

        return redirect('admin');
    }
}
