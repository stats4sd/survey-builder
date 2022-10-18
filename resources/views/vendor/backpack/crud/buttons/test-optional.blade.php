@if ($crud->hasAccess('update'))
    <a href="javascript:void(0)" onclick="testOptionalModule(this)" data-route="{{ url($crud->route.'/test-core') }}"
       data-module="{{ $entry->title }}"class="btn btn-info" data-button-type="test">
        Test module</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts')
    <script>
        if (typeof testOptionalModule != 'function') {

            function testOptionalModule(button) {
                // ask for confirmation before deleting an item
                // e.preventDefault();
                button = $(button);
                var route = button.attr('data-route');
                var module = button.attr('data-module');

                button.html('<div class="spinner-border spinner-border-sm" role="status"></div>Test module');

                $.ajax({
                    url: route,
                    type: 'POST',
                    success: function (result) {
                        console.log(result)
                        // Show an alert with the result
                        new Noty({
                            type: 'success',
                            text: result.data,
                            timeout: false,
                        }).show();

                        button.html('Test module');


                        if (typeof crud !== 'undefined') {
                            crud.table.draw(false);
                        }
                    },
                    error: function (result) {

                        // Show an alert with the result
                        button.html('Test module');
                        new Noty({
                            type: 'danger',
                            text: 'The current version of module ' + module + ' failed to compile: \n' + result.responseJSON.message,
                            timeout: false,
                        }).show();
                    }
                });
            }
        }

        // make it so that the function above is run after each DataTable draw event
        // crud.addFunctionToDataTablesDrawEventQueue('cloneEntry');
    </script>
@endpush
