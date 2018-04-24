<?php

namespace Tests\Unit;

use App\Order;
use App\Meal;
use App\Payment;
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

        $john->payments()->create([
            'amount'     => 30,
            'to_user_id' => Meal::first()->user_id,
        ]);

        $jeffery->payments()->create([
            'amount'     => 10.12,
            'to_user_id' => Meal::first()->user_id,
        ]);

        $this->assertEquals(30, $john->owesForOrder($order));
        $this->assertEquals(49.88, $jeffery->owesForOrder($order));
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
        /** @var User $john */
        list($john, $jeffery, $order, $meal) = $this->placeOrder(3);

        $this->assertEquals(180, $john->oweTotal());

        $john->payments()->create([
            'amount'     => 100,
            'to_user_id' => Meal::first()->user_id,
        ]);

        $this->assertEquals(80, $john->oweTotal());
    }

    /** @test */
    function a_user_has_many_payments()
    {
        /** @var User $paymentFrom */
        /** @var User $paymentTo */
        $paymentFrom = factory(User::class)->create();
        $paymentTo = factory(User::class)->create();

        $paymentFrom->payments()->create([
            'to_user_id' => $paymentTo,
            'amount'     => 10,
        ]);

        $this->assertEquals(1, $paymentFrom->payments()->count());
    }

    /** @test */
    function it_gives_all_the_users_against_I_have_order()
    {
        $john = factory(User::class)->create();

        $jeffery = factory(User::class)->create();
        $soya = factory(User::class)->create();
        $loya = factory(User::class)->create();

        $jefferyMeal = factory(Meal::class)->create(['user_id' => $jeffery]);
        $soyaMeal = factory(Meal::class)->create(['user_id' => $soya]);
        $loyaMeal = factory(Meal::class)->create(['user_id' => $loya]);

        factory(Order::class)->create(['meal_id' => $jefferyMeal])->users()->attach($john);
        factory(Order::class)->create(['meal_id' => $soyaMeal])->users()->attach($john);
        factory(Order::class)->create(['meal_id' => $loyaMeal])->users()->attach($john);

        $paymentUsers = $john->owePayments();

        collect([$jeffery, $soya, $loya])->each(function ($user) use ($paymentUsers) {
            /** @var Collection $paymentUsers */
            $this->assertNotNull($paymentUsers->firstWhere('id', $user->id));
        });
    }

    /** @test */
    function payment_against_users_should_be_correct()
    {
        /** @var User $john */
        $john = factory(User::class)->create();

        $jeffery = factory(User::class)->create();
        $soya = factory(User::class)->create();
        $loya = factory(User::class)->create();

        $price = 70;

        $jefferyMeal = factory(Meal::class)->create(['user_id' => $jeffery, 'price' => $price]);
        $soyaMeal = factory(Meal::class)->create(['user_id' => $soya, 'price' => $price]);
        $loyaMeal = factory(Meal::class)->create(['user_id' => $loya, 'price' => $price]);

        factory(Order::class)->create(['price' => $price, 'meal_id' => $jefferyMeal])->users()->attach($john);
        factory(Order::class)->create(['price' => $price, 'meal_id' => $jefferyMeal])->users()->attach($john);
        factory(Order::class)->create(['price' => $price, 'meal_id' => $soyaMeal])->users()->attach($john);
        factory(Order::class)->create(['price' => $price, 'meal_id' => $loyaMeal])->users()->attach($john);

        $john->payments()->create(['amount' => 140, 'to_user_id' => $jeffery->id]);

        /** @var Collection $paymentUsers */
        $paymentUsers = $john->owePayments()->map(function ($user) use ($john) {
            /** @var User $user */
            $user['owe'] = $john->amountPaidTo($user);

            return $user;
        });

        $this->assertEquals(140, $paymentUsers->firstWhere('id', $jeffery->id)->owe);
    }

    /** @test */
    function it_should_return_the_total_payment_made_against_the_user()
    {
        /** @var User $john */
        $john = factory(User::class)->create();
        /** @var User $jeffery */
        $jeffery = factory(User::class)->create();

        $john->payments()->create(['to_user_id' => $jeffery->id, 'amount' => 300]);

        $this->assertEquals(300, $john->amountPaidTo($jeffery));
    }

    /** @test */
    function it_know_how_much_amount_it_has_paid_in_total()
    {
        /** @var User $john */
        $john = factory(User::class)->create();

        /** @var User $jeffery */
        $jeffery = factory(User::class)->create();
        $nitin = factory(User::class)->create();

        $john->payments()->create(['to_user_id' => $jeffery->id, 'amount' => 300]);
        $john->payments()->create(['to_user_id' => $nitin->id, 'amount' => 200]);

        $this->assertEquals(500, $john->totalAmountPaid());
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

        for ($i = 0; $i < $numberOfOrder; $i ++) {
            /** @var Collection $orders */
            $order = factory(Order::class)->create([
                'quantity' => 2,
                'price'    => $price,
                'meal_id'  => $meal->id,
            ]);

            $order->users()->attach($john);
            $order->users()->attach($jeffery);
        }

        return [$john, $jeffery, $order, $meal];
    }
}
