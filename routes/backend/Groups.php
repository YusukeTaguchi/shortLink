<?php

// Group Management
Route::group(['namespace' => 'Groups'], function () {
    Route::resource('groups', 'GroupsController', ['except' => ['show']]);

    //For DataTables
    Route::post('groups/get', 'GroupsTableController')->name('groups.get');
});
