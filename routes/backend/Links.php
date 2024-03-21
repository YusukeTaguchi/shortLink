<?php

// Links Management
Route::group(['namespace' => 'Links'], function () {
    Route::resource('links', 'LinksController', ['except' => ['show']]);

    //For DataTables
    Route::post('links/get', 'LinksTableController')
        ->name('links.get');
});
