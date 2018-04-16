<?php

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'meal_id' => function () {
            return factory(\App\Meal::class)->create()->id;
        },
        'quantity'  => array_random([1, 2, 3, 4, 5]),
        'price'     => 70000,
    ];
});
