<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['description', 'price'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function orderedForUser($user)
    {
        $user = $user instanceof Model ? $user->getKey() : $user;

        return self::whereHas('orders.users', function ($query) use($user) {
            $query->where('users.id', $user);
        })->get();
    }
}
