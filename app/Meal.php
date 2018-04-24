<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model {

    use ParsableId;

    protected $fillable = ['description', 'price'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function orderedForUser($user)
    {
        $instance = new self();
        $user = $instance->parseId($user);

        return $instance::whereHas('orders.users', function ($query) use ($user) {
            $query->where('users.id', $user);
        })->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
