<?php

namespace Tests\Feature;

use App\Meal;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class CreateMealTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    function a_meal_requires_a_description()
    {
        $meal = factory(Meal::class)->make(['description' => null]);

        $response = $this->postJson(route('meals.store'), $meal->toArray());

        $response->assertJsonValidationErrors(['description']);

        $this->assertEquals(0, Meal::count());
    }

    /** @test */
    function a_meal_description_can_be_max_255()
    {
        $meal = factory(Meal::class)->make(['description' => str_random(256)]);

        $response = $this->postJson(route('meals.store'), $meal->toArray());

        $response->assertJsonValidationErrors(['description']);

        $this->assertEquals(0, Meal::count());
    }

    /** @test */
    function price_is_required()
    {
        $meal = factory(Meal::class)->make(['price' => null]);

        $response = $this->postJson(route('meals.store'), $meal->toArray());

        $response->assertJsonValidationErrors('price');

        $this->assertEquals(0, Meal::count());
    }

    /** @test */
    function price_can_not_be_less_than_or_equal_to_zero()
    {
        $meal = factory(Meal::class)->make(['price' => 0]);

        $response = $this->postJson(route('meals.store'), $meal->toArray());

        $response->assertStatus(422)->assertJsonValidationErrors('price');

        $this->assertEquals(0, Meal::count());

        $meal = factory(Meal::class)->make(['price' => - 5]);

        $response = $this->postJson(route('meals.store'), $meal->toArray());

        $response->assertStatus(422)->assertJsonValidationErrors('price');

        $this->assertEquals(0, Meal::count());
    }

    /** @test */
    function price_should_be_only_number_with_decimal()
    {
        $meal = factory(Meal::class)->make(['price' => 'wdwd']);

        $response = $this->postJson(route('meals.store'), $meal->toArray());

        $response->assertJsonValidationErrors('price');

        $this->assertEquals(0, Meal::count());
    }

    /** @test */
    function a_valid_meal_should_be_saved_in_database()
    {
        $meal = factory(Meal::class)->make();

        $this->postJson(route('meals.store'), $meal->toArray())->assertStatus(201);

        $this->assertEquals(1, Meal::count());
    }

    /** @test */
    function a_meal_can_be_edited()
    {
        $meal = factory(Meal::class)->create();

        $edit = ['description' => 'Hello, I am changed', 'price' => 20];

        $this->putJson(route('meals.update', $meal), $edit)->assertStatus(202);

        $this->assertDatabaseHas('meals', $edit);
    }
}
