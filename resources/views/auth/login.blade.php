<x-guest-layout>

    <div class="card bg-white">
        <div class=" card-body d-flex align-items-center">
            <h5 class="mr-3 mb-0">Sign in through the RHoMIS System:</h5>
            <a href="{{ config('app.rhomis_url') }}" class="btn btn-primary">Sign in</a>
        </div>
    </div>


    <x-auth-card>
        <x-slot name="logo">
            <div class="d-flex justify-content-center">
                <span></span>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <h5>Or sign in via email and password</h5>
            <div>
                <x-label for="email" :value="__('Email')"/>

                <x-input id="email" class="" name="email" :value="old('email')" required autofocus/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')"/>

                <x-input id="password" class=""
                         type="password"
                         name="password"
                         required/>
            </div>

            <!-- Remember Me -->
            <div class="mt-3 form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-sm">
                    {{ __('Remember me') }}
                </label>
            </div>

            <div class="d-flex justify-content-end mt-4">
                @if (Route::has('password.request'))
                    <a class="text-muted" href="{{ route('password.request') }}"
                       style="margin-right: 15px; margin-top: 15px;">
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
