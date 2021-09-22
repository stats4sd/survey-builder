@extends('backpack::layouts.top_left')

@section('content')

<div id="app">
    <form-builder
    :projects="{{ $projects->toJson() }}"
    :modules="{{ $modules->toJson() }}"
    :themes="{{ $themes->toJson() }}"
    user-id="{{ Auth::id() }}"
    >
    <template v-slot:csrf>@csrf</template>
    </form-builder>
</div>
@endsection

@section('after_scripts')
    <script src="{{ asset('js/xlsforms.js') }}"></script>
@endsection

@section('after_styles')
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}"/>
@endsection