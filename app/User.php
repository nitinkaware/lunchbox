<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class User extends Authenticatable {

    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('paid_at');
    }

    public static function findByEmail($email)
    {
        return self::where('email', $email)->firstOrFail();
    }

    public function oweTotal()
    {
        return $this->orders->map(function (Order $order) {
            /** @var Order $order */
            $order['owes'] = $order->owes();

            return $order;
        })->sum('owes');
    }

    public function owesForOrder($orderId): float
    {
        $orderId = $orderId instanceof Order ? $orderId->getKey() : $orderId;

        /** @var Order $order */
        $order = $this->orders()->where('id', $orderId)->first();

        return $order->owes();
    }

    public function owesForMeal($meal)
    {
        $mealId = $meal instanceof Meal ? $meal->getKey() : $meal;

        /** @var Collection $orders */
        $orders = $this->orders()->forMeal($mealId)->get();

        return round($orders->map(function ($order) {
            /** @var Order $order */
            $order['owes'] = $order->owes();

            return $order;
        })->sum('owes'), 2);
    }
}
