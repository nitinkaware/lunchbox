<?php

namespace Tests\Unit;

use App\Order;
use App\Meal;
use App\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MealTest extends TestCase {

    use DatabaseMigrations;

    /** @test */
    public function a_meal_has_many_order()
    {
        /** @var Order $order */
        $meal = factory(Meal::class)->create();

        factory(Order::class)->create(['meal_id' => $meal->id]);

        $this->assertEquals(1, $meal->orders()->count());
        $this->assertInstanceOf(HasMany::class, $meal->orders());
    }

    /** @test */
    function get_the_list_of_the_meal_which_are_ordered_by_user()
    {
        // Create a meal
        $meal1 = factory(Meal::class)->create();
        $meal2 = factory(Meal::class)->create();
        factory(Meal::class)->create();

        // Place the two orders for two meals
        $order1 = factory(Order::class)->create(['meal_id' => $meal1->id]);
        $order2 = factory(Order::class)->create(['meal_id' => $meal2->id]);

        $john = factory(User::class)->create();

        $john->orders()->attach($order1);
        $john->orders()->attach($order2);

        $this->assertCount(2, Meal::orderedForUser($john));
    }
}
