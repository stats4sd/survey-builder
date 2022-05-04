gs
<template>
    <b-card header="Step 1 - Customise Locations" header-tag="h4" border-variant="dark" bg-variant="light">
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
                <h5>Labels for {{ lang.name }}</h5>
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

        <hr/>

        <b-form-group
            id="household-list-yn-grp"
            label="Do you have a pre-defined list of households for enumerators to choose from in the survey?"
            description="If yes, you will be asked to upload the list of households to be included in the form."
            label-for="household-list-yn"
            class="required"
        >
            <b-form-radio-group
                id="household-list-yn"
                name="household-list-yn"
                :value="hasHouseholdList"
                :options="ynList"
                @change="updateHasHouseholdLIst($event)"
            />
        </b-form-group>

        <h5>Upload Location Information for Choice Lists</h5>
        <div v-if="hasHouseholdList">Please upload your location information as a .csv file. It should include <b>1 row
            per household</b>.<br/> You may use <a href="/storage/location-template-with-households.csv">this template</a> as
            a guide.
        </div>
        <div v-if="!hasHouseholdList">Please upload your location information as a .csv file. It should include <b>1 row
            per village</b>.<br/> You may use <a href="/storage/location-template.csv">this template</a> as a guide.
        </div>

        <b-form-file
            class="mt-3"
            :value="locationFile"
            :state="Boolean(locationFile)"
            placeholder="Choose a file or drop it here..."
            drop-placeholder="Drop file here..."
            @input="updateLocationFile($event)"
        ></b-form-file>
        <div class="mt-3">Selected file: {{ locationFile ? locationFile.name : '' }}</div>

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
        hasHouseholdList: {
            default: false,
        },
        locationFile: {
            default: null,
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

        updateHasHouseholdLIst(hasHouseholdList) {
            this.$emit('update:hasHouseholdList', hasHouseholdList);
        },
        updateLocationFile(locationFile) {
            this.$emit('update:locationFile', locationFile);
        },
    },
    data() {
        return {
            ynList: [
                {text: 'Yes', value: true},
                {text: 'No', value: false},
            ],
        }
    }
}
</script>
