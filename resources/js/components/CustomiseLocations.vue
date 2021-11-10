<template>
    <b-card header="Step 1 - Customise Locations">
        <b-row>
            <b-col cols="12">
                The RHoMIS metadata module allows 3 levels of location below
                country. Typically, these equate to "region", "subregion" and "village". These levels
                may be called by different names in different places (e.g. In Ethiopia, 'woreda' and
                'kebele' are used for 'region' and 'subregion'). <br/><br/>
                First, please enter your labels for each of the location levels:
                <br/><br/>
            </b-col>
        </b-row>
        <b-row v-for="lang in languages" :key="lang.id">
            <b-col class="col-12">
                <h4>Labels for {{ lang.name }}</h4>
            </b-col>
            <b-col class="col-lg-4 col-md-6 col-12">
                <b-form-group
                    id="grp-title"
                    label="Top level (just below country):"
                    label-for="regionLabel"
                    description="Use the singular (e.g. 'region', 'state')"
                    class="required"
                >
                    <b-form-input
                        name="regionLabel"
                        :value="regionLabel[lang.id]"
                        @change="updateRegionLabel($event, lang.id)"
                        required
                    />
                </b-form-group>
            </b-col>
            <b-col class="col-lg-4 col-md-6 col-12">
                <b-form-group
                    id="grp-title"
                    :label="'Mid-level (below '+regionLabel[lang.id]+'):'"
                    description="Use the singular (e.g. 'subregion', 'district')"
                    label-for="subregionLabel"
                    class="required"
                >
                    <b-form-input
                        name="subregionLabel"
                        :value="subregionLabel[lang.id]"
                        @change="updateSubregionLabel($event, lang.id)"
                        required
                    />
                </b-form-group>
            </b-col>
            <b-col class="col-lg-4 col-md-6 col-12">
                <b-form-group
                    id="grp-title"
                    label="Lowest level (equivalent to 'village'):"
                    description="Use the singular (e.g. 'village', 'community')"
                    label-for="villageLabel"
                    class="required"
                >
                    <b-form-input
                        name="villageLabels"
                        :value="villageLabel[lang.id]"
                        @change="updateVillageLabel($event, lang.id)"
                        required
                    />
                </b-form-group>
            </b-col>
        </b-row>
        <p class="text-danger font-weight-bold">Location entry - manual entry, copy-paste or upload csv in specific
            format?</p>
    </b-card>
</template>
<script>
export default {
    name: 'customise-locations',
    props: {
        languages: {
            default: () => [],
        },
        regionLabel: {
            default: () => {
            },
        },
        subregionLabel: {
            default: () => {
            }
        },
        villageLabel: {
            default: () => {
            }
        },
    },
    methods: {
        updateRegionLabel(regionLabel, lang) {
            let labels = {...this.regionLabel};
            labels[lang] = regionLabel
            this.$emit('update:regionLabels', labels);
        },
        updateSubregionLabel(subregionLabel, lang) {
            let labels = {...this.subregionLabel};
            labels[lang] = subregionLabel
            this.$emit('update:subregionLabel', subregionLabel);
        },
        updateVillageLabel(villageLabel, lang) {
            let labels = {...this.villageLabel};
            labels[lang] = villageLabel
            this.$emit('update:villageLabel', villageLabel);
        },
    }
}
</script>
