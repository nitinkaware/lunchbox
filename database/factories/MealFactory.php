<?php

use App\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    return [
        'user_id'     => function () {
            return factory(\App\User::class)->create();
        },
        'description' => 'This is some random description',
        'price'       => 70 * 100,
    ];
});
