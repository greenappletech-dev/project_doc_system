@extends('adminlte::page')


@section('content')

    <div id="app">
        <view_acount_per_beneficiary-component
         :from_date="{{ json_encode($from_date) }}"
         :to_date="{{ json_encode($to_date) }}"
         :districts="{{ $districts }}"
         :district_id="{{ $district_id }}"
         :project_id="{{ $project_id }}">
        </view_acount_per_beneficiary-component>
    </div>


@stop


@section('js')

    <script src="{{ asset('js/app.js') }}"></script>

@stop
