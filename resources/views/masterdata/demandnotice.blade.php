@extends('adminlte::page')


@section('content')

    <div id="app">
        <demand-notice-component>
            
        </demand-notice-component>
    </div>


@stop


@section('js')

    <script src="{{ asset('js/app.js') }}"></script>
  
@stop
