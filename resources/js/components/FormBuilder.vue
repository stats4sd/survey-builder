<template>
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2>RHoMIS XLS Form Builder</h2>
                <div class="card border border-info">
                    <div class="card-header">Instructions</div>
                    <div class="card-body">Instructions Go here</div>
                </div>

                <!-- ##################### STEP 1 ######################## -->
                <b-form @submit.prevent="submit">
                    <slot name="csrf"></slot>
                    <b-card header="Step 1 - Core Info">
                        <b-row>
                            <b-col cols="6">
                                <b-form-group
                                    id="grp-project_id"
                                    label="Select project for this form:"
                                    label-for="project_id"
                                    class="required"
                                >
                                    <b-form-select
                                        v-model="xlsform.project_id"
                                        :options="projects"
                                        value-field="id"
                                        text-field="name"
                                        name="project_id"
                                        required
                                    />
                                </b-form-group>
                                <b-form-group
                                    id="grp-title"
                                    label="Enter the form title:"
                                    label-for="title"
                                    class="required"
                                >
                                    <b-form-input
                                        name="title"
                                        v-model="xlsform.title"
                                        required
                                    />
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card>
                    <!-- ##################### STEP 2 ######################## -->
                    <b-alert show variant="link" class="text-danger"
                        >NOTE: "reduced" core versions are not currently
                        available in this demo</b-alert
                    >
                    <b-card header="Step 2 - Themes / Information Needs">
                        <b-row>
                            <b-alert show variant="info">
                                Every RHoMIS Survey includes the set of Core
                                Modules, which capture data required for a broad
                                set of indicators and allow comparison of your
                                project surveys with the anonymised results from
                                the complete RHoMIS dataset from over 30
                                countries.
                                <br /><br />
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
                                    <b-form-select
                                        name="themes"
                                        v-model="xlsform.themes"
                                        :options="themes"
                                        value-field="id"
                                        text-field="title"
                                        multiple
                                    />
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card>

                    <!-- ################# STEP 3: MODULES ######################### -->
                    <b-row>
                        <b-card
                            class="col-6 d-flex flex-column"
                            bg-variant="light"
                            border-variant="success"
                        >
                            <h4>Selected Modules</h4>
                            <p>
                                Drag to reorder the modules. This is the order
                                the modules will appear in the survey.
                            </p>

                            <draggable
                                class="list-group flex-grow-1"
                                :list="xlsform.modules"
                                group="modules"
                            >
                                <b-list-group-item
                                    :variant="
                                        element.module.core
                                            ? 'light'
                                            : 'primary'
                                    "
                                    v-for="element in xlsform.modules"
                                    :key="element.id"
                                >
                                    {{ element.module.title }}
                                    <span
                                        v-if="element.module.core"
                                        class="text-small"
                                    >
                                        (core)
                                    </span>
                                    - Version: {{ element.version_name }}
                                </b-list-group-item>
                            </draggable>
                        </b-card>
                        <b-card
                            class="col-6 d-flex flex-column"
                            bg-varant="light"
                            border-variant="info"
                        >
                            <h4>Available Modules</h4>
                            <p>
                                Select Modules by dragging them into the select
                                list on the left
                            </p>

                            <draggable
                                class="list-group flex-grow-1"
                                :list="availableModules"
                                group="modules"
                            >
                                <b-list-group-item
                                    :variant="
                                        element.module.core
                                            ? 'light'
                                            : 'primary'
                                    "
                                    v-for="element in availableModules"
                                    :key="element.id"
                                >
                                    {{ element.module.title }}
                                    <span
                                        v-if="element.module.core"
                                        class="text-small"
                                    >
                                        (core)
                                    </span>
                                    - Version: {{ element.version_name }}
                                </b-list-group-item>
                            </draggable>
                        </b-card>
                    </b-row>
                    <input
                        type="hidden"
                        :value="selectedModuleIds"
                        name="module_ids"
                    />
                    <b-form-group>
                        <b-button type="submit" variant="primary"
                            >Save Form</b-button
                        >
                    </b-form-group>
                </b-form>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from "vuedraggable";

    export default {
        components: {
            draggable
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
            }
        },
        data() {
            return {
                xlsform: {
                    themes: [],
                    modules: [],
                    moduleVersions: [],
                    project_id: null,
                    title: ""
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
                this.xlsform = { ...this.xlsformOriginal };
                this.xlsform.themes = this.xlsform.themes.map(theme => theme.id);
                this.xlsform.moduleVersions = this.xlsform.modules.map(moduleVersion => moduleVersion.id);
            }
        },
        methods: {
            // Generic function to check if method should be store or update
            submit() {
                if (this.xlsform.id) {
                    this.update(this.xlsformId);
                } else {
                    this.store();
                }
            },
            store() {
                console.log("👍", this.xlsform);
                this.xlsform.user_id = this.userId;

                this.xlsform.moduleVersions = this.xlsform.modules.map(
                    module => module.id
                );

                // prepare and send post request
                axios
                    .post("/admin/xlsform", this.xlsform)
                    .then(res => {
                        window.location = "/admin/xlsform"
                    })
                    .catch(err => {
                        if(err.message) {
                            alert("Save error - " + err.message)
                        }
                        console.log(err);
                    });

                // handle validation errors by showing errors + highlighting

                // on success, redirect user back to form list page.
            },
            update($id) {
                this.xlsform.moduleVersions = this.xlsform.modules.map(module => module.id);

                axios.put("/admin/xlsform/"+this.xlsform.id, this.xlsform)
                .then(res => console.log(res))
                .catch(err => console.log(err));
            }
        }
    };

// TODO: Handle post + put errors properly (show validation errors in right place, pass other errors to user)
// TODO: redirect user to form list after submit
// TODO: FIX Module ordering editing (ordering as extra variable in pivot table?)


</script>


