<template>
    <div class="mt-2">
        <div class="row justify-content-center">

            <div class="col-md-6 offset-3" v-if="needRelogin">
                <a
                    :href="rhomisAppUrl"
                    target="_blank"
                    class="btn btn-info"
                    :class="processing ? 'disabled' : ''"
                >
                    <i class="la la-spinner la-spin" v-if="processing"></i>
                    Re-authenticate with RHoMIS
                </a>
                <i class="las la-question-circle"
                   title="We are working on improving the authentication flow for users of Rhomis. During this beta period, you may occasionally be logged out of the main app while using the Survey Builder. Please re-authenticate to continue"></i>
                >Why
                is this needed?
            </div>
            <div class="col-md-12">
                <h2 class="mb-3">Stage 2 - Build the Survey</h2>
                <b-form @submit.prevent="submit">
                    <!-- ##################### STEP 2 ######################## -->
                    <b-alert show variant="link" class="text-danger"
                    >NOTE: "reduced" core versions are not currently
                        available in this demo
                    </b-alert
                    >

                    <!--  ################# STEP 3: MODULES #########################-->
                    <drag-and-drop-select
                        :startModules="xlsform.startModules"
                        :endModules="xlsform.endModules"
                        :available.sync="availableModules"
                        :selected.sync="xlsform.editableModules"
                        items-name="modules"
                    >
                        <template #selectedinfo>
                            Drag to re-order the modules. This is the order the modules will appear in the survey.
                        </template>
                        <template #availableInfo>
                            These are the available optional modules based on your chosen themes. Drag a module into the
                            left list to add it to your form.
                        </template>
                        <template #listItem="props">
                            <div @click="selectModalModule(props.element)">
                                <div class="d-flex">
                                    <span class="mr-2">{{ props.element.module.title }}</span>
                                    <span
                                        v-if="props.element.module.core"
                                        class="text-small"
                                    >
                                        (core)
                                    </span>
                                    <span
                                        v-if="props.element.module.locked_to_start || props.element.module.locked_to_end"
                                        class="text-small text-right flex-grow-1">
                                    (This element cannot be dragged)
                                </span>
                                    <br/>
                                </div>

                                <small>
                                    - Version: {{ props.element.version_name }} | {{ props.element.module.minutes }}
                                    minutes
                                </small>
                            </div>
                        </template>
                    </drag-and-drop-select>

                    <input
                        type="hidden"
                        :value="selectedModuleIds"
                        name="module_ids"
                    />

                    <b-button type="submit" variant="primary" :disabled="processing || complete"
                    >
                        <i class="la la-spinner la-spin" v-if="processing"></i>
                        Save Form
                    </b-button>
                </b-form>

                <hr/>
                <h4>Next Steps</h4>
                <div v-if="!xlsform.draft && !xlsform.complete" class="alert alert-info">Once you save the form, new
                    options will appear below.
                </div>
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <ul class="w-100">
                            <li v-if="xlsform.draft || xlsform.complete" class="d-flex align-items-center">
                                <span class="w-50 text-right mr-4">1. Test out the Form in ODK Collect:</span>
                                <a

                                    :href="rhomisAppUrl+'/#/projects/'+xlsform.project_name+'/forms/'+xlsform.name+'/collect/draft'"
                                    class="btn btn-link"
                                    :class="processing ? 'disabled' : ''"
                                >
                                    <i class="la la-spinner la-spin" v-if="processing"></i>
                                    Test Survey
                                </a>
                            </li>
                            <li v-if="xlsform.download_url && (xlsform.draft || xlsform.complete)"
                                class="d-flex align-items-center">
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
            <div class="col-md-6 offset-3" v-if="needRelogin">
                <a
                    :href="rhomisAppUrl"
                    target="_blank"
                    class="btn btn-info"
                    :class="processing ? 'disabled' : ''"
                >
                    <i class="la la-spinner la-spin" v-if="processing"></i>
                    Re-authenticate with RHoMIS
                </a>
                <i class="fas fa-question-circle"
                   title="We are working on improving the authentication flow for users of Rhomis. During this beta period, you may occasionally be logged out of the main app while using the Survey Builder. Please re-authenticate to continue">Why
                    is this needed?</i>
            </div>
        </div>


        <b-modal size="xl" id="module-modal" hide-footer scrollable
                 :title="modalModule ? modalModule.module.title : ''">
            <div v-if="modalModule">
                <h3>Module Summary</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex">
                        <span class="w-25 text-right pr-4">Indicators that can be calculated from this module:</span>
                        <span>{{ modalModule.indicator_list.join("\n") }}</span>
                    </li>
                </ul>

                <h3>XLS Form Questions:</h3>
                <b-table :fields="odkSurveyColumns" :items="modalModuleQuestions" :tbody-tr-class="questionRowClass">
                    <template #cell(is_localisable)="row">
                        <span
                            :class="row.item.is_localisable ? 'text-bold text-dark bg-warning w-100' : ''">{{
                                row.item.is_localisable ? 'Yes' : '-'
                            }}</span>
                    </template>
                </b-table>
            </div>
        </b-modal>
    </div>
