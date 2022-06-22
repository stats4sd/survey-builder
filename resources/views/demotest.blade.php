@extends('layouts.app')

@section('content')

    <div id="app">
    <demo-test/>
    </div>

@endsection

@section('after_scripts')
    <script src="{{ asset('js/xlsforms.js') }}"></script>
@endsection


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}"/>
@endsection
