<template>
    <b-card header="Step 2 - Customise Response Option Lists">
        <b-row>
            <b-col cols="12">
                Next, you must create the response options lists to be used in your survey. <br/><br/>
                Each list has a set of options, created from all the previous RHoMIS surveys. Please build your lists by
                dragging existing items into your list. If you cannot find an item you need, you may create one, but
                please check the main list first to avoid creating duplicate entries.
                <br/><br/>
            </b-col>
        </b-row>
        <b-row v-for="list in xlsChoicesOther" :key="list[0].list_name">
            <b-col cols="12">

                <hr/>
                <h4>{{ list[0]['list_name'] }}</h4>
                <drag-and-drop-select-table
                    :columns="listTableColumns"
                    :available="list"
                    :selected.sync="selectedChoicesRows[list[0]['list_name']]"
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
            </b-col>
        </b-row>
    </b-card>
</template>

<script>
import DragAndDropSelectTable from "./DragAndDropSelectTable";

export default {
    name: "CustomiseLists",
    components: {
        DragAndDropSelectTable,
    },
    props: {
        languages: {
            default: () => [],
        },
    },
    data() {
        return {
            'xlsChoicesLists': {},
            'selectedChoicesRows': {},
        }
    },
    computed: {
        xlsChoicesOther() {
            return Object.keys(this.xlsChoicesLists).map(key => {
                let list = this.xlsChoicesLists[key]
                if (list[0]['localisable'] === 0 || list[0]['list_type'] === 'location') {
                    return null;
                }

                // filter each list to only show 'available' entries (compare with the selectedChoiceRows
                if (this.selectedChoicesRows.hasOwnProperty(key)) {
                    return list.filter(item => !this.selectedChoicesRows[key].includes(item));
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
        console.log('hello from lists');
        axios.get('/xls-choices')
            .then(res => {
                console.log('lists got', res);
                this.xlsChoicesLists = res.data;
            });
    },
    methods: {}
}
</script>

<style scoped>

</style>
