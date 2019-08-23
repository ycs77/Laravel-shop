<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->get('users', 'UserController@index');

    $router->resource('products', 'ProductController')->names('admin.products')->except('show');

    $router->post('orders/{order}', 'OrderController@ship')->name('admin.orders.ship');
    $router->post('orders/{order}/refund', 'OrderController@handleRefund')->name('admin.orders.handle_refund');
    $router->resource('orders', 'OrderController')->names('admin.orders')->only('index', 'show');

});
