<nav class="navbar navbar-expand-false navbar-dark bg-dark">
    <button class="navbar-toggler sidebar-toggler" id="sidebarToggle">
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="menu-button-icon"
             height="30" width="30" xmlns="http://www.w3.org/2000/svg">
            <path fill="none" d="M0 0h24v24H0V0z"></path>
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
        </svg>
    </button>
    <h2 style="color: white;">RHoMIS - XLSForm Builder</h2>
    <form method="POST" action={{ route('logout') }}>
        @csrf
        <button class="logout-button nav-link" style="background-color:#343a40" type="submit"><i class="la la-lock"></i> {{ trans('backpack::base.logout') }}</button>
    </form>
</nav>
