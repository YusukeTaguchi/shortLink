<?php

// Links Management
Route::group(['namespace' => 'Links'], function () {
    Route::resource('links', 'LinksController', ['except' => ['show']]);

    //For DataTables
    Route::post('links/get', 'LinksTableController')
        ->name('links.get');

    //For DataTables
    Route::post('links/top/today', 'TopTodayLinksTableController')
     ->name('links.top');

    //For DataTables
    Route::post('links/top/monthly', 'TopMonthlyLinksTableController')
    ->name('links.monthly');

    // Sync Links
    Route::get('links/{id}/sync', 'LinksController@sync')->name('links.sync');
    Route::get('links/{id}/fake/{fake}', 'LinksController@fake')->name('links.fake');
});
