<template>
    <div>

    </div>

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
        projectName: null,
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
    emits: ['update:title', 'update:project_id', 'update:countries', 'update:languages', 'update:defaultLanguage', 'update:selectedLanguages', 'update:selectedCountries', 'update:projectName'],

    methods: {
        updateTitle(title) {
            this.$emit('update:title', title);
        },
        updateProjectId(projectId) {
            this.$emit('update:projectId', projectId);
        },
        updateProjectName(projectName) {
            this.$emit('update:projectName', projectName);
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
