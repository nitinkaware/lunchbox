@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-2">
                @include('layouts._sidebar')
            </div>
            <div class="col-md-10">
                <orders :prop-orders="{{ json_encode($orders->toArray(request())) }}"></orders>
            </div>
        </div>
    </div>
@endsection
