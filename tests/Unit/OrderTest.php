<?php

namespace Tests\Unit;

use App\Order;
use App\Meal;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OrderTest extends TestCase {

    use DatabaseMigrations;

    /** @test */
    public function an_order_belongs_to_many_users()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create();

        /** @var User $orders */
        factory(User::class)->create()->orders()->attach($order->id);
        factory(User::class)->create()->orders()->attach($order->id);
        factory(User::class)->create()->orders()->attach($order->id);

        $this->assertEquals(3, $order->users()->count());
    }

    /** @test */
    function an_order_belongs_to_meal()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create();
        $this->assertInstanceOf(Meal::class, $order->meal()->first());
        $this->assertEquals(1, $order->meal()->count());
    }

    /** @test */
    function an_owes_attributes_must_return_price()
    {
        /** @var Order $order */
        $order = factory(Order::class)->create(['price' => 60]);
        /** @var User $orders */
        factory(User::class)->create()->orders()->attach($order->id);
        factory(User::class)->create()->orders()->attach($order->id);
        factory(User::class)->create()->orders()->attach($order->id);
        
        $this->assertNotNull($order->owes());
    }
}
