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
                            </span>
                            - Version: {{ props.element.version_name }}
                        </template>
                    </drag-and-drop-select>

                    <input
                        type="hidden"
                        :value="selectedModuleIds"
                        name="module_ids"
                    />
                    <b-form-group>
                        <b-button type="submit" variant="primary"
                        >Save Form
                        </b-button
                        >
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
            }
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
    },
    methods: {
        // Generic function to check if method should be store or update
        submit() {
            this.xlsform.module_versions = this.xlsform.modules.map(
                module => module.id
            );
            // At this point, should only ever be editing...
            // if (this.xlsform.id) {
                this.update();
            // } else {
            //    this.store();
            // }
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

            // handle validation errors by showing errors + highlighting

            // on success, redirect user back to form list page.
        },
        update() {

            axios.put("/xlsform/" + this.xlsform.name, this.xlsform)
                .then(res => {
                    console.log('ok', res.data);
                    new Noty({
                        'type': 'success',
                        'text': '<h4>Success!</h4> Your survey form has been saved. The XLS Form is now being built. Once complete you will see it in the main RHoMIS app.'
                    }).show();

                })
                .catch(err => {
                    // check for validation error

                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        }
    }
};

// TODO: Handle post + put errors properly (show validation errors in right place, pass other errors to user)
// TODO: FIX Module ordering editing (ordering as extra variable in pivot table?)


</script>

