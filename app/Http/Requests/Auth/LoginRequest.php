<?php

namespace App\Http\Requests\Auth;

use App\Models\Project;
use App\Models\User;
use App\Models\Xlsform;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'nullable|string',
            'redirect_url' => 'nullable|string',
        ];
    }

    /**
     * Attempt to authenticate using a JWT shared from an external application
     * @throws ValidationException
     */
    public function authenticate()
    {
        // set leeway to account for time diff?
        JWT::$leeway = 10;

        // check method of authentication
        if ($this->input('token')) {
            $user = $this->authenticateViaJWTToken();

            RateLimiter::clear($this->throttleKey());
            Auth::login($user);

        } else {
            // quick hack, as hopefully this will only be used for testing and occasional admin
            $user = User::where('email', $this->input('email'))->first();

            if (!Auth::attempt($this->only('email', 'password'), $this->filled('remember'))) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'username' => __('auth.failed'),
                ]);
            }

            RateLimiter::clear($this->throttleKey());
            $user = Auth::user();
        }


    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')) . '|' . $this->ip();
    }

    public function authenticateViaJWTToken()
    {
        // decode token to get User info
        // $token = JWTAuth::getToken();
        $token = $this->input('token');
        $decoded = JWT::decode($token, config('auth.auth_secret'), ['alg' => 'HS256']);

        // get user details:
        $userMeta = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])
            ->get(config('auth.auth_url') . '/api/meta-data')
            ->throw()
            ->json();


        // check for existing users with that email;
        // for now, simply delete them! (May do something more clever later)
        $user = User::where('email', $decoded->email)->delete();

        //If user is not in system, store:
        $user = User::updateOrCreate(
            ['id' => $decoded->_id],
            [
                'email' => $decoded->email,
                'jwt_token' => $token,
            ],
        );

        // If a project does not exist in the database, create it (this *should* not be needed!)
        foreach ($userMeta['projects'] as $project) {
            if (!Project::find($project['name'])) {
                Project::create([
                    'name' => $project['name'],
                    'description' => $project['description'],
                ]);
            }
        }

        // ensure user has access to all their projects;
        $user->projects()->syncWithoutDetaching($userMeta['user']['projects']);

        // If a form does not exist in the database, create...
        foreach ($userMeta['forms'] as $form) {
            if (!Xlsform::find($form['name'])) {
                Xlsform::create([
                    'name' => $form['name'],
                    'project_name' => $form['project'],
                    'draft' => $form['draft'] ?? true,
                    'complete' => $form['complete'] ?? false,
                    'centralId' => $form['centralId'] ?? null,
                    'user_id' => $user->id,
                ]);
            }
        }

        $user->xlsforms()->syncWithoutDetaching($userMeta['user']['forms']);

        // check + update user permissions:
        if ($userMeta['user']['roles']['administrator']) {
            $user->assignRole('admin');
        } else {
            $user->removeRole('admin');
        }

        return $user;
    }
}
