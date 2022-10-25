@extends('backpack::layouts.top_left')

@section('content')

    <div class="container-fluid mt-4 pt-4">
        <a href="{{ route('module.index') }}">Back to module list</a>
        <h1>Title: {{ $entry->title }}</h1>
        <h3>Theme: {{ $entry->theme->title }}</h3>
        <h5>Authors: {{ $entry->authors->pluck('name')->join(', ') }}</h5>

        <a class="btn btn-link" href="{{ backpack_url('module/'.$entry->id.'/edit') }}">Edit Module</a>
        <hr/>

        <ul class="list-group" style="max-width: 800px">
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Approx. time required (minutes):</div>
                <div>{{ $entry->minutes }}</div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Available In Languages:</div>
                <div>
                    <ul>
                        @foreach ($entry->languages as $language)
                            <li>{{ $language->name }} ({{ $language->id }})</li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Indicators Calculated:</div>
                <div>
                    <ul>
                        @foreach ($entry->indicators as $indicator)
                            <li>{{ $indicator->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">SDG Indicators:</div>
                <div>
                    <ul>
                        @foreach ($entry->sdgs as $sdg)
                            <li> {{ $sdg->id }} - {{ $sdg->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Core module?</div>
                <div class="{{ $entry->core ? 'text-success' : 'text-danger' }}">{{ $entry->core ? 'Yes' : 'No' }}</div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Live version available?</div>
                <div class="{{ $entry->live ? 'text-success' : 'text-danger' }}">{{ $entry->live ? 'Yes' : 'No' }}</div>
            </li>
            <hr/>
            <li class="list-group-item d-flex bg-primary">
                <h5 class="mb-0">Current Versions:</h5>
            </li>
            @if($entry->currentVersions->count() === 0)
                <li class="list-group-item d-flex">
                    ~There are no currently live versions for this module. You can upload new drafts or publish draft
                    versions from the list below~
                </li>
            @else
                @foreach($entry->currentVersions as $currentVersion)
                    <li class="list-group-item d-flex">
                        <div
                            class="w-50 text-right mr-4">{{ $currentVersion->version_name }} {{ $currentVersion->mini ? '(reduced)' : '' }}
                            :
                        </div>
                        <div class="text-success mr-3">
                            <a href="{{ Storage::url($currentVersion->file) }}">{{ $currentVersion->file }}</a>
                        </div>
                        <form action="{{ backpack_url('moduleversion/'.$currentVersion->id.'/unpublish') }}">
                            <button type='submit' class="btn btn-sm btn-danger">Make draft</button>
                        </form>
                    </li>
                @endforeach
            @endif
        </ul>
        <hr/>
        <h3>Draft Version(s)</h3>
        <div class="list-group" style="max-width: 1000px">
            @foreach ($entry->draftVersions as $version)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 align-self-center">
                        {{ $version->version_name }} {{ $version->mini ? '(reduced)' : '' }}
                    </h5>
                    <a class="btn btn-link align-self-center" href="{{ Storage::disk('local')->url($version->file) }}">
                        download xls file
                    </a>
                    <div id="test-result-{{$version->id}}">
                        @if($version->test_success)
                            <span class="badge badge-success">TEST PASSED</span>
                        @elseif($version->test_failed)
                            <span class="badge badge-danger">TEST FAILED</span>
                        @else
                            <span class="badge badge-info">Untested</span>
                        @endif
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-info" onclick="testModuleVersion({{$version->id}})" id="test-{{$version->id}}">Test Version with
                            Pyxform
                        </button>
                        <a href="{{ route('moduleversion.edit', ['id' => $version->id]) }}"
                           class="btn btn-primary">edit</a>

                        @if($version->test_success)
                            <a href="{{ route('moduleversion.publish', ['moduleversion' => $version->id]) }}" id="publish-{{$version->id}}"
                               class="btn btn-success">publish</a>
                        @else
                            <button class="btn btn-secondary" disabled="disabled" id="publish-{{$version->id}}">publish</button>
                        @endif
                    </div>
                </li>
            @endforeach
        </div>
        <a href="{{ route('moduleversion.createformodule', ['module' => $entry]) }}" class="btn btn-primary ml-4">+
            Create New Version</a>

        <hr/>

        <h3>Previous Published Versions</h3>
        <div class="list-group" style="max-width: 1000px">
            @foreach ($entry->publishedVersions as $version)
                @if(!$loop->first)
                    <li class="list-group-item d-flex justify-content-between">
                        <h5 class="mb-0 align-self-center">
                            {{ $version->version_name }}
                        </h5>
                        <a class="btn btn-link align-self-center"
                           href="{{ Storage::disk('local')->url($version->file) }}">
                            {{ $version->file_name }}
                        </a>

                        <div class="btn-group">
                            <a href="{{ route('moduleversion.show', ['id' => $version->id]) }}"
                               class="btn btn-primary">show</a>
                        </div>
                    </li>
                @endif
            @endforeach
        </div>
        <hr/>
    </div>

@endsection


@section('after_scripts')
    <script>

        function testModuleVersion(versionId) {

            document.getElementById('test-result-' + versionId).replaceChildren()
            document.getElementById('test-result-' + versionId).insertAdjacentHTML('afterbegin', "<span class='spinner-border-sm spinner-border'></span> test in progress...")

            console.log(versionId);

            $.ajax({
                url: '/admin/moduleversion/' + versionId + '/test',
                type: 'POST',
                success: function (result) {
                    new Noty({
                        type: 'success',
                        text: result.message,
                        timeout: false,
                    }).show();

                    document.getElementById('test-result-' + versionId).replaceChildren()
                    document.getElementById('test-result-' + versionId).insertAdjacentHTML('afterbegin', "<span class='badge badge-success'>TEST PASSED</span>")

                    document.getElementById('publish-' + versionId).remove()
                    document.getElementById('test-'+versionId).insertAdjacentHTML('afterend', `
                            <a
                                href="moduleversion/${versionId}/publish"
                                id="publish-${versionId}"
                                class="btn btn-success"
                            >
                                publish
                            </a>`
                    )
                },
                error: function (result) {
                    let errors = result.responseJSON.errors ?? null;
                    let path = result.responseJSON.xlsform_path ?? null;

                    new Noty({
                        type: 'danger',
                        text: `This module failed to compile when combined with the current core modules. The following error occured:<br/>
                                    ${errors}<br/>
                                    Download the xlsform to review and identify the modules that need updating.`,
                        timeout: false,
                    }).show();

                    document.getElementById('test-result-' + versionId).replaceChildren()
                    document.getElementById('test-result-' + versionId).insertAdjacentHTML('afterbegin', `
                                <span class='badge badge-danger'>TEST FAILED</span>
                                <a href='${path}' target='_blank'>Download test form for debugging</a>`
                    )

                }

            })
        }
    </script>
@endsection
