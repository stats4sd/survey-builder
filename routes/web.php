<?php

use App\Http\Controllers\Admin\CoreVersionCrudController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\XlsChoicesController;
use App\Http\Controllers\XlsformController;
use App\Http\Controllers\XlsSurveyRowController;
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

        Route::get('download/{path}/{disk?}', [FileController::class, 'download'])->where('path', '.*')->name('file.download');

        Route::get('module/{module}', [ModuleController::class, 'show'])->name('module.localshow');
        Route::get('latest-core', [CoreVersionCrudController::class, 'getLatest'])->name('core.latest');

        Route::get('xls-choices', [XlsChoicesController::class, 'index'])->name('xlschoices.index');


        Route::resource('xlsform', XlsformController::class);

        // redirect the core "edi" to the Build component
        Route::get('xlsform/{xlsform}/edit', function($xlsform) {
            return redirect('xlsform/'.$xlsform.'/edit-two');
        });

        Route::get('xlsform/{xlsform}/edit-one', [XlsformController::class,  'editOne']);
        Route::get('xlsform/{xlsform}/edit-two', [XlsformController::class,  'editTwo']);
        Route::get('xlsform/{xlsform}/edit-three', [XlsformController::class,  'editThree']);
        Route::get('xlsform/{xlsform}/edit-four', [XlsformController::class,  'editFour']);

        Route::put('xlsform/{xlsform}/customise', [XlsformController::class, 'updateCustomisations']);


        // redirect xlsform crud users to front-end
        Route::get('admin/xlsform/create', function(){
            return redirect('xlsform/create');
        });

        Route::get('module-version/{moduleversion}/get-details', [ModuleController::class, 'getDetails']);
    }
);


Route::get('testbroadcast', function() {
    $xlsform = \App\Models\Xlsform::first();
    $user = \App\Models\User::first();
    \App\Events\BuildXlsFormComplete::dispatch($xlsform->name, $user);
});
