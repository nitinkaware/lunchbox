<?php

namespace Tests\Unit;

use App\Order;
use App\Meal;
use App\User;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase {

    use DatabaseMigrations;

    /** @test */
    public function user_belongs_to_many_orders()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Collection $orders */
        factory(Order::class)->create()->users()->attach($user->id);
        factory(Order::class)->create()->users()->attach($user->id);
        factory(Order::class)->create()->users()->attach($user->id);

        $this->assertEquals(3, $user->orders()->count());
    }

    /** @test */
    function a_user_know_how_much_amount_it_owes_for_particular_order()
    {
        /** @var User $john */
        /** @var User $jeffery */
        list($john, $jeffery, $order) = $this->placeOrder();

        $this->assertEquals(60, $john->owesForOrder($order));
        $this->assertEquals(60, $jeffery->owesForOrder($order));
    }

    /** @test */
    function a_user_know_how_much_amount_it_owes_for_particular_meal()
    {
        /** @var User $john */
        /** @var User $jeffery */
        list($john, $jeffery, $order, $meal) = $this->placeOrder(3);

        $this->assertEquals(180, $john->owesForMeal($meal));
        $this->assertEquals(180, $jeffery->owesForMeal($meal));
    }

    /** @test */
    function a_user_know_how_much_amount_it_owes_in_total()
    {
        list($john, $jeffery, $order, $meal) = $this->placeOrder(3);

        $this->assertEquals(180, $john->oweTotal());
    }

    /**
     * @param int $numberOfOrder
     *
     * @param int $price
     *
     * @return array
     */
    private function placeOrder(int $numberOfOrder = 1, $price = 60): array
    {
        /** @var User $john */
        $john = factory(User::class)->create();

        /** @var User $jeffery */
        $jeffery = factory(User::class)->create();

        $meal = factory(Meal::class)->create(['price' => $price]);

        for ($i = 0; $i< $numberOfOrder; $i++) {
            /** @var Collection $orders */
            $order = factory(Order::class)->create([
                'quantity'  => 2,
                'price'     => $price,
                'meal_id' => $meal->id,
            ]);

            $order->users()->attach($john);
            $order->users()->attach($jeffery);
        }

        return [$john, $jeffery, $order, $meal];
    }
}
