<?php

namespace App\Http\Resources;

use App\Meal;
use App\Order;
use App\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderHistoryCollection extends ResourceCollection {

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'      => $this->collection->transform(function ($order) {
                /** @var Order $order */
                return [
                    'id'             => $order->id,
                    'price'          => $order->price(),
                    'owes'           => $order->owes(),
                    'quantity'       => $order->quantity(),
                    'meal'           => [
                        'description' => $order->meal->description,
                    ],
                    'users'          => $order->users->pluck('name'),
                    'shared_between' => $order->users->pluck('name')->implode(', '),
                    'created_at'     => $order->created_at->diffForHumans(),
                    'paid_at'        => $order->pivot->paid_at,
                ];
            }),
            'oweTotal' => round(auth()->user()->oweTotal(), 2),
            'users'     => User::all(),
            'meals'     => Meal::all(),
        ];
    }
}
