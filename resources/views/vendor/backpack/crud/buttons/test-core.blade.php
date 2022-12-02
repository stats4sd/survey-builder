@if ($crud->hasAccess('update'))
    <a href="javascript:void(0)" onclick="testCoreModules(this)" data-route="{{ url($crud->route.'/test-core') }}"
       class="btn btn-info" data-button-type="test">
        Test all Core Modules</a>

    <span id="test-form-download-spot"></span>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts')
    <script>
        if (typeof testCoreModules != 'function') {

            function testCoreModules(button) {
                // ask for confirmation before deleting an item
                // e.preventDefault();
                button = $(button);
                var route = button.attr('data-route');

                button.html('<div class="spinner-border spinner-border-sm" role="status"></div>Test all Core Modules');
                document.getElementById('test-form-download-spot').replaceChildren()


                $.ajax({
                    url: route,
                    type: 'POST',
                    success: function (result) {
                        console.log(result);
                        // Show an alert with the result
                        new Noty({
                            type: 'success',
                            text: result.message,
                            timeout: false,
                        }).show();

                        button.html('Test all Core Modules');


                        if (typeof crud !== 'undefined') {
                            crud.table.draw(false);
                        }
                    },
                    error: function (result) {

                        console.log(result)

                        let errors = result.responseJSON.errors ?? null;
                        let path = result.responseJSON.xlsform_path ?? null;
                        // Show an alert with the result
                        button.html('Test all Core Modules');
                        new Noty({
                            type: 'danger',
                            text: `The current core modules could not be turned into a viable ODK form. The following error occured:<br/>
                                    ${errors}<br/>
                                    Download the xlsform to review and identify the modules that need updating.`,
                            timeout: false,
                        }).show();

                        document.getElementById('test-form-download-spot').insertAdjacentHTML('afterbegin', `<a class="btn btn-warning text-dark" href="${path}" target="_blank">Download Test Form for debugging</a>`)
                        if (typeof crud !== 'undefined') {
                            crud.table.draw(false);
                        }

                    }
                });
            }
        }

        // make it so that the function above is run after each DataTable draw event
        // crud.addFunctionToDataTablesDrawEventQueue('cloneEntry');
    </script>
@endpush
