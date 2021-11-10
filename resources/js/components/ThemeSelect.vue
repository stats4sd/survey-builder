<template>
    <b-card header="Step 2 - Themes / Information Needs">
        <b-row>
            <b-alert show variant="info">
                Every RHoMIS Survey includes the set of Core
                Modules, which capture data required for a broad
                set of indicators and allow comparison of your
                project surveys with the anonymised results from
                the complete RHoMIS dataset from over 30
                countries.
                <br/><br/>
                If you have specific needs that go beyond the
                core survey, select the specific themes you wish
                to investigate. Optional modules will appear
                below based on your selection.
            </b-alert>
            <b-col cols="6">
                <b-form-group
                    id="grp-project_id"
                    label="Select themes for this form:"
                    label-for="themes"
                >
                    <vSelect
                        name="themes"
                        :value="selectedThemes"
                        @input="updateSelectedThemes"
                        :options="themes"
                        :reduce="theme => theme.id"
                        label="title"
                        multiple
                    />
                </b-form-group>
            </b-col>
        </b-row>
    </b-card>
</template>
<script>
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';


export default {
    name: 'theme-select',
    components: {
        vSelect,
    },
    props: {
        themes: {},
        selectedThemes: {}
    },
    emits: ['update:selectedThemes'],
    methods: {
        updateSelectedThemes(selectedThemes) {
            this.$emit('update:selectedThemes', selectedThemes);
        },
    },
}
</script>
