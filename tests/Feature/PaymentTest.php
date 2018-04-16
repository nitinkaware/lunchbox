<?php

namespace Tests\Feature;

use App\Order;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase {

    use RefreshDatabase;


    /** @test */
    function an_unauthenticated_user_can_not_make_payments()
    {
        $this->postJson(route('payments.store'))->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    function an_order_id_is_required_for_the_payment()
    {
        $this->postJson(route('payments.store'))->assertStatus(422)->assertJsonValidationErrors('order_id');
    }

    /** @test */
    function an_order_id_must_be_an_array_and_not_empty()
    {
        $this->postJson(route('payments.store'), [
            'order_id' => 1,
        ])->assertStatus(422)->assertJsonValidationErrors('order_id');

        $this->postJson(route('payments.store'), [
            'order_id' => [],
        ])->assertStatus(422)->assertJsonValidationErrors('order_id');
    }

    /** @test */
    function an_order_id_array_must_present_in_database_and_that_order_should_be_for_logged_in_user()
    {
        $this->postJson(route('payments.store'), [
            'order_id' => [1],
        ])->assertStatus(422)->assertJsonValidationErrors('order_id.0');

        $order = factory(Order::class)->create();

        $this->postJson(route('payments.store'), [
            'order_id' => [$order->id],
        ])->assertStatus(422)->assertJsonValidationErrors('order_id.0');
    }

    /** @test */
    function a_user_can_make_payment_for_a_order()
    {
        $this->withoutExceptionHandling();

        /** @var User $john */
        $john = factory(User::class)->create();
        $jeffery = factory(User::class)->create();

        $this->actingAs($john);

        /** @var Order $order */
        $order = factory(Order::class)->create();
        $order->users()->attach($john);
        $order->users()->attach($jeffery);

        $this->postJson(route('payments.store'), [
            'order_id' => [$order->id],
        ])->assertStatus(Response::HTTP_CREATED);


        $this->assertEquals(1, $john->orders()->wherePivot('paid_at', '!=', null)->count());
        $this->assertEquals(1, $jeffery->orders()->wherePivot('paid_at', '=', null)->count());
    }
}
