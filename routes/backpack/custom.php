<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

use App\Http\Controllers\Admin\ModuleCrudController;

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes



    Route::crud('theme', 'ThemeCrudController');
    Route::crud('module', 'ModuleCrudController');

    Route::crud('indicator', 'IndicatorCrudController');
    Route::crud('sdg', 'SdgCrudController');
    Route::crud('language', 'LanguageCrudController');

    Route::crud('project', 'ProjectCrudController');

    Route::post('project/{project}/deploy', 'ProjectCrudController@deploy');
    //Route::post('xlsform/{xlsform}/deploy', 'XlsformCrudController@deploy');
    //Route::get('xlsform/{xlsform}/build', 'XlsformCrudController@build');

    Route::post('user/{user}/newtoken', 'TokenController@store')->name('account.newtoken');
    Route::crud('moduleversion', 'ModuleVersionCrudController');
    Route::get('moduleversion', function() {
        return redirect(backpack_url('module'));
    });

    Route::get('moduleversion/{moduleversion}/publish', 'ModuleVersionCrudController@publish')->name('moduleversion.publish');
    Route::get('moduleversion/{moduleversion}/unpublish', 'ModuleVersionCrudController@unpublish')->name('moduleversion.unpublish');
    Route::crud('author', 'AuthorCrudController');

    Route::crud('choice-list', 'ChoiceListCrudController');
    Route::crud('choices-row', 'ChoicesRowCrudController');

    Route::post('module/test-core', [ModuleCrudController::class, 'testCore']);
}); // this should be the absolute last line of this file
