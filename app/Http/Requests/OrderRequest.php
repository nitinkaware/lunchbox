<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'meal_id' => ['required', Rule::exists('meals', 'id')],
            'user_id'   => ['array', 'required'],
            'user_id.*' => ['required', Rule::exists('users', 'id')],
            'quantity'  => ['required', 'integer', 'between:1,5'],
        ];
    }

    public function mealId()
    {
        return $this->get('meal_id');
    }

    public function userIds()
    {
        return $this->get('user_id');
    }

    public function quantity()
    {
        return $this->get('quantity');
    }
}
