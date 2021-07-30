@include('crud::fields.inc.wrapper_start')

<h4>Create new version for module: <span class="text-bold" id="prv_module_title"></span></h4>
<span id="prv_version_name">

<div id="prv_version_info"></div>


</span>
@include('crud::fields.inc.wrapper_end')

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
<script>
    console.log('hello');

    function updateModuleInfo() {
        var moduleId = document.querySelector('[name="module_id"]').value;
        axios.get('/module/'+moduleId)
        .then(res => {
            var module = res.data

            document.getElementById('prv_module_title').innerHTML = module.title

            if(module.module_versions.length == 0) {
                document.getElementById('prv_version_info').innerHTML = `
                <div class="alert alert-info">
                    Module has no existing versions. This will be the first version.
                </div>
                `
            }

            else {


                document.getElementById('prv_version_info').innerHTML = `
                <div class="alert alert-info">
                    Previous version name: <span class="text-bold">${module.latest_version_name}</span>
                </div>
                `
            }
        })
    }

    updateModuleInfo()

</script>


@endpush