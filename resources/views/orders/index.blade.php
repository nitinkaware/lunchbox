@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <div class="list-group">
                    <button type="button" class="list-group-item list-group-item-action active">Orders</button>
                    <button type="button" class="list-group-item list-group-item-action">Meals</button>
                </div>
            </div>
            <div class="col-md-10">
                <orders></orders>
            </div>
        </div>
    </div>
@endsection
