<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::resource('dashboard', 'DashboardController')->only(['index']);
    Route::resource('meals', 'MealsController')->only(['store', 'update']);
    Route::resource('orders', 'OrdersController')->only(['index', 'create', 'store', 'update', 'destroy']);
    Route::resource('payments', 'PaymentsController')->only(['index', 'store']);
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('meals/summery', 'MealsSummaryController')->names([
        'index' => 'meals.summery.index',
    ])->only(['index']);
});
