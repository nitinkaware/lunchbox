<?php

namespace App\Jobs;

use App\Http\Requests\OrderRequest;
use App\Meal;
use App\Order;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOrder implements ShouldQueue {

    /**
     * @var Order
     */
    private $order;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var array
     */
    private $userIds;

    public function __construct(Order $order, array $attributes, array $userIds)
    {
        $this->order = $order;
        $this->attributes = $attributes;
        $this->userIds = $userIds;
    }

    public static function fromRequest(Order $order, OrderRequest $request): self
    {
        return new static($order, [
            'meal_id'  => $request->mealId(),
            'price'    => Meal::find($request->mealId())->price,
            'quantity' => $request->quantity(),
        ], $request->userIds());
    }

    public function handle()
    {
        $this->order->forceFill($this->attributes)->save();
        $this->order->users()->sync($this->userIds);
    }
}
