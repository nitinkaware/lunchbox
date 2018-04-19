<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderHistoryCollection;
use App\Jobs\CreateOrder;
use App\Jobs\DeleteOrder;
use App\Jobs\UpdateOrder;
use App\Meal;
use App\Order;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller {

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->getOrders();
        }

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $meals = Meal::all();

        return view('orders.create', compact('users', 'meals'));
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

    private function getOrders()
    {
        $orders = auth()->user()->orders()->with('meal:id,description', 'users:id,name')->latest()->paginate(10);

        return new OrderHistoryCollection($orders);
    }
}
