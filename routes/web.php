<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::resource('meals', 'MealsController')->only(['store', 'update']);
    Route::resource('orders', 'OrdersController')->only(['index', 'create','store', 'update', 'destroy']);
    Route::resource('payments', 'PaymentsController')->only(['store']);
    Route::get('/home', 'HomeController@index')->name('home');
});
