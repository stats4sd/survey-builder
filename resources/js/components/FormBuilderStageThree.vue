<template>
    <div>
        <div class="mt-2">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h2 class="mb-3">Stage 3 - Customise to local context</h2>

                    <customise-locations
                        :languages="xlsform.languages"
                        :region-label.sync="xlsform.region_label"
                        :subregion-label.sync="xlsform.subregion_label"
                        :village-label.sync="xlsform.village_label"
                        :location-file.sync="xlsform.location_file"
                        :location-file-url="xlsform.location_file_url"
                        :location-file-name="xlsform.location_file_name"
                        :has-household-list.sync="xlsform.has_household_list"
                    />
                    <!-- Not yet in use -->
                    <!-- <customise-questions></customise-questions>-->

                    <customise-lists
                        :languages="xlsform.languages"
                        :selected-choices-rows-original="xlsform.selected_choices_rows"
                        @form-choice-rows="updateSelectedChoicesRows"
                    ></customise-lists>

                    <b-button variant="primary" @click.prevent="submit">Save Choice Lists and Build</b-button>
<!--                    <b-button variant="success" @click.prevent="build">Build Form</b-button>-->

                    <hr/>
                    <h4>Next Steps</h4>
                    <div v-if="!xlsform.draft && !xlsform.complete" class="alert alert-info">Once you save and build the form, new
                        options will appear below.
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-9">
                            <ul class="w-100">
                                <li v-if="xlsform.draft || xlsform.complete" class="d-flex align-items-center">
                                    <span class="w-50 text-right mr-4">1. Try out the Form in ODK Collect:</span>
                                    <a

                                        :href="rhomisAppUrl+'/#/projects/'+xlsform.project_name+'/forms/'+xlsform.name+'/collect'"
                                        class="btn btn-link"
                                        :class="processing ? 'disabled' : ''"
                                    >
                                        <i class="la la-spinner la-spin" v-if="processing"></i>
                                        Collect data
                                    </a>
                                </li>
                                <li v-if="xlsform.download_url" class="d-flex align-items-center">
                                    <span class="w-50 text-right mr-4">2. Review the ODK Form in Excel:</span>
                                    <a
                                        :href="!processing ? xlsform.download_url : ''"
                                        class="btn btn-link"
                                        :class="processing ? 'disabled' : ''"
                                    >
                                        <i class="la la-spinner la-spin" v-if="processing"></i>
                                        Download XLS Form
                                    </a>
                                </li>
                                <li v-if="xlsform.draft || xlsform.complete" class="d-flex align-items-center">
                                    <span class="w-50 text-right mr-4">3. Continue to Stage 3 - Customise your form:</span>
                                    <a
                                        :href="`/xlsform/${xlsform.name}/edit-three`"
                                        class="btn btn-primary"
                                        :class="processing ? 'disabled' : ''"
                                    >
                                        <i class="la la-spinner la-spin" v-if="processing"></i>
                                        Continue to Customise
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <div class="d-flex">
                        <span v-if="processing"
                              :class="building ? 'text-secondary' : ''">Your form is being saved...</span>
                        <span v-if="processing" :class="deploying ? 'text-secondary' : ''" class="ml-2">...your XLSX file is being generated...</span>
                        <span v-if="deploying" :class="complete ? 'text-secondary' : ''" class="ml-2">...your form is being deployed...</span>
                        <span v-if="complete" class="ml-2">...success!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CustomiseLocations from "./CustomiseLocations";
import CustomiseQuestionText from "./CustomiseQuestionText";
import CustomiseLists from "./CustomiseLists";
import Noty from "noty";


