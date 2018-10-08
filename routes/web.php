<?php

Route::get('/', 'PagesController@root')->name('root');

Auth::routes(['verify' => true]);

Route::get('alert/{route}/{message}', 'AlertController@handle');

Route::middleware('verified')->group(function () {
    Route::resource('user_addresses', 'UserAddressController')->only(['index', 'create', 'store']);
});
