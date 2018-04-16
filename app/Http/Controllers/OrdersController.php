<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CreateOrder;
use App\Jobs\DeleteOrder;
use App\Jobs\UpdateOrder;
use App\Order;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller {

    public function index()
    {
        //
    }

    public function store(OrderRequest $request)
    {
        $this->dispatchNow(CreateOrder::fromRequest($request));

        return response()->json([], Response::HTTP_CREATED);
    }


    public function update(Order $order, OrderRequest $request)
    {
        $this->dispatchNow(UpdateOrder::fromRequest($order, $request));

        return response()->json([], Response::HTTP_ACCEPTED);
    }

    public function destroy(Order $order)
    {
        $this->dispatchNow(new DeleteOrder($order));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
