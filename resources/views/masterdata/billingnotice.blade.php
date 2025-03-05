@extends('adminlte::page')


@section('content')

    <div id="app">
        <billing-notice-component>
            
        </billing-notice-component>
    </div>


@stop


@section('js')

    <script src="{{ asset('js/app.js') }}"></script>
  
@stop
