@if ($crud->hasAccess('update'))
	<a href="javascript:void(0)" onclick="buildEntry(this)" class="btn btn-sm btn-link" data-button-type="build"><i class="la la-copy"></i>Build Form</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
	if (typeof buildEntry != 'function') {
	  $("[data-button-type=clone]").unbind('click');

	  function buildEntry(button) {
        // ask for confirmation before deleting an item
        // e.preventDefault();
        var button = $(button);
        var route = button.attr('data-route');

        new Noty({
        type: "success",
        timeout: false,
        text: "Let's pretend this button did something, and now the survey XLSform has been generated.... onto the next part of the demo!",
        }).show();
      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('cloneEntry');
</script>
@if (!request()->ajax()) @endpush @endif