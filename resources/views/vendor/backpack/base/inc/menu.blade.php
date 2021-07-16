<!-- =================================================== -->
<!-- ========== Top menu items (ordered left) ========== -->
<!-- =================================================== -->
<ul class="nav navbar-nav d-md-down-none">



</ul>
<!-- ========== End of top menu left items ========== -->



<!-- ========================================================= -->
<!-- ========= Top menu right items (ordered right) ========== -->
<!-- ========================================================= -->
<ul class="nav navbar-nav ml-auto @if(config('backpack.base.html_direction') == 'rtl') mr-0 @endif">

        {{-- Logout using standard Laravel / Breeze auth route (POST to logout), NOT the Backpack default  --}}
        <li class="nav-item px-4">
            <a href="{{ route('backpack.account.info') }}" class="nav-link text-white">My Account</a>
        </li>
        <li class="nav-item pr-4">
            <form method="POST" action={{ route('logout') }}>
            @csrf
            <button class="btn btn-link text-white" type="submit"><i class="la la-lock"></i> {{ trans('backpack::base.logout') }}</button>
            </form>
        </li>
</ul>
<!-- ========== End of top menu right items ========== -->
