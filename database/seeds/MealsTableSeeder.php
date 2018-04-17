<?php

use App\Meal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class MealsTableSeeder extends Seeder {

    public function run()
    {
        Model::unguard();

        Meal::truncate();

        Meal::create([
            'description' => 'Veg Lunch Tiffin',
            'price'       => 60,
        ]);

        Meal::create([
            'description' => 'Veg Dinner Tiffin',
            'price'       => 70,
        ]);

        Meal::create([
            'description' => 'Non-Veg Lunch Tiffin',
            'price'       => 80,
        ]);

        Meal::create([
            'description' => 'Non-Veg Dinner Tiffin',
            'price'       => 90,
        ]);

        Meal::create([
            'description' => 'Poha-Shira Breakfast - 15',
            'price'       => 15,
        ]);

        Meal::create([
            'description' => 'Poha-Shira Breakfast - 20',
            'price'       => 20,
        ]);

        Model::reguard();
    }
}
