@extends('layouts.app')

@section('content')
    <h2>RHoMIS XLS Form Builder</h2>
    <div id="app">
        <b-card class="border border-dark" no-body>
            <b-card-header role="tab" class="header-dark text-white">
                <b-button variant="link" v-b-toggle-instructions-collapse><h3>Survey Builder <i class="las la-question-circle"></i></h3></b-button>
            </b-card-header>
            <b-collapse class="card-body" id="instructions-collapse">
                This tool will help you build your own, customised RHoMIS ODK Survey form. The pages below will guide you
                through each stage in the process. Please read each instruction carefully.<br/><br/>
                The Process is divided into 4 stages:
                <ol>
                    <li>First, you give some basic information about your survey project.</li>
                    <li>Second, you get to build the survey using the complete set of RHoMIS Core and Optional
                        Modules.
                    </li>
                    <li>Next, you can customise the questions and available responses to your specific context.</li>
                    <li>Finally, you can review the complete form and decide on the next steps.</li>
                </ol>
                Your progress is saved after each stage, and you may go back to change any entry until you mark the form as
                'finalised'. At that point, the form is locked and ready for live data collection.<br/>
            </b-collapse>
        </b-card>
        <b-card no-body class="border-dark border-top-0 rounded-top rounded-lg">
            <b-tabs card nav-class="bg-dark rounded-top rounded-sm flex-nowrap" nav-wrapper-class="px-2" lazy>
                <b-tab title="Stage 1 - Create" lazy>
                    <form-builder-stage-one
                        :countries="{{$countries->toJson() }}"
                        :languages="{{ $languages->toJson() }}"
                        :projects="{{ $projects->toJson() }}"
                        :modules="{{ $modules->toJson() }}"
                        :themes="{{ $themes->toJson() }}"
                        user-id="{{ Auth::id() }}"
                        :xlsformOriginal="{{ $xlsform->toJson() }}"
                    >
                        <template v-slot:csrf>@csrf</template>
                    </form-builder-stage-one>
                </b-tab>
                <b-tab title="Stage 2 - Build" active>
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
