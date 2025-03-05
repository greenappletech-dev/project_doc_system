@extends('adminlte::page')


@section('content')

    <div id="app">
        <home-component></home-component>
    </div>


@stop


@section('js')

    <script src="{{ asset('js/app.js') }}"></script>
  
@stop
