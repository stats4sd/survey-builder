<template>
    <b-card header="Step 1 - Core Info">
        <b-row>
            <b-alert variant="info">Please enter the key information for your project</b-alert>
            <b-col cols="6">
                <b-form-group
                    id="grp-project_id"
                    label="Select project for this form:"
                    label-for="project_id"
                    class="required"
                >
                    <b-form-select
                        :value="projectId"
                        @change="updateProjectId"
                        :options="projects"
                        value-field="id"
                        text-field="name"
                        name="project_id"
                        required
                    />
                </b-form-group>
                <b-form-group
                    id="grp-title"
                    label="Enter the form title:"
                    label-for="title"
                    class="required"
                >
                    <b-form-input
                        name="title"
                        :value="title"
                        @change="updateTitle"
                        required
                    />
                </b-form-group>
                <b-form-group
                    id="grp-project_id"
                    label="Which country/ies will this form be used in?"
                    label-for="countries"
                >
                    <vSelect
                        name="countries"
                        :value="selectedCountries"
                        @input="updateSelectedCountries"
                        :options="countries"
                        :reduce="country => country.id"
                        label="name"
                        multiple
                    />
                </b-form-group>
                <b-alert variant="info" show>Please select the language(s) that should be available in your ODK form. All RHoMIS forms must have English as a language. You can also choose a default language. This should be the language your enumerators will use most often.</b-alert>
                <b-form-group
                    id="grp-project_id"
                    label="Select languages for this form"
                    label-for="languages"
                >
                    <vSelect
                        name="languages"
                        :value="selectedLanguages"
                        @input="updateSelectedLanguages"
                        :options="languages"
                        :reduce="language => language.id"
                        label="name"
                        multiple
                    />
                </b-form-group>
                <b-form-group
                    id="grp-default_language"
                    label="Select the default language:"
                    label-for="default_language"
                    class="required"
                >
                    <b-form-select
                        :value="defaultLanguage"
                        @change="updateDefaultLanguage"
                        :options="selectedLanguages"
                        value-field="id"
                        text-field="name"
                        name="default_language"
                        required
                    />
                </b-form-group>
            </b-col>
        </b-row>
    </b-card>
</template>
<script>
import vSelect from "vue-select";

export default {
    name: 'core-form-info',
    components: {
        vSelect,
    },
    props: {
        projects: {},
        title: '',
        projectId: null,
        countries: {
            default: () => [],
        },
        selectedCountries: {
            default: () => [],
        },
        languages: {
            default: () => [],
        },
        selectedLanguages: {
            default: () => [],
        },
        defaultLanguage: '',
    },
    emits: ['update:title', 'update:project_id', 'update:countries', 'update:languages', 'update:defaultLanguage', 'update:selectedLanguages', 'update:selectedCountries'],
    methods: {
        updateTitle(title) {
            this.$emit('update:title', title);
        },
        updateProjectId(projectId) {
            this.$emit('update:projectId', projectId);
        },
        updateSelectedCountries(selectedCountries) {
            this.$emit('update:selectedCountries', selectedCountries);
        },
        updateSelectedLanguages(selectedLanguages) {
            this.$emit('update:selectedLanguages', selectedLanguages);
        },
        updateDefaultLanguage(defaultLanguage) {
            this.$emit('update:defaultLanguage', defaultLanguage);
        },
    }
}
</script>
