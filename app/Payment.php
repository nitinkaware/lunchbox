<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    protected $fillable = ['to_user_id', 'amount'];

    public function scopeToUser(Builder $query, $userId)
    {
        $query->where('to_user_id', $userId);
    }

    public function scopeFromUser(Builder $query, $userId)
    {
        $query->where('from_user_id', $userId);
    }
}
