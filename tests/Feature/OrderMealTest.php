<?php

namespace Tests\Feature;

use App\Order;
use App\Meal;
use App\User;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OrderMealTest extends TestCase {

    use DatabaseMigrations;

    /** @test */
    function a_meal_is_require_for_order()
    {
        $users = factory(User::class, 4)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id' => null,
            'user_id' => $users->pluck('id'),
        ])->assertStatus(422)->assertJsonValidationErrors('meal_id');

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function meal_should_exits_in_database_before_your_order_it()
    {
        $users = factory(User::class, 4)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id' => 32,
            'user_id' => $users->pluck('id'),
        ])->assertStatus(422)->assertJsonValidationErrors('meal_id');

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function a_user_id_must_be_an_array_input_field()
    {
        $this->signIn();

        $meal = factory(Meal::class)->create();

        $this->postJson(route('orders.store'), [
            'meal_id' => $meal->id,
            'user_id' => null,
        ])->assertStatus(422)->assertJsonValidationErrors('user_id');

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function an_empty_user_id_array_must_not_be_processed()
    {
        $this->signIn();

        $meal = factory(Meal::class)->create();

        $this->postJson(route('orders.store'), [
            'meal_id' => $meal->id,
            'user_id' => [],
        ])->assertStatus(422)->assertJsonValidationErrors("user_id");

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function a_user_ids_of_array_must_exits_in_database()
    {
        $this->signIn();

        $meal = factory(Meal::class)->create(['user_id' => auth()->id()]);

        $invalidUsers = [2, 3, 4];

        $response = $this->postJson(route('orders.store'), [
            'meal_id' => $meal->id,
            'user_id' => [2, 3, 4],
        ]);

        $response->assertStatus(422);

        foreach ($invalidUsers as $index => $id) {
            $response->assertJsonValidationErrors("user_id.{$index}");
        }

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function a_some_user_ids_of_array_must_exits_in_database()
    {
        $this->signIn();

        $meal = factory(Meal::class)->create(['user_id' => auth()->id()]);

        $invalidUsers = [2, 3, 4];

        $response = $this->postJson(route('orders.store'), [
            'meal_id' => $meal->id,
            'user_id' => [2, 3, 4, auth()->id()],
        ]);

        $response->assertStatus(422);

        foreach ($invalidUsers as $index => $id) {
            $response->assertJsonValidationErrors("user_id.{$index}");
        }

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function a_meal_requires_quantity()
    {
        $meal = factory(Meal::class)->create();

        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id' => $meal->id,
            'user_id' => $users->pluck('id'),
        ])->assertStatus(422)->assertJsonValidationErrors("quantity");

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function a_meal_quantity_must_be_number()
    {
        $meal = factory(Meal::class)->create();

        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 'fedeff',
        ])->assertStatus(422)->assertJsonValidationErrors("quantity");

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    function a_meal_quantity_must_be_number_between_1_to_5()
    {
        $meal = factory(Meal::class)->create();

        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 0,
        ])->assertStatus(422)->assertJsonValidationErrors("quantity");

        $this->assertEquals(0, Order::count());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 6,
        ])->assertStatus(422)->assertJsonValidationErrors("quantity");

        $this->assertEquals(0, Order::count());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 4,
        ])->assertStatus(201);
    }

    /** @test */
    function a_meal_quantity_can_not_be_float()
    {
        $meal = factory(Meal::class)->create();

        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 1.11,
        ])->assertStatus(422)->assertJsonValidationErrors("quantity");

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    public function a_meal_can_be_ordered()
    {
        $meal = factory(Meal::class)->create([
            'price' => 70,
        ]);

        /** @var Collection $users */
        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $response = $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 2,
        ]);

        $response->assertStatus(201);

        $users->each(function ($user) {
            /** @var User $user */
            $this->assertEquals(1, $user->orders()->count());
            $this->assertEquals(46.67, $user->owesForOrder(Order::first()));
        });

        $this->assertEquals(1, Order::count());
    }

    /** @test */
    function meal_price_is_divided_same_among_all_users()
    {
        $meal = factory(Meal::class)->create([
            'price' => 70000,
        ]);

        /** @var Collection $users */
        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 2,
        ]);

        $this->assertEquals(1, Order::count());

        /** @var Order $order */
        $order = Order::first();

        $users->each(function ($user) use ($order) {
            /** @var User $user */
            $this->assertEquals(46666.67, $user->owesForOrder($order));
        });
    }

    /** @test */
    function an_order_can_be_updated()
    {
        $meal = factory(Meal::class)->create([
            'price' => 70,
        ]);

        /** @var Collection $users */
        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 2,
        ]);

        $order = Order::first();
        $this->assertEquals(3, $order->users()->count());

        $meal = factory(Meal::class)->create([
            'price' => 60,
        ]);

        $this->putJson(route('orders.update', $order), [
            'meal_id'  => $meal->id,
            'user_id'  => [1, 2],
            'quantity' => 1,
        ])->assertStatus(202);

        $this->assertEquals(2, $order->users()->count());

        $this->assertEquals(1, $order->fresh()->quantity);
        $this->assertEquals(60, $order->fresh()->price);
    }

    /** @test */
    function an_order_can_be_deleted()
    {
        $meal = factory(Meal::class)->create([
            'price' => 70,
        ]);

        /** @var Collection $users */
        $users = factory(User::class, 3)->create();

        $this->signIn($users->first());

        $this->postJson(route('orders.store'), [
            'meal_id'  => $meal->id,
            'user_id'  => $users->pluck('id'),
            'quantity' => 2,
        ]);

        $order = Order::first();

        $this->assertEquals(3, $order->users()->count());


        $this->deleteJson(route('orders.destroy', $order))->assertStatus(204);

        $this->assertNull(Order::first());
        $this->assertEquals(0, $order->users()->newPivotStatement()->count());
    }
}
