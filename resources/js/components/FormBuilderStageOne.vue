<template>
    <div class="col-md-12">
        <h2 class="mb-3">Stage 1 - Create a Survey</h2>
        <b-form @submit.prevent="submit">
            <slot name="csrf"></slot>
            <div class="font-weight-bold mb-4" v-if="xlsformOriginal && xlsformOriginal.hasOwnProperty('project_name')">
                <b>Project: </b> {{ xlsform.project_name}}<br/>
                This form has been saved to this project. To change the project or the form name, please <a @click.prevent="destroy()" href="#">click here</a> to create a new form. Note this will reset your progress for this form.
            </div>

            <b-form-group
                v-if="projects.length > 0 && (!xlsformOriginal || !xlsformOriginal.hasOwnProperty('project_name'))"
                id="grp-project_name"
                label="Select project for this form:"
                label-for="project_name"
                :invalid-feedback="errors.project_name ? errors.project_name.join(', ') : ''"
                :state="!errors.project_name"
            >
                <b-form-select
                    v-model="xlsform.project_name"
                    :options="projects"
                    value-field="name"
                    text-field="name"
                    name="project_name"
                    :disabled="xlsformOriginal && !xlsformOriginal.hasOwnProperty('project_name')"
                />
            </b-form-group>


            <b-form-group
                v-if="!xlsformOriginal || !xlsformOriginal.hasOwnProperty('project_name')"
                id="grp-new_project"
                label="If you wish to create a new project, select 'new' in the dropdown above and enter the name here."
                label-for="project_name"
                :class="projects.length === 0 ? 'required' : ''"
                :invalid-feedback="errors.new_project_name ? errors.new_project_name.join(', ') : ''"
                :state="!errors.new_project_name"
            >
                <b-form-input
                    :disabled="(xlsform.project_name && xlsform.project_name!=='new') || !xlsform.project_name"
                    v-model="xlsform.new_project_name"
                    name="new_project_name"
                />
            </b-form-group>
            <b-form-group
                id="grp-title"
                label="Enter the form name:"
                label-for="name"
                class="required"
                :invalid-feedback="errors.name"
                :state="!errors.name"
            >
                <b-form-input
                    name="name"
                    v-model="xlsform.name"
                    required
                    :disabled="xlsformOriginal && xlsformOriginal.hasOwnProperty('name')"
                />
            </b-form-group>
            <b-alert variant="info" show>Please select the language(s) that should be available in your ODK
                form. All RHoMIS
                forms must have English as a language. You can also choose a default language. This should be
                the language
                your
                enumerators will use most often.
            </b-alert>
            <b-form-group
                id="grp-project_id"
                label="Select languages for this form"
                label-for="languages"
                :invalid-feedback="errors.languages"
                :state="!errors.languages"
            >
                <vSelect
                    name="languages"
                    v-model="xlsform.languages"
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
                :invalid-feedback="errors.default_language"
                :state="!errors.default_language"
            >
                <vSelect
                    name="default_language"
                    v-model="xlsform.default_language"
                    :options="languages.filter(lang => xlsform.languages.includes(lang.id))"
                    :reduce="language => language.id"
                    label="name"
                />
            </b-form-group>
            <b-alert :show="hasErrors" variant="danger">The form could not be saved. Please review the error messages
                above, and then resubmit.
            </b-alert>
            <b-button variant="primary" type="submit">Save and Continue</b-button>
        </b-form>
    </div>

</template>

<script>
import ThemeSelect from "./ThemeSelect";
import DragAndDropSelect from "./DragAndDropSelect";
import Draggable from "vuedraggable";
import vSelect from "vue-select";


export default {
    name: 'form-builder-stage-one',
    components: {
        DragAndDropSelect,
        ThemeSelect,
        Draggable,
        vSelect,
    },
    props: {
        projectsStart: {
            default: () => []
        },
        modules: {
            default: () => []
        },
        themes: {
            default: () => []
        },
        xlsformOriginal: {
            default: null
        },
        userId: {
            default: null
        },

        languages: {
            default: () => [],
        },
    },
    data() {
        return {
            projects: [],
            xlsform: {
                languages: ['en'],
                themes: [],
                modules: [],
                module_versions: [],
                project_name: null,
                new_project_name: null,
                name: "",
                default_language: "",
            },
            errors: {},
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
        },
        hasErrors() {
            return Object.keys(this.errors).length > 0;
        }
    },

    mounted() {

        // if creating (not editing) assign core modules to xlsform
        if (this.xlsformOriginal == null) {
            this.xlsform.modules = this.modules.filter(
                module => module.module.core === 1
            ).sort((a,b) => {
                return a.module.lft > b.module.lft
            });
        } else {
            this.xlsform = {...this.xlsformOriginal};
            this.xlsform.themes = this.xlsform.themes ? this.xlsform.themes.map(theme => theme.id) : []
            this.xlsform.module_versions = this.xlsform.modules ? this.xlsform.themes.map(moduleVersion => moduleVersion.id) : []
            this.xlsform.languages = this.xlsform.languages ? this.xlsform.languages.map(language => language.id) : []
        }

        this.projects = this.projectsStart
        this.projects.push({'name':'new'});
    },
    methods: {
        // Generic function to check if method should be store or update
        submit() {
            this.errors = {}

            this.xlsform.module_versions = this.xlsform.modules.map(
                module => module.id
            );

            if (this.xlsformOriginal && this.xlsformOriginal.hasOwnProperty('name')) {
                this.update();
            } else {
                this.store();
            }
        },
        store() {
            console.log("👍", this.xlsform);

            this.xlsform.user_id = this.userId;

            // prepare and send post request
            axios
                .post("/xlsform", this.xlsform)
                .then(res => {
                    console.log('ok', res.data);
                    window.location.assign("/xlsform/" + res.data.name + "/edit")
                })
                .catch(err => {
                    // check for validation error

                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                });

        },
        update() {

            axios.put("/xlsform/" + this.xlsform.name, this.xlsform)
                .then(res => {
                    console.log('ok', res.data);
                    window.location.assign("/xlsform/" + res.data.name + "/edit")
                })
                .catch(err => {
                    // check for validation error

                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        // destroys the current form and lets the user start again.
        // used for letting the user change the project or form name.
        destroy() {
            axios.delete("/xlsform/"+this.xlsform.name)
            .then(res => {
                window.location.assign("/xlsform/create")
            })
            .catch(err => {
                alert('This form could not be deleted. Please take a screenshot of this error message and contact the system administrator. Error: ' + err.message);
            })
        }
    }
};

// TODO: Handle post + put errors properly (show validation errors in right place, pass other errors to user)
// TODO: FIX Module ordering editing (ordering as extra variable in pivot table?)


</script>
