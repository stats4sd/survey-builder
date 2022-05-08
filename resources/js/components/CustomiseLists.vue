<template>
    <div>
        <b-card header="Step 2 - Customise Response Option Lists" header-tag="h3" bg-variant="light"
                border-variant="dark">
            <b-row>
                <b-col cols="12">
                    Next, you must create the response options lists to be used in your survey. <br/><br/>
                    Each list has a set of options, created from all the previous RHoMIS surveys. Please build your
                    lists by
                    dragging existing items into your list. If you cannot find an item you need, you may create one, but
                    please check the main list first to avoid creating duplicate entries.
                    <br/><br/>
                </b-col>
            </b-row>

            <div v-if="isLoading" class="d-flex justify-content-middle align-items-center"><i
                class="la la-spinner la-spin"> </i> Loading
            </div>
            <div v-else>

                <h3>Customise Unit Choice Lists</h3>
                <b-row v-for="list in xlsChoicesUnits" :key="list.list_name">
                    <b-col cols="12">
                        <hr/>
                        <b-card no-body :border-variant="list.complete ? 'success' : 'info'"
                                :header-border-variant="list.complete ? 'success' : 'info'"
                                :header-class=" list.complete ? 'bg-light-success p-0' : 'p-0'">
                            <template #header>
                                <b-button v-b-toggle="'collapse-'+list.list_name" variant="link" class="w-100 p-2">
                                    <h4 class="text-dark p-0 mb-0">{{ list.list_name }}</h4>
                                </b-button>
                            </template>
                            <b-collapse :id="'collapse-'+list.list_name" class="mt-2">
                                <b-card-body>
                                    <div class="w-100 d-flex justify-content-between mb-2">
                                        <b-button variant="info" @click="selectAll(list.list_name)">Select All
                                        </b-button>
                                        <b-button variant="info" @click="deselectAll(list.list_name)">Deselect All
                                        </b-button>
                                    </div>
                                    <h5>Questions that use this list:</h5>
                                    <ul>
                                        <li v-for="question in list.survey_rows" :key="question.name">
                                            <b>{{ question.name }}</b>:
                                            {{ question.english_label }}
                                        </li>
                                    </ul>
                                    <drag-and-drop-select-table
                                        :columns="listTableColumns"
                                        :available="list.availableChoicesRows"
                                        :selected.sync="selectedChoicesRows[list.list_name]"
                                        items-name="option"
                                    >
                                        <template #listItem="props">
                                            <td>{{ props.element.name }}</td>
                                            <td v-for="lang in languages">{{
                                                    props.element['choices_labels_by_lang'][lang.id][0]['label']
                                                }}
                                            </td>
                                        </template>
                                    </drag-and-drop-select-table>
                                    <div class="d-flex justify-content-end">
                                        <b-button variant="info" @click="toggleComplete(list.list_name)">Mark as
                                            {{ list.complete ? 'Incomplete' : 'Complete' }}
                                        </b-button>
                                    </div>

                                </b-card-body>
                            </b-collapse>
                        </b-card>
                    </b-col>
                </b-row>

                <h3>Customise Other Choice Lists</h3>
                <b-row v-for="list in xlsChoicesOther" :key="list.list_name">
                    <b-col cols="12">
                        <hr/>
                        <b-card no-body :border-variant="list.complete ? 'success' : 'info'"
                                :header-border-variant="list.complete ? 'success' : 'info'"
                                :header-class=" list.complete ? 'bg-light-success p-0' : 'p-0'">
                            <template #header>
                                <b-button v-b-toggle="'collapse-'+list.list_name" variant="link" class="w-100 p-2">
                                    <h4 class="text-dark p-0 mb-0">{{ list.list_name }}</h4>
                                </b-button>
                            </template>
                            <b-collapse :id="'collapse-'+list.list_name" class="mt-2">
                                <b-card-body>
                                    <div class="w-100 d-flex justify-content-between mb-2">
                                        <b-button variant="info" @click="selectAll(list.list_name)">Select All
                                        </b-button>
                                        <b-button variant="info" @click="deselectAll(list.list_name)">Deselect All
                                        </b-button>
                                    </div>
                                    <h5>Questions that use this list</h5>
                                    <ul>
                                        <li v-for="question in list.survey_rows" :key="question.name">{{
                                                question.name
                                            }}:
                                            {{ question.english_label }}
                                        </li>
                                    </ul>
                                    <drag-and-drop-select-table
                                        :columns="listTableColumns"
                                        :available="list.availableChoicesRows"
                                        :selected.sync="selectedChoicesRows[list.list_name]"
                                        items-name="option"
                                    >
                                        <template #listItem="props">
                                            <td>{{ props.element.name }}</td>
                                            <td v-for="lang in languages">{{
                                                    props.element['choices_labels_by_lang'][lang.id][0]['label']
                                                }}
                                            </td>
                                        </template>
                                    </drag-and-drop-select-table>
                                    <div class="d-flex justify-content-end">
                                        <b-button variant="info" @click="toggleComplete(list.list_name)">Mark as
                                            {{ list.complete ? 'Incomplete' : 'Complete' }}
                                        </b-button>
                                    </div>

                                </b-card-body>
                            </b-collapse>
                        </b-card>
                    </b-col>
                </b-row>
            </div>
        </b-card>


    </div>
