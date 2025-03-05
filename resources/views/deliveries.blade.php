@extends('adminlte::page')


@section('content')

    <div id="app">
        <deliveries-component :districts="{{ $districts }}" :documenttion_types="{{ $documenttion_types }}"></deliveries-component>
    </div>


@stop


@section('js')

    <script src="{{ asset('js/app.js') }}"></script>
  
@stop
