<template>
    <div class="px-5">
        <h2 class="d-flex align-items-center">
            <HelpLink section="building-a-survey" heading="reviewing-your-questionnaire"/>
            Review the form and deploy to ODK
        </h2>
        <hr/>

        <!--    Overall metrics: time for survey; no. of questions; no of optional modules added -->
        <h4>Form Contents</h4>
        <b-row>
            <b-col lg="6" cols="12">
                <b-list-group>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">Modules:</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ xlsform.modules.length }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">Optional Modules:</b-col>
                        <b-col cols="3" class="font-weight-bold">
                            {{ xlsform.modules.filter(module => module.module.core === 0).length }}
                        </b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">Estimated Total Time:</b-col>
                        <b-col cols="3" class="font-weight-bold">
                            {{ xlsform.modules.reduce((carry, module) => carry + module.module.minutes, 0) }} minutes
                        </b-col>
                    </b-list-group-item>
                    <b-list-group-item v-if="xlsform.draft || xlsform.complete" class="d-flex">
                        <b-col cols="6" class="text-right">Try out the Form in ODK Collect:</b-col>
                        <b-col cols="6" class="font-weight-bold">
                            <a
                                :href="rhomisAppUrl+'/#/projects/'+xlsform.project_name+'/forms/'+xlsform.name+'/collect'"
                                class="btn btn-link"
                            >
                                <i class="la la-spinner la-spin" v-if="processing"></i>
                                Collect data
                            </a>
                        </b-col>
                    </b-list-group-item>
                    <b-list-group-item v-if="xlsform.download_url" class="d-flex">
                        <b-col cols="6" class="text-right">Review the ODK Form in Excel:</b-col>
                        <b-col cols="6" class="font-weight-bold">
                            <a
                                :href="!processing ? xlsform.download_url : ''"
                                class="btn btn-link"
                            >
                                <i class="la la-spinner la-spin" v-if="processing"></i>
                                Download XLS Form
                            </a>
                        </b-col>
                    </b-list-group-item>
                </b-list-group>
            </b-col>
        </b-row>

        <!--    Module list     -->
        <b-row class="my-4">
            <b-col>
                <b-button v-b-toggle.moduleList varient="info">Show full list of modules</b-button>
                <b-collapse id="moduleList" name="moduleList">
                    <b-table :items="xlsform.modules" :fields="moduleFields"/>
                </b-collapse>
            </b-col>
        </b-row>
        <hr/>

        <!-- Locations: count of countries, regions, subregions and villages. (Maybe list in collapsible?) -->
        <h4>Location Information</h4>
        <b-alert v-if="!xlsform.location_file_url" variant="warning" class="text-dark" show>
            You have not uploaded any location information for the form. Please make sure you do so on the <a
            :href="'/xlsform/'+xlsform.name+'/edit-three'">Stage 3 page</a> before finalising the survey.
        </b-alert>
        <b-row class="my-4">
            <b-col lg="6" cols="12">
                <b-list-group>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">No. of Countries:</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ countryCount }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">{{ xlsform.region_label.en }} count:</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ regionCount }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">{{ xlsform.subregion_label.en }} count:</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ subregionCount }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">{{ xlsform.village_label.en }} count:</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ villageCount }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex" v-if="xlsform.has_household_list">
                        <b-col cols="6" class="text-right">No. of Households:</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ householdCount }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex" v-else>
                        This form does not a pre-defined list of households. Household id entry will be via number or
                        free-text field entry.
                    </b-list-group-item>
                </b-list-group>
            </b-col>
        </b-row>


        <!-- List of customised lists. Comment that all other 'customisable' lists will take the default options, but it's recommended to explicitly choose the options... -->
        <h4>Custom Response Option Lists</h4>
        <p>Response option lists that require customisation are below. Any lists not yet customised appear highlighted
            in red.</p>
        <b-row class="my-4">
            <b-col lg="6" cols="12">
                <b-list-group>
                    <b-list-group-item class="d-flex">
                        <b-col cols="6" class="text-right">List</b-col>
                        <b-col cols="3" class="font-weight-bold">No. of choices</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex font-weight-bold">UNITS</b-list-group-item>
                    <b-list-group-item
                        class="d-flex"
                        v-for="row in selectedUnitChoicesRows"
                        v-if="!['Country', 'region', 'subregion', 'village', 'household'].includes(row[0].list_name)">
                        <b-col cols="6" class="text-right">{{ row[0].list_name }}</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ row.length }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex text-danger" v-for="row in unselectedUnitChoicesLists"
                                       v-if="!isLoading">
                        <b-col cols="6" class="text-right">{{ row.list_name }}</b-col>
                        <b-col cols="3" class="font-weight-bold">0</b-col>
                    </b-list-group-item>

                    <b-list-group-item class="d-flex font-weight-bold">OTHER LISTS</b-list-group-item>
                    <b-list-group-item
                        class="d-flex"
                        v-for="row in selectedOtherChoicesRows"
                        v-if="!['Country', 'region', 'subregion', 'village', 'household'].includes(row[0].list_name)">
                        <b-col cols="6" class="text-right">{{ row[0].list_name }}</b-col>
                        <b-col cols="3" class="font-weight-bold">{{ row.length }}</b-col>
                    </b-list-group-item>
                    <b-list-group-item class="d-flex text-danger" v-for="row in unselectedOtherChoicesLists"
                                       v-if="!isLoading">
                        <b-col cols="6" class="text-right">{{ row.list_name }}</b-col>
                        <b-col cols="3" class="font-weight-bold">0</b-col>
                    </b-list-group-item>

                    <div v-if="isLoading"><span class="spinner-border-sm"></span> Loading</div>
                </b-list-group>
            </b-col>
        </b-row>

        <!-- option to finalise the form -->
        <b-button variant="primary" @click="finalise">Finalise and Publish Form</b-button>

    </div>
