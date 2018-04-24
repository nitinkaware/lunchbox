<?php

namespace App\Http\Controllers;

use App\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MealsSummaryController extends Controller {

    public function index()
    {
        $mealsSummery = $this->getMealsSummary();

        $totalPaid = auth()->user()->totalAmountPaid();

        return view('meals.summary.index', compact('mealsSummery', 'totalPaid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getMealsSummary(): Collection
    {
        return Meal::orderedForUser(auth()->user())->map(function ($meal) {
            $meal['owe'] = auth()->user()->owesForMeal($meal);

            return $meal;
        });
    }
}
