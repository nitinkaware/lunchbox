<?php

namespace Tests\Feature;

use App\Meal;
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
    function an_user_id_is_required_for_making_payment()
    {
        $this->signIn();

        $this->postJson(route('payments.store'))->assertStatus(422)->assertJsonValidationErrors('to_user_id');
    }

    /** @test */
    function an_user_id_is_must_exits_in_database_for_making_payment()
    {
        $this->signIn();

        $this->postJson(route('payments.store'), [
            'user_id' => 12,
        ])->assertStatus(422)->assertJsonValidationErrors('to_user_id');
    }

    /** @test */
    function an_amount_is_required_for_making_payment_against_user()
    {
        $this->signIn();

        $this->postJson(route('payments.store'), [
            'user_id' => auth()->id(),
        ])->assertStatus(422)->assertJsonValidationErrors('amount');
    }

    /** @test */
    function an_amount_should_be_a_number()
    {
        $this->signIn();

        $this->postJson(route('payments.store'), [
            'user_id' => auth()->id(),
            'amount'  => 'e2332',
        ])->assertStatus(422)->assertJsonValidationErrors('amount');
    }

    /** @test */
    function decimal_amount_should_be_a_accepted()
    {
        $this->signIn();

        $this->postJson(route('payments.store'), [
            'to_user_id' => auth()->id(),
            'amount'  => 12.21,
        ])->assertStatus(201);
    }

    /** @test */
    function a_payment_can_be_made_against_a_user()
    {
        $this->signIn();

        $toUser = factory(User::class)->create();

        factory(Meal::class)->create([
            'user_id' => $toUser->id,
            'price'   => 70,
        ]);

        $this->postJson(route('payments.store'), [
            'to_user_id' => $toUser->id,
            'amount'     => 70,
        ])->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('payments', [
            'to_user_id' => $toUser->id,
            'amount'     => 70,
        ]);
    }
}