</template>

<script>
import Noty from "noty";
import Swal from "sweetalert2";
import axios from "axios";

export default {
    name: "FormBuilderStageFour",

    props: {
        xlsformOriginal: {
            default: () => [],
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
            processing: false,
            building: false,
            deploying: false,
            complete: false,
            needRelogin: false,
            moduleFields: [
                {
                    key: 'module.title',
                    label: 'title',
                },
                {
                    key: 'version_name',
                    label: 'version',
                },
                {
                    key: 'module.minutes',
                    label: 'Estimated time (minutes)',
                },
            ],
            selectedChoicesRows: {},
            choiceLists: [],
            isLoading: false,
        }
    },
    computed: {
        selectedOtherChoicesRows() {
            let nonLocationRows = {}
            let locationKeys = ['Countries', 'regions', 'subregions', 'villages', 'households'];
            let unitKeys = this.choiceLists.filter(list => list.is_units === 1).map(list => list.list_name)

            console.log('unitKeys', unitKeys);

            Object.keys(this.selectedChoicesRows).forEach(key => {
                if (!locationKeys.includes(key) && !unitKeys.includes(key)) {
                    nonLocationRows[key] = this.selectedChoicesRows[key] ?? null;
                }
            })

            return nonLocationRows;
        },
        selectedUnitChoicesRows() {
            let unitRows = {}
            let unitKeys = this.choiceLists.filter(list => list.is_units === 1)

            Object.keys(this.selectedChoicesRows).forEach(key => {
                if (unitKeys.includes(key)) {
                    unitRows[key] = this.selectedChoicesRows[key] ?? null;
                }
            })

            return unitRows
        },
        unselectedUnitChoicesLists() {
            return this.choiceLists
                .filter(list => list.is_units === 1)
                .filter(list => !Object.keys(this.selectedChoicesRows).includes(list.list_name))

        },
        unselectedOtherChoicesLists() {
            return this.choiceLists
                .filter(list => list.is_units === 0 && list.is_locations === 0)
                .filter(list => !Object.keys(this.selectedChoicesRows).includes(list.list_name))
        },
        countryCount() {
            return this.selectedChoicesRows.hasOwnProperty('Country') ? this.selectedChoicesRows.Country.length : 0;
        },
        regionCount() {
            return this.selectedChoicesRows.hasOwnProperty('region') ? this.selectedChoicesRows.region.length : 0;
        },
        subregionCount() {
            return this.selectedChoicesRows.hasOwnProperty('subregion') ? this.selectedChoicesRows.subregion.length : 0;
        },
        villageCount() {
            return this.selectedChoicesRows.hasOwnProperty('village') ? this.selectedChoicesRows.village.length : 0;
        },
        householdCount() {
            return this.selectedChoicesRows.hasOwnProperty('household') ? this.selectedChoicesRows.household.length : 0;
        },

    },

    mounted() {
        this.xlsform = {...this.xlsformOriginal};
        this.xlsform.modules = this.xlsform.allModules;
        this.xlsform.module_versions = this.xlsform.allModuleVersions;
        this.xlsform.module_versions = this.xlsform.module_versions ? this.xlsform.module_versions.map(moduleVersion => moduleVersion.id) : []


        this.xlsform.moduleVersions = this.xlsform.modules.map(moduleVersion => moduleVersion.id);

        this.xlsform.region_label = this.xlsform.region_label ? JSON.parse(this.xlsform.region_label) : {"en": "region"}
        this.xlsform.subregion_label = this.xlsform.subregion_label ? JSON.parse(this.xlsform.subregion_label) : {"en": "subregion"}
        this.xlsform.village_label = this.xlsform.village_label ? JSON.parse(this.xlsform.village_label) : {"en": "village"}

        // if file exists, put string of file path into location_file_original;
        if (this.xlsform.location_file instanceof String) {
            this.xlsform.location_file_original = this.xlsform.location_file;
            this.xlsform.location_file = ""
        }

        this.xlsform.selectedChoicesRows.forEach(row => {

            let updatedList = [...this.selectedChoicesRows[row.list_name] ?? []]
            updatedList.push(row);

            this.$set(this.selectedChoicesRows, row.list_name, updatedList);
        })

        this.isLoading = true;
        axios.get('/localisable-lists/')
            .then(res => {
                console.log('lists got', res);
                this.choiceLists = res.data;

                this.isLoading = false;

            });


        this.setupListeners();
    },
    methods: {
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
                    if (payload.code == 401 || payload.code == 419) {

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
                    if (payload.code == 401 || payload.code == 419) {

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
                .listen("FinaliseXlsFormComplete", payload => {
                    this.reset()

                    if (payload.code == 401 || payload.code == 419) {
                        this.needRelogin = true;
                        new Noty({
                            type: "error",
                            text: "Sorry - it looks like your session has timed out. Your form has been saved. To continue, please the link at the top of the page to re-login.",
                            timeout: false,
                        }).show();
                    } else {

                        new Noty({
                            type: "success",
                            text: `Your Survey has been successfully published. It is now live and ready to be used for live data collection.`,
                            timeout: false,
                        }).show()

                        this.xlsform.complete = 1;
                        this.xlsform.draft = 0;

                    }
                })
        },
        finalise() {

            Swal.fire({
                title: 'Are you sure?',
                html: 'If this form is already live, this will update the live version with your latest edits. Otherwise, the form will be published and become ready for live data collection. Further edits to this form will be to a new draft version, and will not affect the published version until you re-publish the draft from here.',
                showCancelButton: true,
                confirmButtonText: `Yes - publish the form ${this.xlsform.name}`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.post("/xlsform/" + this.xlsform.name + "/finalise")
                        .then(res => {
                            new Noty({
                                'type': 'info',
                                'text': 'Your Survey is being finalised and deployed...',
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
            })


        }
    }


}
</script>

<style scoped>

</style>
