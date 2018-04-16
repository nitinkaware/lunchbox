<?php

Auth::routes();
Route::resource('meals', 'MealsController')->only(['store', 'update']);
Route::resource('orders', 'OrdersController')->only(['store', 'update', 'destroy']);
Route::resource('payments', 'PaymentsController')->only(['store']);
Route::get('/home', 'HomeController@index')->name('home');
