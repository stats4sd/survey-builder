<!-- field_type_name -->
@include('crud::fields.inc.wrapper_start')

@php
$variant = $field['variant'] ?? 'info';
@endphp

@if(array_key_exists('title', $field))
    <h4 class="mb-2">{{ $field['title'] }}</h4>
@endif
@if(array_key_exists('content', $field))
    <div class="bd-callout bd-callout-{{ $variant }}">
        {!! $field['content'] !!}
    </div>
@endif
@include('crud::fields.inc.wrapper_end')

