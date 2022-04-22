@extends('backpack::layouts.top_left')

@section('content')

<div class="container-fluid mt-4 pt-4">
    <h1>Core {{ $coreVersion->version_name }}</h1>

    <ul class="list-group" style="max-width: 800px">
            <li class="list-group-item d-flex">
                <div class="w-25 text-right mr-4">Published?: </div>
                <div>{{ $coreVersion->published_at ?? 'no'}}</div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-25 text-right mr-4">Full or Reduced?: </div>
                <div>{{ $coreVersion->mini ? 'Reduced' : 'Full'}}</div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-25 text-right mr-4">File: </div>
                <div><a href="{{ Storage::url($coreVersion->file)}}">{{ $coreVersion->file }}</a></div>
            </li>
            <li class="list-group-item d-flex">
                <div class="w-25 text-right mr-4">Modules:</div>
                <div>
                    @foreach($coreVersion->moduleVersions as $moduleVersion)
                        <div class="list-group">
                            <div class="list-group-item">
                                <h6>{{ $moduleVersion->module->title }}</h6>
                                <ul>
                                    <li>Theme: <b>{{ $moduleVersion->module->theme->title }}</b></li>
                                    <li>Avg. Time of Interview: <b>{{ $moduleVersion->module->minutes }}</b></li>
                                    <li>SDG Indicators: <b>{{ $moduleVersion->module->sdgs->pluck('name')->join(', ') }}</b></li>
                                </ul>

                            </div>
                        </div>
                    @endforeach

                </div>
            </li>
    </ul>
</div>
@endsection
