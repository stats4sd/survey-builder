<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @yield('after_styles')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased app aside-menu-fixed">
        <div class="min-h-screen">
            <header class="">
                @include('layouts.header')
            </header>

            <div class="app-body">
                <!-- Sidebar -->
                @include('layouts.sidebar-front-end')

                <!-- Page Content -->
                <main class="main mx-auto px-5 pt-5" style="max-width:1200px; background-color: #1b8697 !important">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
    @yield('before_scripts')

    @yield('after_scripts')

</html>
