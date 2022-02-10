<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Redirect login requests to the main Rhomis App...
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function create()
    {
        // for local testing, allow manual login with token
        //if(app()->environment('local')) {
            return view('auth.login');
        //} else {
        //    return redirect(config('auth.rhomis_url'));
        //}
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $redirect = $request->input('redirect_url') ?? 'admin';

        $request->session()->regenerate();

        return redirect($redirect);
    }

    /**
     * Destroy an authenticated session, then redirect to RHoMIS app to destroy session there.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('auth.rhomis_url');
    }
}
