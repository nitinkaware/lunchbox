<?php

namespace App\Http\Requests;

use DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'to_user_id' => ['required', Rule::exists('users', 'id')],
            'amount'  => ['required', 'numeric'],
        ];
    }

    public function fromUserId()
    {
        return $this->get('to_user_id');
    }

    public function amount()
    {
        return $this->get('amount');
    }
}
