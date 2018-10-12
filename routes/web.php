<?php

Auth::routes(['verify' => true]);

Route::get('alert/{route}/{message}', 'AlertController@handle');

Route::redirect('/', 'products')->name('root');

Route::resource('products', 'ProductController')->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::resource('user_addresses', 'UserAddressController')->except('show');

        // 商品收藏
        Route::post('products/{product}/favorite', 'ProductController@favor')->name('products.favor');
        Route::delete('products/{product}/favorite', 'ProductController@disfavor')->name('products.disfavor');
    });
});
