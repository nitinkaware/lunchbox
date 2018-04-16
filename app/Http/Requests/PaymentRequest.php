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
            'order_id'   => ['array', 'required'],
            'order_id.*' => [Rule::exists('orders', 'id')->where(function ($query) {
                $query->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('order_user')
                        ->whereRaw('order_user.order_id = orders.id and order_user.user_id = ?', [auth()->id()]);
                });
            })],
        ];
    }

    public function orderIds(): array
    {
        return $this->get('order_id');
    }
}