</template>

<script>
import DragAndDropSelectTable from "./DragAndDropSelectTable";
import axios from "axios";


export default {
    name: "CustomiseLists",
    components: {
        DragAndDropSelectTable,
    },
    props: {
        languages: {
            default: () => [],
        },
        selectedChoicesRowsOriginal: {
            default: () => [],
        }
    },
    data() {
        return {
            // object with key = list-name
            selectedChoicesRows: [],
            xlsChoicesLists: [],
            isLoading: false,
        }
    },
    watch: {
        selectedChoicesRows: function (val) {
            this.$emit('form-choice-rows', val);
        },
        selectedChoicesRowsOriginal: function (val) {
            // format current form's selected choices ready for editing
            this.selectedChoicesRows = [...this.selectedChoicesRowsOriginal];
            console.log(this.selectedChoicesRows);
            this.selectedChoicesRows = this.selectedChoicesRows.reduce((carry, choice) => {

                if (!carry.hasOwnProperty(choice.list_name)) {
                    carry[choice.list_name] = [];
                }

                // reformat selected_choices_labels into choices_labels to match the expected format:
                // choice.choices_labels = choice.selected_choices_labels;


                carry[choice.list_name].push(choice);

                return carry;
            }, {});


        }

    },

    computed: {
        xlsChoicesUnits() {
            return this.xlsChoicesLists.map(list => {
                if (list['is_localisable'] === 0 || list['is_units'] === 0) {
                    return null;
                }

                list.availableChoicesRows = list.choicesRows;

                // filter to show only non-selected entries
                if (this.selectedChoicesRows.hasOwnProperty(list.list_name)) {

                    list.availableChoicesRows = list.choicesRows.filter(item => {

                        // first check for items selected in this session
                        if (this.selectedChoicesRows[list.list_name].includes(item)) return false;

                        // then check for items preselected
                        if (this.selectedChoicesRows[list.list_name].find(selected => selected.xls_choices_rows_id === item.id)) return false;

                        return true
                    })
                }

                return list;
            }).filter(item => item !== null);
        },
        xlsChoicesOther() {
            return this.xlsChoicesLists.map(list => {

                if (list['is_localisable'] === 0 || list['is_locations'] || list['is_units']) {
                    return null;
                }

                list.availableChoicesRows = list.choicesRows;

                // filter to show only non-selected entries
                if (this.selectedChoicesRows.hasOwnProperty(list.list_name)) {

                    list.availableChoicesRows = list.choicesRows.filter(item => {

                        // first check for items selected in this session
                        if (this.selectedChoicesRows[list.list_name].includes(item)) return false;

                        // then check for items preselected
                        if (this.selectedChoicesRows[list.list_name].find(selected => selected.xls_choices_rows_id === item.id)) return false;

                        return true
                    })
                }

                return list;
            }).filter(item => item !== null)
        },
        listTableColumns() {
            let columns = ['name']
            this.languages.forEach(lang => columns.push('label - ' + lang.id));
            return columns;
        }
    },
    mounted() {
        // TODO: update this to load up the compiled_choices_rows for the current XLSform

        this.isLoading = true;

        axios.get('/xls-choices')
            .then(res => {
                console.log('lists got', res);
                this.xlsChoicesLists = res.data;

                // filter to only show localisable lists
                this.xlsChoicesLists = this.xlsChoicesLists.filter(list => list.is_localisable === 1);

                this.isLoading = false;

            });
    },
    methods: {
        selectAll(list_name) {
            this.$set(this.selectedChoicesRows, list_name, this.xlsChoicesLists.filter(list => list.list_name === list_name)[0].choicesRows);
        },
        deselectAll(list_name) {
            this.$set(this.selectedChoicesRows, list_name, []);
        },
        toggleComplete(list_name) {
            this.xlsChoicesLists = this.xlsChoicesLists.map((list) => {
                if (list.list_name === list_name) {

                    // check if any options are selected and return error if not
                    if (!this.selectedChoicesRows.hasOwnProperty(list_name) || this.selectedChoicesRows[list_name].length === 0) {
                        alert('You must select at least one option for the ' + list_name + ' list.');
                    } else {
                        list.complete = !list.complete;
                        // close collapse
                        if (list.complete) {
                            this.$root.$emit('bv::toggle::collapse', 'collapse-' + list_name)
                        }
                    }
                }
                return list;
            });
        },


    }
}
</script>

<style scoped>

</style>
