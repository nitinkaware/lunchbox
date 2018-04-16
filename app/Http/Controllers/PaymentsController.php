<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Jobs\CreatePayment;
use Symfony\Component\HttpFoundation\Response;

class PaymentsController extends Controller {

    function __construct()
    {
        $this->middleware('auth');
    }

    public function store(PaymentRequest $request)
    {
        $this->dispatchNow(CreatePayment::fromRequest($request));

        return response()->json([], Response::HTTP_CREATED);
    }
}
