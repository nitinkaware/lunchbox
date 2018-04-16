<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealRequest;
use App\Jobs\CreateMeal;
use App\Jobs\UpdateMeal;
use App\Meal;
use Symfony\Component\HttpFoundation\Response;

class MealsController extends Controller {

    public function index()
    {
        //
    }

    public function store(MealRequest $request)
    {
        $this->dispatchNow(CreateMeal::fromRequest($request));

        return response()->json([], Response::HTTP_CREATED);
    }

    public function update(Meal $meal, MealRequest $request)
    {
        $this->dispatchNow(UpdateMeal::fromRequest($meal, $request));

        return response()->json([], Response::HTTP_ACCEPTED);
    }
}
