@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <create-order :prop-meals="{{ $meals }}" :prop-users="{{ $users }}"></create-order>
            </div>
        </div>
    </div>
@endsection
