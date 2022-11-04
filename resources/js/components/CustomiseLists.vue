<template>
    <div>
        <b-card bg-variant="light"
                border-variant="dark" no-body>
            <b-card-header>
                <h4 class="d-flex align-items-center    ">
                    <HelpLink section="building-a-survey" heading="choice-localisation"/>
                    Step 2 - Customise Response Option Lists
                </h4>
            </b-card-header>
            <b-card-body>
                <b-row>
                    <b-col cols="12">
                        Next, you must create the response options lists to be used in your survey. <br/><br/>
                        Each list has a set of options, created from all the previous RHoMIS surveys. Please build your
                        lists by
                        dragging existing items into your list. If you cannot find an item you need, you may create one,
                        but
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
                                        <div v-if="list.description">
                                            <h5>Description</h5>
                                            <p>{{ list.description }}</p>
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
                                            <template #addItemButton>
                                                <p>Custom items are highlighted in blue. To delete them, drag them to
                                                    the
                                                    "available" list - they will disappear.</p>
                                                <b-button class="mt-2" @click="createNewEntry(list.list_name)">+ Add New
                                                    Item
                                                </b-button>
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
                                        <div v-if="list.description">
                                            <h5>Description</h5>
                                            <p>{{ list.description }}</p>
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
                                                        props.element['choices_labels_by_lang'][lang.id] ?
                                                            props.element['choices_labels_by_lang'][lang.id][0]['label']
                                                            : '~undefined~'
                                                    }}
                                                </td>
                                            </template>
                                            <template #addItemButton>
                                                <p>Custom items are highlighted in blue. To delete them, drag them to
                                                    the
                                                    "available" list - they will disappear.</p>
                                                <b-button class="mt-2" @click="createNewEntry(list.list_name)">+ Add New
                                                    Item
                                                </b-button>
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
            </b-card-body>
        </b-card>

        <!-- new entry modal -->
        <b-modal title="Create new entry" id="newEntryForm" ok-title="Save" @ok="storeNewEntry(currentList)">
            <b-alert show variant="info">Create New entry for {{ currentList }}</b-alert>
            <b-form-group
                label="Enter the name of the entry:"
                description="The value must start with a letter and include no spaces."
            >
                <b-form-input v-model="newEntry.name"></b-form-input>
            </b-form-group>
            <b-form-group
                v-for="lang in languages"
                :key="lang.id"
                :label="'Enter the ' + lang.name + ' Label'"
                description="This is what the enumerators will see in the list">
                <b-form-input v-model="newEntry.choices_labels_by_lang[lang.id]"></b-form-input>
            </b-form-group>
        </b-modal>


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
        },
        xlsformName: '',
    },
    data() {
        return {
            // object with key = list-name
            selectedChoicesRows: [],
            xlsChoicesLists: [],
            isLoading: false,
            newEntry: {
                name: '',
                choices_labels_by_lang: {},
            },
            currentList: '',
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

        this.isLoading = true;
        console.log('hello there');
        axios.get('/xlsform/' + this.xlsformName + '/xls-choices')
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
                        // emit to main component to update formData
                        this.$emit('list-completion-updated', list);


                        // close collapse
                        if (list.complete) {
                            this.$root.$emit('bv::toggle::collapse', 'collapse-' + list_name)
                        }
                    }
                }
                return list;
            });
        },

        createNewEntry(list_name) {
            this.currentList = list_name;
            this.$bvModal.show('newEntryForm')
        },

        storeNewEntry(list_name) {

            let updateList = this.selectedChoicesRows[list_name];

            let newLabels = Object.keys(this.newEntry.choices_labels_by_lang).map(key => {
                return {
                    language_id: key,
                    label: this.newEntry.choices_labels_by_lang[key],
                }
            })

            let newLabelsByLang = {}

            Object.keys(this.newEntry.choices_labels_by_lang).forEach(key => {
                newLabelsByLang[key] = [{
                    language_id: key,
                    label: this.newEntry.choices_labels_by_lang[key],
                }]
            });

            updateList.push({
                list_name: list_name,
                name: this.newEntry.name,
                choices_labels_by_lang: newLabelsByLang,
                choices_labels: newLabels,
                is_custom: true,
            });

            this.$set(this.selectedChoicesRows, list_name, updateList);

            this.newEntry = {
                name: '',
                choices_labels_by_lang: {}
            }
        },
    }
}
</script>

<style scoped>

</style>
