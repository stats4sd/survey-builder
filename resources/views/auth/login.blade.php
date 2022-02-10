<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/" class="d-flex justify-content-center mb-4">
                <x-application-logo width=64 height=64 />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <h5>Sign in via Email and Password</h5>
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="" name="email" :value="old('email')" required autofocus />
            </div>

             <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class=""
                                type="password"
                                name="password"
                                required/>
            </div>

            <h5 class="mt-4">To Manually test Token authentication:</h5>
{{--            <!-- Email Address -->--}}
{{--            <div>--}}
{{--                <x-label for="token" :value="__('Token')" />--}}

{{--                <x-input id="token" class="" name="token" :value="old('token')" required autofocus />--}}
{{--            </div>--}}

{{--            <!-- Password -->--}}
{{--            <div class="mt-4">--}}
{{--                <x-label for="redirect_url" :value="__('Redirect Url')" />--}}

{{--                <x-input id="redirect_url" class=""--}}
{{--                                type="redirect_url"--}}
{{--                                name="redirect_url"--}}
{{--                                required/>--}}
{{--            </div>--}}

            <!-- Remember Me -->
            <div class="mt-3 form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-sm">
                    {{ __('Remember me') }}
                </label>
            </div>

            <div class="d-flex justify-content-end mt-4">
                @if (Route::has('password.request'))
                    <a class="text-muted" href="{{ route('password.request') }}" style="margin-right: 15px; margin-top: 15px;">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
