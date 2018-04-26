<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class User extends Authenticatable {

    use Notifiable, ParsableId;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'from_user_id');
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function oweTotal(): float
    {
        $owe = $this->orders->map(function (Order $order) {
            /** @var Order $order */
            $order['owes'] = $order->owes();

            return $order;
        })->sum('owes');

        return $owe - $this->totalAmountPaid();
    }

    public function owesForOrder($orderId): float
    {
        $orderId = $this->parseId($orderId);

        /** @var Order $order */
        $order = $this->orders()->with('meal')->where('id', $orderId)->first();

        return round($order->owes() - $this->amountPaidTo($order->meal->user_id), 2);
    }

    public function owesForMeal($meal): float
    {
        $mealId = $this->parseId($meal);

        /** @var Collection $orders */
        $orders = $this->orders()->with('users')->forMeal($mealId)->get();

        return round($orders->map(function ($order) {
            /** @var Order $order */
            $order['owes'] = $order->owes();

            return $order;
        })->sum('owes'), 2);
    }

    public function oweTo($userId): float
    {
        $userId = $this->parseId($userId);

        $orderTotal = $this->orders()->with('users')->whereHas('meal', function ($query) use ($userId) {
            $query->where('meals.user_id', $userId);
        })->get()->each(function ($order) {
            /** @var Order $order */
            $order['owes'] = $order->owes();

            return $order;
        })->sum('owes');

        return $orderTotal - $this->amountPaidTo($userId);
    }

    public function owePayments(): Collection
    {
        return self::whereHas('meals.orders.users', function ($query) {
            $query->where('users.id', $this->getKey());
        })->get();
    }

    public function amountPaidTo($toUser): float
    {
        $toUser = $this->parseId($toUser);

        return $this->payments()->toUser($toUser)->sum('amount');
    }

    public function totalAmountPaid(): float
    {
        return $this->payments()->sum('amount');
    }

    public static function findByEmail($email)
    {
        return self::where('email', $email)->firstOrFail();
    }
}
