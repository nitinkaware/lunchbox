<?php

namespace App\Jobs;

use App\Events\MealOrdered;
use App\Http\Requests\OrderRequest;
use App\Order;
use App\Meal;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateOrder implements ShouldQueue {

    /**
     * @var int
     */
    private $mealId;

    /**
     * @var array
     */
    private $userIds;

    /**
     * @var int
     */
    private $quantity;

    public function __construct(int $mealId, array $userIds, int $quantity)
    {
        $this->mealId = $mealId;
        $this->userIds = $userIds;
        $this->quantity = $quantity;
    }

    public static function fromRequest(OrderRequest $request)
    {
        return new static(
            $request->mealId(),
            $request->userIds(),
            $request->quantity()
        );
    }

    public function handle()
    {
        /** @var Order $order */
        $order = Order::forceCreate([
            'meal_id' => $this->mealId,
            'price'     => $this->mealPrice(),
            'quantity'  => $this->quantity,
        ]);

        $order->users()->sync($this->userIds);

        collect($this->userIds)->each(function($userId) use ($order) {
            event(new MealOrdered($order, $userId));
        });

        return true;
    }

    /**
     * @return int
     */
    private function mealPrice(): int
    {
        return Meal::find($this->mealId)->price;
    }
}