export default {
    name: "FormBuilderStageThree",
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
        rhomisAppUrl: {
            default: ''
        }
    },
    data() {
        return {
            xlsform: {
                countries: [],
                languages: [],
                themes: [],
                modules: [],
                moduleVersions: [],
                project_id: null,
                title: "",
                default_language: "",
                region_label: {"en": "region"},
                subregion_label: {"en": "subregion"},
                village_label: {"en": "village"},
                location_file: null,
                has_household_list: null,
                selected_choices_rows: [],
                location_file_url: "",
                location_file_name: "",
            },
            updatedSelectedChoicesRows: {},
            processing: false,
            building: false,
            deploying: false,
            complete: false,
            needRelogin: false,
        };
    },
    mounted() {

        // at this stage, there should always be an xlsform to edit / update, so clone initial state ready for editing:
        this.xlsform = {...this.xlsformOriginal};
        this.xlsform.themes = this.xlsform.themes.map(theme => theme.id);

        this.xlsform.module_versions = this.xlsform.modules ? this.xlsform.themes.map(moduleVersion => moduleVersion.id) : []

        // keep languages as an array of objects as that's the best format for this page.

        this.xlsform.moduleVersions = this.xlsform.modules.map(moduleVersion => moduleVersion.id);

        this.xlsform.region_label = this.xlsform.region_label ? JSON.parse(this.xlsform.region_label) : {"en": "region"}
        this.xlsform.subregion_label = this.xlsform.subregion_label ? JSON.parse(this.xlsform.subregion_label) : {"en": "subregion"}
        this.xlsform.village_label = this.xlsform.village_label ? JSON.parse(this.xlsform.village_label) : {"en": "village"}

        // if file exists, put string of file path into location_file_original;
        if (this.xlsform.location_file instanceof String) {
            this.xlsform.location_file_original = this.xlsform.location_file;
            this.xlsform.location_file = ""
        }

        this.setupListeners();

    },
    methods: {
        submit() {
            this.reset();
            this.processing = true;
            this.update();
        },

        update() {

            //setup FormData object to enable posting the file along with data
            var formData = new FormData();
            if (this.xlsform.location_file instanceof File) {
                formData.append("location_file", this.xlsform.location_file, 'locations.csv');
            }

            formData.append("region_label", JSON.stringify(this.xlsform.region_label));
            formData.append("subregion_label", JSON.stringify(this.xlsform.subregion_label));
            formData.append("village_label", JSON.stringify(this.xlsform.village_label));
            formData.append("has_household_list", this.xlsform.has_household_list);
            formData.append("selected_choices_rows", JSON.stringify(this.updatedSelectedChoicesRows));
            formData.append("_method", 'PUT');

            axios.post('/xlsform/' + this.xlsform.name + '/customise', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            })
                .then(res => {
                    console.log('save ok', res.data)
                    new Noty({
                        'type': 'info',
                        'text': 'Your survey form has been saved. The XLS file is now being built. Once complete it will be deployed to the RHoMIS ODK System.',
                        'timeout': false,
                    }).show();

                    this.building = true;
                })
                .catch(err => {
                    // check for validation error

                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }

                    this.reset()
                });
        },
        reset() {
            this.processing = this.building = this.deploying = this.complete = this.needRelogin = false;
        },
        // create listeners for Laravel Events
        setupListeners() {
            this.$echo
                .private("App.Models.User." + this.userId)
                .listen("BuildXlsFormComplete", payload => {
                    this.deploying = true;

                    this.xlsform.download_url = payload.xlsform.download_url

                    new Noty({
                        type: "info",
                        text: "Your XLSX Form file has been built. It will now be deployed to the RHoMIS ODK Central Service as a draft form. <br/><br/>" +
                            "Once complete, you will be able to try the form in ODK Collect. You can also download the file to review locally using the link below.</a>.",
                        timeout: false,
                    }).show();
                })
                .listen("DeployXlsFormComplete", payload => {

                    this.reset();

                    this.xlsform.draft = payload.xlsform.draft
                    this.xlsform.complete = payload.xlsform.complete

                    new Noty({
                        type: "success",
                        text: "Your XLSX form has been successfully deployed. Use the link below to get instructions on how to review the form in ODK Collect.",
                    }).show()
                })
                .listen("BuildXlsFormFailed", payload => {
                    this.reset()
                    console.log(payload)
                    new Noty({
                        type: "error",
                        text: `Building your XLSform file failed with the following code and message:
                            Code: ${payload.code}
                            Message: ${payload.message}`,
                        timeout: false,
                    }).show();
                })
                .listen("DeployXlsFormFailed", payload => {
                    this.reset()


                    // send reauth-message
                    if (payload.code == 401) {

                        this.needRelogin = true;
                        new Noty({
                            type: "error",
                            text: "Sorry - it looks like your session has timed out. Your form has been saved. To continue, please the link at the top of the page to re-login.",
                            timeout: false,
                        }).show();
                    } else {
                        new Noty({
                            type: "error",
                            text: `Deploying your XLSform file failed with the following code and message:
                            Code: ${payload.code}
                            Message: ${payload.message}`,
                            timeout: false,
                        }).show()
                    }
                })
                .listen("RhomisAPiCallDidFail", payload => {
                    this.reset()

                    // send reauth-message
                    if (payload.code == 401) {

                        this.needRelogin = true;
                        new Noty({
                            type: "error",
                            text: "Sorry - it looks like your session has timed out. Your form has been saved. To continue, please the link at the top of the page to re-login.",
                            timeout: false,
                        }).show();
                    } else {

                        new Noty({
                            type: "error",
                            text: `There was an error communicating with the RHoMIS API. Please forward the following information to your friendly IT administrator:
                            URL: ${payload.requestUrl}
                            Status: ${payload.code}
                            Message: ${payload.body}`,
                            timeout: false,
                        }).show()
                    }
                })
        },
        updateSelectedChoicesRows(val) {
            console.log(val)

            this.updatedSelectedChoicesRows = val;
        },

        build() {
            axios.post("/xlsform/" + this.xlsform.name + "/build")
                .then(res => {
                    new Noty({
                        'type': 'info',
                        'text': 'Your new XLS form is being built...',
                    }).show();
                })
                .catch(err => {
                    // check for validation error

                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }

                    this.reset()
                });
        }
    }
}
</script>
