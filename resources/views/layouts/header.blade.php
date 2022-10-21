<nav class="navbar navbar-expand-false navbar-dark bg-dark mb-4">
    <a class="menu-button nav-link" href="{{ config('auth.rhomis_url') . '/#/home'  }}">
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 1024 1024" class="menu-button-icon"
             height="30" width="30" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M946.5 505L560.1 118.8l-25.9-25.9a31.5 31.5 0 0 0-44.4 0L77.5 505a63.9 63.9 0 0 0-18.8 46c.4 35.2 29.7 63.3 64.9 63.3h42.5V940h691.8V614.3h43.4c17.1 0 33.2-6.7 45.3-18.8a63.6 63.6 0 0 0 18.7-45.3c0-17-6.7-33.1-18.8-45.2zM568 868H456V664h112v204zm217.9-325.7V868H632V640c0-22.1-17.9-40-40-40H432c-22.1 0-40 17.9-40 40v228H238.1V542.3h-96l370-369.7 23.1 23.1L882 542.3h-96.1z"></path>
        </svg>
    </a>
    <h2 style="color: white;">RHoMIS - XLSForm Builder</h2>
    <form method="POST" action={{ route('logout') }}>
        @csrf
        <button class="logout-button nav-link" style="background-color:#343a40" type="submit"><i
                class="la la-lock"></i> {{ trans('backpack::base.logout') }}</button>
    </form>
</nav>