</template>

<script>
import CoreFormInfo from "./CoreFormInfo";
import ThemeSelect from "./ThemeSelect";
import DragAndDropSelect from "./DragAndDropSelect";
import Draggable from "vuedraggable";
import Noty from "noty";

export default {
    components: {
        DragAndDropSelect,
        ThemeSelect,
        CoreFormInfo,
        Draggable,
    },
    props: {
        projects: {
            default: []
        },
        modules: {
            default: []
        },
        xlsformOriginal: {
            default: null
        },
        userId: {
            default: null
        },
        countries: {
            default: () => [],
        },
        languages: {
            default: () => [],
        },
        rhomisAppUrl: {
            default: "",
        }
    },
    data() {
        return {
            xlsform: {
                countries: [],
                languages: ['en'],
                themes: [],
                startModules: [],
                endModules: [],
                modules: [],
                editableModules: [],
                module_versions: [],
                project_id: null,
                title: "",
                default_language: "",
            },
            // is the form being processed in the background?
            processing: false,

            // stages of processing:
            building: false,
            deploying: false,
            complete: false,
            needRelogin: false,
            modalModule: null,
            modalModuleQuestions: null,
            odkSurveyColumns: [
                'type',
                'name',
                'english_label',
                {
                    key: 'is_localisable',
                    label: 'Can be customised to your context?',
                }

            ],
        };
    },
    computed: {
        availableModules() {
            return this.modules.filter(
                module =>
                    !this.xlsform.modules.some(xlsModule => xlsModule.id === module.id)
            );
        },
        selectedModuleIds() {
            return this.xlsform.modules.map(module => module.id);
        }
    },

    mounted() {


        // if creating (not editing) assign core modules to xlsform
        this.xlsform = {...this.xlsformOriginal};

        this.xlsform.themes = this.xlsform.themes ? this.xlsform.themes.map(theme => theme.id) : []
        this.xlsform.module_versions = this.xlsform.module_versions ? this.xlsform.module_versions.map(moduleVersion => moduleVersion.id) : []
        this.xlsform.countries = this.xlsform.countries ? this.xlsform.countries.map(country => country.id) : []
        this.xlsform.languages = this.xlsform.languages ? this.xlsform.languages.map(language => language.id) : []

        // handle modules
        this.xlsform.startModules = this.xlsform.modules.filter(module => module.module.locked_to_start === 1)
        this.xlsform.endModules = this.xlsform.modules.filter(module => module.module.locked_to_end === 1)
        this.xlsform.editableModules = this.xlsform.modules.filter(module => module.module.locked_to_start !== 1 && module.module.locked_to_end !== 1)

        this.setupListeners()
    },
    methods: {
        // Generic function to check if method should be store or update
        submit() {
            this.xlsform.module_versions = this.xlsform.modules.map(
                module => module.id
            );
            this.reset();
            this.processing = true;
            this.update();
        },
        update() {

            axios.put("/xlsform/" + this.xlsform.name, this.xlsform)
                .then(res => {
                    console.log('ok', res.data);
                    new Noty({
                        'type': 'info',
                        'text': 'Your survey form has been saved. The XLS file is now being built. Once complete it will be deployed to the RHoMIS ODK System.'
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

        selectModalModule(moduleVersion) {
            this.modalModule = moduleVersion
            this.$bvModal.show('module-modal')

            axios.get('/module-version/' + moduleVersion.id + '/get-details')
                .then(res => {
                    this.modalModuleQuestions = res.data.survey_rows;
                    this.modalModule = res.data;
                });
        },

        // get the row formatting for showing ODK survey questions
        questionRowClass(item, type) {
            if (!item || type !== 'row') return;

            switch (item.type) {
                case 'note':
                    return 'table-secondary';

                case 'begin repeat':
                case 'end repeat':
                    return 'table-warning';

                case 'begin group':
                case 'end group':
                    return 'table-primary';

            }

        }
    }
};

// TODO: Handle post + put errors properly (show validation errors in right place, pass other errors to user)
// TODO: FIX Module ordering editing (ordering as extra variable in pivot table?)


</script>
