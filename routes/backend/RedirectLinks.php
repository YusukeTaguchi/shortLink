<?php

// RedirectLink Management
Route::group(['namespace' => 'RedirectLinks'], function () {
    Route::resource('redirect-links', 'RedirectLinksController', ['except' => ['show']]);

    //For DataTables
    Route::post('redirect-links/get', 'RedirectLinksTableController')->name('redirect-links.get');
});
