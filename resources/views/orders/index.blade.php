@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <div class="list-group">
                    <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action @if(request()->routeIs('orders.index')) active @endif">Orders</a>
                    <a href="{{ route('meals.summery.index') }}" class="list-group-item list-group-item-action @if(request()->routeIs('meals.summery.index')) active @endif">Meals Summery</a>
                </div>
            </div>
            <div class="col-md-10">
                <orders></orders>
            </div>
        </div>
    </div>
@endsection
