@extends('layouts.app')

@section('content')
    <h2>RHoMIS XLS Form Builder</h2>
    <div id="app">
        <b-card class="border border-dark" no-body>
            <b-card-header role="tab" class="bg-dark border-0 p-0">
                <b-button

                    variant="link"
                    block
                    v-b-toggle.instructions-collapse
                    class="text-white text-left p-4"
                >
                    <h3 class="mb-0">Survey Builder</h3>
                </b-button>
            </b-card-header>
        </b-card>

        <form-key-details-view :xlsform="{{ $xlsform->toJson() }}"
                               rhomis-app-url="{{ config('auth.rhomis_url') }}"></form-key-details-view>

        <b-card no-body class="border-dark border-top-0 rounded-top rounded-lg" header-bg-variant="dark">
            <template #header>
                <b-nav card-header tabs class="bg-dark rounded-top rounded-sm flex-nowrap">
                    <b-nav-item href="{{ url('xlsform/'.$xlsform->name.'/edit-one') }}">Stage 1 - Create</b-nav-item>
                    <b-nav-item href="{{ url('xlsform/'.$xlsform->name.'/edit-two') }}">Stage 2 - Build</b-nav-item>
                    <b-nav-item active>Stage 3 - Customise</b-nav-item>
                    <b-nav-item href="{{ url('xlsform/'.$xlsform->name.'/edit-four') }}">Stage 4 - Review</b-nav-item>
                </b-nav>
            </template>
            <b-card-body>
                <form-builder-stage-three
                    :xlsform-original="{{ $xlsform->toJson() }}"
                    user-id="{{ Auth::id() }}"
                >
                </form-builder-stage-three>
            </b-card-body>

        </b-card>
    </div>

@endsection

@section('after_scripts')
    <script src="{{ asset('js/xlsforms.js') }}"></script>
@endsection


@section('after_styles')
    {{--    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}"/>--}}
@endsection
