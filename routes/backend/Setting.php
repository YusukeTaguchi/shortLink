<?php

// Setting Management
Route::group(['namespace' => 'Settings'], function () {
    Route::resource('settings', 'SettingsController', ['except' => ['show']]);

    //For DataTables
    Route::post('settings/get', 'SettingsTableController')->name('settings.get');
});
