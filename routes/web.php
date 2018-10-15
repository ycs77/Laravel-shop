<?php

Auth::routes(['verify' => true]);

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::resource('user_addresses', 'UserAddressController')->except('show');

        // 商品收藏
        Route::post('products/{product}/favorite', 'ProductController@favor')->name('products.favor');
        Route::delete('products/{product}/favorite', 'ProductController@disfavor')->name('products.disfavor');
        Route::get('products/favorites', 'ProductController@favorites')->name('products.favorites');

        // 購物車
        Route::get('cart', 'CartController@index')->name('cart.index');
        Route::post('cart', 'CartController@add')->name('cart.add');
        Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');

        // 訂單
        Route::resource('orders', 'OrderController')->only(['index', 'store', 'show']);

        // 付款
        Route::get('payment/{order}/website', 'PaymentController@payByWebsite')->name('payment.website');
    });
});

Route::redirect('/', 'products')->name('root');
Route::resource('products', 'ProductController')->only(['index', 'show']);

Route::get('alert/{route}/{message}', 'AlertController@handle');

Route::post('payment/website/notify', 'PaymentController@websiteNotify')->name('payment.website.notify');
