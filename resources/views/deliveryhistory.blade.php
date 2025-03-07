@extends('adminlte::page')


@section('content')

    <div id="app">
        <delivery_history-component
         :from_date="{{ json_encode($from_date) }}"
         :to_date="{{ json_encode($to_date) }}"
         :districts="{{ $districts }}"
         :district_id="{{ $district_id }}"
         :project_id="{{ $project_id }}">
        </delivery_history-component>
    </div>


@stop


@section('js')

    <script src="{{ asset('js/app.js') }}"></script>
  
@stop
