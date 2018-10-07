<?php

Route::get('/', 'PagesController@root')->name('root');

Auth::routes(['verify' => true]);

Route::get('alert/{route}/{message}', 'AlertController@handle');
