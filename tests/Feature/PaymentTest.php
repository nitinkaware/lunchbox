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
        $this->actingAs(factory(User::class)->create());
        $this->postJson(route('payments.store'))->assertStatus(422)->assertJsonValidationErrors('order_id');
    }

    /** @test */
    function an_order_id_must_be_an_array_and_not_empty()
    {
        $this->actingAs(factory(User::class)->create());
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
        $this->actingAs(factory(User::class)->create());

        $this->postJson(route('payments.store'), [
            'order_id' => [1],
        ])->assertStatus(422)->assertJsonValidationErrors('order_id.0');

        $order = factory(Order::class)->create();

        $this->postJson(route('payments.store'), [
            'order_id' => [$order->id],
        ])->assertStatus(422)->assertJsonValidationErrors('order_id.0');
    }
}
