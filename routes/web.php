<?php

use App\Http\Controllers\Admin\CoreVersionCrudController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\XlsChoicesController;
use App\Http\Controllers\XlsformController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

// overwrite Backpack auth route defaults:
Route::get('admin/login', function () {
    return redirect('login');
});


Route::group(
    [
        'middleware' => 'auth',
    ],
    function () {
        Route::get('module/{module}', [ModuleController::class, 'show'])->name('module.localshow');
        Route::get('latest-core', [CoreVersionCrudController::class, 'getLatest'])->name('core.latest');

        Route::get('xls-choices', [XlsChoicesController::class, 'index'])->name('xlschoices.index');


        Route::resource('xlsform', XlsformController::class);
    }
);
