@if ($crud->hasAccess('update'))
	<a href="{{ url($crud->route . '/' . $entry->id . '/build') }}" class="btn btn-sm btn-link" data-button-type="build"><i class="la la-copy"></i>Build Form</a>
@endif
