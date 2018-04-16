<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $fillable = [
        'meal_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('paid_at');
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function scopeForMeal(Builder $query, $mealId)
    {
        $query->where('meal_id', $mealId);
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function shares(): int
    {
        return $this->users()->count();
    }

    public function owes(): float
    {
        return round(
            ($this->price() * $this->quantity()) / $this->shares(),
            2
        );
    }

    public function createTransaction($userId)
    {
        $userId = $userId instanceof User ? $userId->getKey() : $userId;

        $transaction = new Transaction(['amount' => $this->price()]);
        $transaction->user_id = $userId;
        $transaction->save();

        return $transaction;
    }
}
