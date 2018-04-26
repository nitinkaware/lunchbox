<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCollection extends ResourceCollection {

    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function toJson()
    {
        return json_encode($this->toArray(request()));
    }
}
