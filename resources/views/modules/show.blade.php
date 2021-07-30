@extends('backpack::layouts.top_left')

@section('content')

    <div class="container-fluid mt-4 pt-4">
        <a href="{{ route('module.index') }}">Back to module list</a>
        <h1>{{ $entry->title }}</h1>
        <h3>{{ $entry->theme->title }}</h3>

        <hr />

        <ul class="list-group" style="max-width: 800px">
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Approx. time required (minutes): </div>
                <div>{{ $entry->minutes }}</div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Core module? </div>
                <div class="{{ $entry->core ? 'text-success' : 'text-danger' }}">{{ $entry->core ? 'Yes' : 'No' }}</div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Live version available? </div>
                <div class="{{ $entry->live ? 'text-success' : 'text-danger' }}">{{ $entry->live ? 'Yes' : 'No' }}</div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Current Published Version: </div>
                <div class="{{ $entry->publishedVersions->count() > 0 ? 'text-success' : 'text-danger' }}">
                    @if ($entry->publishedVersions->count() < 1)
                        ~none~
                    @else
                        {{ $entry->current_version_name }}
                    @endif

                </div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-50 text-right mr-4">Current Published File: </div>
                <div class="{{ $entry->publishedVersions->count() > 0 ? 'text-success' : 'text-danger' }}">
                    @if ($entry->publishedVersions->count() < 1)
                        ~none~
                    @else
                        <a href="{{ Storage::url($entry->current_file) }}">{{ $entry->current_file }}</a>
                    @endif

                </div>
            </li>
        </ul>
        <hr />
        <h3>Current Draft Version(s)</h3>
        <div class="list-group" style="max-width: 1000px">
            @foreach ($entry->draftVersions as $version)
                <li class="list-group-item d-flex justify-content-between">
                    <h5 class="mb-0 align-self-center">
                        {{ $version->version_name }}
                    </h5>
                    <a class="btn btn-link align-self-center" href="{{ Storage::disk('local')->url($version->file) }}">
                        {{ $version->file_name }}
                    </a>
                    <div class="btn-group">
                        <a href="{{ route('moduleversion.edit', ['id' => $version->id]) }}"
                            class="btn btn-primary">edit</a>
                        <a href="{{ route('moduleversion.publish', ['moduleversion' => $version->id]) }}"
                            class="btn btn-success">publish</a>
                    </div>
                </li>
            @endforeach
        </div>

        <hr />
        <h3>Previous Published Versions</h3>
        <div class="list-group" style="max-width: 1000px">
            @foreach ($entry->publishedVersions as $version)
                <li class="list-group-item d-flex justify-content-between">
                    <h5 class="mb-0 align-self-center">
                        {{ $version->version_name }}
                    </h5>
                    <a class="btn btn-link align-self-center" href="{{ Storage::disk('local')->url($version->file) }}">
                        {{ $version->file_name }}
                    </a>

                    <div class="btn-group">
                        <a href="{{ route('moduleversion.show', ['id' => $version->id]) }}"
                            class="btn btn-primary">show</a>
                    </div>
                </li>
            @endforeach
        </div>
        <hr />

        <a href="{{ route('moduleversion.createformodule', ['module' => $entry]) }}" class="btn btn-primary ml-4">+
            Create New Version</a>



    </div>


@endsection
