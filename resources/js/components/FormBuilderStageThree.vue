<template>
    <div>
        <div class="container mt-2">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h2>Stage 2 - Customise to local context</h2>

                    <h4 class="my-2">Customising form: {{ xlsform.title }}</h4>

                    <customise-locations
                        :languages="xlsform.languages"
                        :region-label.sync="xlsform.region_label"
                        :subregion-label.sync="xlsform.subregion_label"
                        :village-label.sync="xlsform.village_label"
                    />
                        <!-- Not yet in use -->
                        <!-- <customise-questions></customise-questions>-->

                    <customise-lists
                        :languages="xlsform.languages"
                    ></customise-lists>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CustomiseLocations from "./CustomiseLocations";
import CustomiseQuestionText from "./CustomiseQuestionText";
import CustomiseLists from "./CustomiseLists";

export default {
    name: "FormBuilderStageTwo",
    components: {
        CustomiseLocations,
        CustomiseQuestionText,
        CustomiseLists,
    },
    props: {
        xlsformOriginal: {
            default: []
        },
        userId: {
            default: null,
        },
    },
    data() {
        return {
            xlsform: {
                countries: [],
                languages: ['en'],
                themes: [],
                modules: [],
                moduleVersions: [],
                project_id: null,
                title: "",
                default_language: "",
                region_label: "region",
                subregion_label: "subregion",
                village_label: "village",
            },
        };
    },
    mounted() {

        // at this stage, there should always be an xlsform to edit / update, so clone initial state ready for editing:
        this.xlsform = {...this.xlsformOriginal};
        this.xlsform.themes = this.xlsform.themes.map(theme => theme.id);
        this.xlsform.moduleVersions = this.xlsform.modules.map(moduleVersion => moduleVersion.id);

        this.xlsform.region_label = this.xlsform.region_label ?? { "en": "region" }
        this.xlsform.subregion_label = this.xlsform.subregion_label ?? { "en": "subregion"}
        this.xlsform.village_label = this.xlsform.village_label ?? { "en": "village"}

    },
}
</script>

