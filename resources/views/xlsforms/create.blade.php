@extends('layouts.app')

@section('content')

    <div id="app">
        <b-card no-body class="border-dark border-top-0 rounded-top rounded-lg">
            <b-tabs card nav-class="bg-dark rounded-top rounded-sm flex-nowrap" nav-wrapper-class="px-2" lazy>
                <b-tab title="Stage 1 - Create" active>
                    <form-builder-stage-one
                        project-name="{{ $project_name }}"
                        :countries="{{$countries->toJson() }}"
                        :languages="{{ $languages->toJson() }}"
                        :projects-start="{{ $projects->toJson() }}"
                        :modules="{{ $modules->toJson() }}"
                        :themes="{{ $themes->toJson() }}"
                        user-id="{{ Auth::id() }}"
                    >
                        <template v-slot:csrf>@csrf</template>
                    </form-builder-stage-one>
                </b-tab>
                <b-tab title="Stage 2 - Build" lazy>
                    <b-card class="secondary">Please save your form before continuing</b-card>
                </b-tab>
                <b-tab title="Stage 3 - Customise" lazy>
                    <b-card class="secondary">Please save your form before continuing</b-card>
                </b-tab>
                <b-tab title="Stage 4 - Review" lazy>
                    <b-card class="secondary">Please save your form before continuing</b-card>
                </b-tab>
            </b-tabs>
        </b-card>
    </div>

@endsection

@section('after_scripts')
    <script src="{{ asset('js/xlsforms.js') }}"></script>
@endsection


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}"/>
@endsection
