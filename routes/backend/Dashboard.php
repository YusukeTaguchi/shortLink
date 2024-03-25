<?php

/**
 * All route names are prefixed with 'admin.'.
 */
Route::get('dashboard', 'DashboardController@index')->name('dashboard');
Route::get('dashboard/today', 'DashboardController@today')->name('dashboard.today');
Route::get('dashboard/monthly', 'DashboardController@monthly')->name('dashboard.monthly');
Route::post('get-permission', 'DashboardController@getPermissionByRole')->name('get.permission');

// Edit Profile
/* Route::get('profile/edit', 'DashboardController@editProfile')->name('profile.edit');
Route::patch('profile/update', 'DashboardController@updateProfile')
    ->name('profile.update'); */
