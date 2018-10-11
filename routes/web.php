<?php

Auth::routes(['verify' => true]);

Route::get('alert/{route}/{message}', 'AlertController@handle');

Route::redirect('/', 'products')->name('root');

Route::resource('products', 'ProductController')->only(['index', 'show']);

Route::middleware('verified')->group(function () {
    Route::resource('user_addresses', 'UserAddressController')->except('show');
});
