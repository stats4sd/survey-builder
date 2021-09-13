@extends('backpack::layouts.top_left')

@section('content')

<div id="app">
    <form-builder :projects="{{ $projects->toJson() }}"></form-builder>
</div>
@endsection

@section('after_scripts')
    <script src="{{ asset('js/xlsforms.js') }}"></script>
@endsection