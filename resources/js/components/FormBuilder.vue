<template>
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2>RHoMIS XLS Form Builder</h2>
                <div class="card border border-info">
                    <div class="card-header">Instructions</div>

                    <div class="card-body">
                        Instructions Go here
                    </div>
                </div>

                <b-form @onSubmit="submit">
                    <b-form-group
                        id="grp-project_id"
                        label="Select project"
                        label-for="project_id"
                    >
                        <b-form-select
                            v-model="xlsform.project_id"
                            :options="projects"
                            value-field="id"
                            text-field="name"

                        />
                    </b-form-group>


                </b-form>


            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            projects: {
                default: [],
            }
        },
        data() {
            return {
                xlsform: {},
            }
        },

        mounted() {
            this.getProjects();
        },
        methods: {
            getProjects() {
                axios.get()
            },

            submit() {
                console.log('ok');
            }
        }
    }
</script>
