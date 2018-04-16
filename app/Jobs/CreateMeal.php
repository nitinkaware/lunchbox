<?php

namespace App\Jobs;

use App\Http\Requests\MealRequest;
use App\Meal;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateMeal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $price;

    public function __construct(string $description, int $price)
    {

        $this->description = $description;
        $this->price = $price;
    }

    public static function fromRequest(MealRequest $request): self
    {
        return new static(
            $request->description(),
            $request->price()
        );
    }

    public function handle()
    {
        return Meal::create([
            'description'  => $this->description,
            'price' => $this->price,
        ]);
    }
}
