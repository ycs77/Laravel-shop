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

    $router->get('products', 'ProductController@index');
    $router->get('products/create', 'ProductController@create');
    $router->post('products', 'ProductController@store');
    $router->get('products/{id}/edit', 'ProductController@edit');
    $router->put('products/{id}', 'ProductController@update');

    $router->get('orders', 'OrderController@index')->name('admin.orders.index');
    $router->get('orders/{order}', 'OrderController@show')->name('admin.orders.show');
    $router->post('orders/{order}', 'OrderController@ship')->name('admin.orders.ship');
    $router->post('orders/{order}/refund', 'OrderController@handleRefund')->name('admin.orders.handle_refund');

});
