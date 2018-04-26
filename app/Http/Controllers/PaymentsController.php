<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Jobs\CreatePayment;
use Symfony\Component\HttpFoundation\Response;

class PaymentsController extends Controller {

    public function index()
    {
        $payments = $this->getPayments();

        if (request()->wantsJson()) {
            return $payments;
        }

        return view('payments.index', compact('payments'));
    }

    public function store(PaymentRequest $request)
    {
        $this->dispatchNow(CreatePayment::fromRequest($request));

        return response([], Response::HTTP_CREATED);
    }

    private function getPayments()
    {
        return new PaymentCollection(auth()->user()->owePayments()->map(function ($user) {
            $user['paid_amount'] = auth()->user()->amountPaidTo($user);

            return $user;
        }));
    }
}
