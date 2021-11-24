<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/**
 * Post request to login can ignore CSRF protections as these will come from main RHoMIS application
 * TODO: try and restrict to specific referrer urls/domains...
 *
 */
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware([
        'cookies',
        'cookies.queued',
        'startsession',
        'shareerrors',
        'bindings',
    ]);

/**
 * Other auth routes should simply redirect back to the main app...
 */
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest', 'web'])
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
