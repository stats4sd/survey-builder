<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

        // After registering, authenticate via the standard LoginRequest()

        $login = new LoginRequest([
            'email' => $newUser['email'],
            'password' => $newUser['password'],
        ]);

        $login->authenticate();

        //$login->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);

        // $loginResponse = Http::post(config('auth.jwt_url').'/api/user/login', $login);

        // //If cannot authenticate
        // if (! $loginResponse->ok()) {
        //     throw ValidationException::withMessages([
        //         'email' => __('auth.failed'),
        //     ]);
        // }

        // $decoded = JWT::decode($loginResponse->body(), config('auth.jwt_secret'), ['alg' => 'HS256']);


        // //If user is not in system, store:
        // $user = User::updateOrCreate(
        //     ['id' => $decoded->_id],
        //     [
        //         'email' => $credentials['email'],
        //         'username' => $decoded->username,
        //     ],
        // );
        // //If user is not in system, store:
        // $user = User::updateOrCreate(
        //     ['email' => $login['email']],
        //     ['jwt_token' => $loginResponse->body()]
        // );

        // Auth::login($user);

        // return redirect('admin');
    }
}
