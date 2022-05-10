<template>
    <b-card class="border-0" header-bg-variant="dark" header-text-variant="white">
        <template #header>
            <h4 class="mb-0">Editing Form {{ xlsform.name }}</h4>
        </template>
        <b-list-group flush>
            <b-list-group-item class="d-flex justify-content-start align-items-center">
                <span class="w-50 w-lg-25">Project Name:</span>
                <b>{{ xlsform.project_name }}</b>
            </b-list-group-item>
            <b-list-group-item class="d-flex justify-content-start align-items-center">
                <span class="w-50 w-lg-25">Form Name:</span>
                <b>{{ xlsform.name }}</b>
            </b-list-group-item>
            <b-list-group-item class="d-flex justify-content-start align-items-center">
                <span class="w-50 w-lg-25">Default Language:</span>
                <b>{{ xlsform.default_language }}</b>
            </b-list-group-item>
            <b-list-group-item class="d-flex justify-content-start align-items-center">
                <span class="w-50 w-lg-25">Languages:</span>
                <b>{{ xlsform.languages.map(language => language.name).join(', ') }}</b>
            </b-list-group-item>
            <b-list-group-item class="d-flex justify-content-start align-items-center">
                <span class="w-50 w-lg-25">Countries:</span>
                <b>{{ xlsform.countries.map(country => country.name).join(', ') }}</b>
            </b-list-group-item>
        </b-list-group>
        <template #footer>
            <a v-if="xlsform.draft || xlsform.complete"
               :href="rhomisAppUrl+'/#/projects/'+xlsform.project_name+'/forms/'+xlsform.name" class="btn btn-success">View
                Xlsform in RHoMIS App</a>
        </template>
    </b-card>
</template>

<script>
export default {
    name: "FormKeyDetailsView",
    props: {
        'xlsform': {
            default: () => {},
        },
        'rhomisAppUrl': {
            default: "",
        },
    },
}
</script>

<style scoped>

</style>
