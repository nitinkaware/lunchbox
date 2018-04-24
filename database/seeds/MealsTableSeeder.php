<?php

use App\Meal;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class MealsTableSeeder extends Seeder {

    public function run()
    {
        Model::unguard();

        Meal::truncate();

        $ajayGuptaId = User::findByEmail('ajay.gupta@gmail.com')->id;
        $bhimId = User::findByEmail('bhim.prajapati@gmail.com')->id;

        Meal::create([
            'user_id'     => $ajayGuptaId,
            'description' => 'Veg Lunch Tiffin',
            'price'       => 60,
        ]);

        Meal::create([
            'user_id'     => $ajayGuptaId,
            'description' => 'Veg Dinner Tiffin',
            'price'       => 70,
        ]);

        Meal::create([
            'user_id'     => $ajayGuptaId,
            'description' => 'Non-Veg Lunch Tiffin',
            'price'       => 80,
        ]);

        Meal::create([
            'user_id'     => $ajayGuptaId,
            'description' => 'Non-Veg Dinner Tiffin',
            'price'       => 90,
        ]);

        Meal::create([
            'user_id'     => $bhimId,
            'description' => 'Poha-Shira Breakfast - 15',
            'price'       => 15,
        ]);

        Meal::create([
            'user_id'     => $bhimId,
            'description' => 'Poha-Shira Breakfast - 20',
            'price'       => 20,
        ]);

        Model::reguard();
    }
}
