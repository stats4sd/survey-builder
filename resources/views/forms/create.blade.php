@extends('backpack::layouts.top_left')

@section('content')
    <h2>RHoMIS XLS Form Builder</h2>
    <div class="card border border-info">
        <div class="card-header">Instructions</div>
        <div class="card-body">Building a survey form for your RHoMIS project involves 3 stages.
            <ol>
                <li>First, you give some basic information and choose which modules to use.</li>
                <li>Next, you can customise the questions and available responses to your specific context.</li>
                <li>Finally, you can review the form content and deploy it to test on your Android device.</li>
            </ol>
            Your progress is saved after each stage, and you may go back to change any entry until you mark the form as
            'finalised'. At that point, the form is locked and ready for live data collection.<br/>

        </div>
    </div>
    <div id="app">

        <ul class="nav nav-tabs" id="stage-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="stage-tab nav-link active" id="stage-one-tab" data-toggle="tab" href="#stage-one" role="tab"
                   aria-controls="stage-one" aria-selected="true">Stage 1 - Core Info + Modules</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="stage-tab nav-link" id="stage-two-tab" data-toggle="tab" href="#stage-two" role="tab"
                   aria-controls="stage-two" aria-selected="false">Stage 2 - Customise the Questions</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="stage-tab nav-link" id="stage-three-tab" data-toggle="tab" href="#stage-three" role="tab"
                   aria-controls="stage-three" aria-selected="false">Stage 3 - Review + Confirm</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="stage-one" role="tabpanel" aria-labelledby="stage-one-tab">
                <form-builder-stage-one
                    :countries="{{$countries->toJson() }}"
                    :languages="{{ $languages->toJson() }}"
                    :projects="{{ $projects->toJson() }}"
                    :modules="{{ $modules->toJson() }}"
                    :themes="{{ $themes->toJson() }}"
                    user-id="{{ Auth::id() }}"
                >
                    <template v-slot:csrf>@csrf</template>
                </form-builder-stage-one>
            </div>
            <div class="tab-pane fade disabled" id="stage-two" role="tabpanel" aria-labelledby="stage-two-tab">
                <b-alert class="secondary" show>Please save your form before continuing</b-alert>
            </div>
            <div class="tab-pane fade disabled" id="stage-three" role="tabpanel" aria-labelledby="stage-three-tab">
                <b-alert class="secondary" show>Please save your form before continuing</b-alert>
            </div>
        </div>
    </div>

@endsection

@section('after_scripts')
    <script src="{{ asset('js/xlsforms.js') }}"></script>

{{--    <script>--}}
{{--        // update url bar with tab hrefs (and show tab based on href/ element id in url bar)--}}
{{--        $(document).ready(() => {--}}

{{--            let url = location.href.replace(/\/$/, "");--}}

{{--            if (location.hash) {--}}
{{--                const hash = url.split("#");--}}
{{--                $('#stage-tabs a[href="#' + hash[1] + '"]').tab("show");--}}
{{--                url = location.href.replace(/\/#/, "#");--}}
{{--                history.replaceState(null, null, url);--}}
{{--                setTimeout(() => {--}}
{{--                    $(window).scrollTop(0);--}}
{{--                }, 400);--}}
{{--            } else {--}}
{{--                $('#stage-tabs a[href="#stage-one"]').tab("show");--}}
{{--                url = location.href.replace(/\/#/, "#");--}}
{{--                history.replaceState(null, null, url);--}}
{{--                setTimeout(() => {--}}
{{--                    $(window).scrollTop(0);--}}
{{--                }, 400);--}}
{{--            }--}}

{{--            $('a.stage-tab').on("click", function () {--}}
{{--                let newUrl;--}}
{{--                const hash = $(this).attr("href");--}}
{{--                if (hash == "#stage-one") {--}}
{{--                    newUrl = url.split("#")[0];--}}
{{--                } else {--}}
{{--                    newUrl = url.split("#")[0] + hash;--}}
{{--                }--}}
{{--                newUrl += "/";--}}
{{--                history.replaceState(null, null, newUrl);--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
@endsection


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css') }}"/>
@endsection
