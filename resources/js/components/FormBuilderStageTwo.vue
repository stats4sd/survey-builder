<template>
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="mb-3">Stage 1 - Build the Survey</h2>
                <b-form @submit.prevent="submit">
                    <!-- ##################### STEP 2 ######################## -->
                    <b-alert show variant="link" class="text-danger"
                    >NOTE: "reduced" core versions are not currently
                        available in this demo
                    </b-alert
                    >
                    <theme-select :themes="themes" :selectedThemes.sync="xlsform.themes"/>

                    <!--  ################# STEP 3: MODULES #########################-->
                    <drag-and-drop-select
                        :available.sync="availableModules"
                        :selected.sync="xlsform.modules"
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
                            {{ props.element.module.title }}
                            <span
                                v-if="props.element.module.core"
                                class="text-small"
                            >
                                (core)
                            </span><br/>
                            <small>
                            - Version: {{ props.element.version_name }}
                            </small>
                        </template>
                    </drag-and-drop-select>

                    <input
                        type="hidden"
                        :value="selectedModuleIds"
                        name="module_ids"
                    />
                    <b-form-group class="d-flex">
                        <b-button type="submit" variant="primary" :disabled="processing"
                        >
                            <i class="la la-spinner la-spin" v-if="processing"></i>
                            Save Form
                        </b-button>
                        <span v-if="processing" :class="building ? 'text-secondary' : ''">Your form is being saved...</span>
                        <span v-if="processing" :class="deploying ? 'text-secondary' : ''" class="ml-2">...your XLSX file is being generated...</span>
                        <span v-if="deploying" :class="complete ? 'text-secondary' : ''" class="ml-2">...your form is being deployed...</span>
                        <span v-if="complete" class="ml-2">...success!</span>
                        <a v-if="xlsform.download_url" :href="xlsform.download_url" class="btn btn-primary" >Download XLS Form File</a>
                        <a v-if="xlsform.draft || xlsform.complete" :href="rhomisAppUrl+'/#/projects/'+xlsform.project_name+'/forms/'+xlsform.name" class="btn btn-success">View Xlsform in RHoMIS App</a>

                    </b-form-group>
                </b-form>
            </div>
        </div>
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
        themes: {
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
                modules: [],
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
        };
    },
    computed: {
        availableModules() {
            return this.modules.filter(
                module =>
                    !this.xlsform.modules.some(xlsModule => xlsModule.id === module.id) &&
                    this.xlsform.themes.includes(module.module.theme_id)
            );
        },
        selectedModuleIds() {
            return this.xlsform.modules.map(module => module.id);
        }
    },

    mounted() {


        // if creating (not editing) assign core modules to xlsform
        if (this.xlsformOriginal == null) {
            this.xlsform.modules = this.modules.filter(
                module => module.module.core === 1
            );
        } else {
            this.xlsform = {...this.xlsformOriginal};
            this.xlsform.themes = this.xlsform.themes ? this.xlsform.themes.map(theme => theme.id) : []
            this.xlsform.module_versions = this.xlsform.module_versions ? this.xlsform.module_versions.map(moduleVersion => moduleVersion.id) : []
            this.xlsform.countries = this.xlsform.countries ? this.xlsform.countries.map(country => country.id) : []
            this.xlsform.languages = this.xlsform.languages ? this.xlsform.languages.map(language => language.id) : []
        }

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
            this.processing = this.building = this.deploying = this.complete = false;
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
                    text: "Your XLSX Form file has been built. It will now be deployed to the RHoMIS ODK Central Service as a draft form. <br/><br/>"+
                        "Once complete, you will be able to try the form in ODK Collect. You can also download the file to review locally using the link below.</a>.",
                    timeout: false,
                }).show();
            })
            .listen("DeployXlsFormComplete", payload => {

                this.reset();

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

                new Noty({
                    type: "error",
                    text: `Deploying your XLSform file failed with the following code and message:
                            Code: ${payload.code}
                            Message: ${payload.message}`,
                    timeout: false,
                }).show()
            })
            .listen("RhomisAPiCallDidFail", payload => {
                this.reset()

                new Noty({
                    type: "error",
                    text: `There was an error communicating with the RHoMIS API. Please forward the following information to your friendly IT administrator:
                            URL: ${payload.requestUrl}
                            Status: ${payload.code}
                            Message: ${payload.body}`,
                    timeout: false,
                }).show()
            })
        }

    }
};

// TODO: Handle post + put errors properly (show validation errors in right place, pass other errors to user)
// TODO: FIX Module ordering editing (ordering as extra variable in pivot table?)


</script>
