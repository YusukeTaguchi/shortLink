<?php

// Domain Management
Route::group(['namespace' => 'Domains'], function () {
    Route::resource('domains', 'DomainsController', ['except' => ['show']]);

    //For DataTables
    Route::post('domains/get', 'DomainsTableController')->name('domains.get');
});
