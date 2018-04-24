<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait ParsableId {

    protected function parseId($value)
    {
        return $value instanceof Model ? $value->getKey() : $value;
    }
}