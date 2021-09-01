@include('crud::fields.inc.wrapper_start')

<h4>Create new version for all core modules</h4>
<span id="prv_version_name">

<div id="prv_version_info"></div>


</span>
@include('crud::fields.inc.wrapper_end')

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
<script>
    console.log('hello');

    function updateModuleInfo() {

        axios.get('/latest-core')
        .then(res => {
            var latest = res.data
            console.log('latest', latest);
            if(latest.length == 0) {
                document.getElementById('prv_version_info').innerHTML = `
                <div class="alert alert-info">
                    There are no existing Core versions. This will be the first version.
                </div>
                `
            }

            else {


                document.getElementById('prv_version_info').innerHTML = `
                <div class="alert alert-info">
                    Previous version name: <span class="text-bold">${core.latest_version_name}</span>
                </div>
                `
            }
        })
    }

    updateModuleInfo()

</script>


@endpush