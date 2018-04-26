<?php

namespace App\Http\Controllers;

class DashboardController extends Controller {

    public function index()
    {
        $users = auth()->user()->owePayments()->each(function ($user) {
            $user['owe'] = auth()->user()->oweTo($user);

            return $user;
        });

        return view('dashboard.index', compact('users'));
    }
}
