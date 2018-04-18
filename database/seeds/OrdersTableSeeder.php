<?php

use App\Meal;
use App\Order;
use App\User;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();
        DB::table('order_user')->truncate();

        $vegMean = Meal::where('description', 'Veg Lunch Tiffin')->first();
        $nitin = User::findByEmail('nitin.kaware1@gmail.com');
        $steven = User::findByEmail('steven.joseph@gmail.com');
        $dharmesh = User::findByEmail('dharmesh.bhatt@gmail.com');

        // Order meal which is shared between 2 people.
        factory(Order::class, 25)->create([
            'meal_id'  => $vegMean->id,
            'price'    => $vegMean->price,
            'quantity' => 2,
        ])->each(function ($order) use ($nitin, $steven) {
            /** @var Order $order */
            $order->users()->attach($nitin);
            $order->users()->attach($steven);
        });

        // Order meal which is shared between 3 people
        factory(Order::class, 25)->create([
            'meal_id'  => $vegMean->id,
            'price'    => $vegMean->price,
            'quantity' => 2,
        ])->each(function ($order) use ($nitin, $steven, $dharmesh) {
            /** @var Order $order */
            $order->users()->attach($nitin);
            $order->users()->attach($steven);
            $order->users()->attach($dharmesh);
        });

        // Order morning breakfast.
        $breakfast = Meal::where('description', 'Poha-Shira Breakfast - 15')->first();
        $nitin = User::findByEmail('nitin.kaware1@gmail.com');

        factory(Order::class, 25)->create([
            'meal_id'  => $breakfast->id,
            'price'    => $breakfast->price,
            'quantity' => 1,
        ])->each(function ($order) use ($nitin) {
            /** @var Order $order */
            $order->users()->attach($nitin);
        });
    }
}
