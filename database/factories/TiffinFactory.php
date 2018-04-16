<?php

use App\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    return [
        'description' => 'This is some random description',
        'price' => 70 * 100,
    ];
});
