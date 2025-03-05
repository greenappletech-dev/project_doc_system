@extends('adminlte::page')


@section('content')

    <div id="app">
        <delivery_history-component :deliveries="{{ $deliveries }} " ></delivery_history-component>
    </div>


@stop


@section('js')

    <script src="{{ asset('js/app.js') }}"></script>
  
@stop
