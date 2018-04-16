<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => 'required|max:255',
            'price'       => 'required|integer|min:1',
        ];
    }

    public function description(): string
    {
        return $this->get('description');
    }

    public function price(): float
    {
        return $this->get('price');
    }
}
