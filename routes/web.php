<?php

Route::redirect('/', 'products')->name('root');

Route::resource('products', 'ProductController')->only(['index', 'show']);

Auth::routes(['verify' => true]);

Route::get('alert/{route}/{message}', 'AlertController@handle');

Route::middleware('verified')->group(function () {
    Route::resource('user_addresses', 'UserAddressController')->except('show');
});
