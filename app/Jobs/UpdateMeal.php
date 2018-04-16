<?php

namespace App\Jobs;

use App\Http\Requests\MealRequest;
use App\Meal;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMeal implements ShouldQueue {

    /**
     * @var Meal
     */
    private $meal;

    /**
     * @var array
     */
    private $attributes;

    public function __construct(Meal $meal, array $attributes)
    {
        $this->meal = $meal;
        $this->attributes = $attributes;
    }

    public static function fromRequest(Meal $meal, MealRequest $request): self
    {
        return new static($meal, [
            'description' => $request->description(),
            'price' => $request->price(),
        ]);
    }

    public function handle()
    {
        return $this->meal->update(
            $this->attributes
        );
    }
}
