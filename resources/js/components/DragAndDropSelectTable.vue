<template>
    <b-row>
        <b-col cols="6" class="mr-0 pr-0">
            <b-card
                class="d-flex flex-column"
                bg-varant="light"
                border-variant="info"
            >
                <h4>Available {{ itemsName }}</h4>
                <p>
                    <slot name="availableInfo">
                        Select {{ itemsName }} by dragging them into the select list on the left.
                    </slot>
                </p>
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <th scope="col" v-for="column in columns">{{ column }}</th>
                    </thead>
                    <draggable
                        tag="tbody"
                        :value="available"
                        :group="itemsName"
                        class="pb-5"
                    >
                        <tr
                            :variant="element.required ? 'light' : 'primary'"
                            v-for="element in available"
                            :key="element.id"
                        >
                            <slot name="listItem" v-bind:element="element">
                                {{ element.title || (element.label || 'item ID: ' + element.id) }}
                            </slot>
                        </tr>

                        <tr>
                            <td
                                v-if="available.length === 0"
                                :colspan="columns.length"
                                style="min-height: 15px" class="bg-white"
                            >
                                ~~ Drag items here to remove from list ~~
                            </td>
                        </tr>
                    </draggable>
                </table>
            </b-card>
        </b-col>
        <b-col cols="6" class="mr-0 pr-0">
            <b-card
                class="d-flex flex-column"
                bg-variant="light"
                border-variant="success"
            >
                <h4>Selected {{ itemsName }}</h4>
                <p>
                    <slot name="selectedinfo">Drag to reorder the {{ itemsName }}.</slot>
                </p>

                <table class="table table-striped">
                    <thead class="thead-dark">
                    <th scope="col" v-for="column in columns">{{ column }}</th>
                    </thead>

                    <draggable
                        tag="tbody"
                        :value="selected"
                        :group="itemsName"
                        @input="updateSelected"
                        class="pb-5"
                    >
                        <tr
                            :class="element.is_custom ? 'bg-info' : ''"
                            v-for="element in selected"
                            :key="element.id"
                        >
                            <slot name="listItem" v-bind:element="element">
                                {{ element.title || (element.label || 'item ID: ' + element.id) }}
                            </slot>
                        </tr>
                        <tr>
                            <td
                                v-if="selected.length === 0"
                                :colspan="columns.length"
                                style="min-height: 15px" class="bg-white"
                            >
                                ~~ Drag new items here ~~
                            </td>
                        </tr>
                    </draggable>
                </table>
                <slot name="addItemButton"></slot>
            </b-card>
        </b-col>
    </b-row>
</template>
<script>
import Draggable from "vuedraggable"

export default {
    name: 'drag-and-drop-select-table',
    components: {Draggable},
    props: {
        columns: {
            type: Array,
            default: () => [],
        },
        available: {
            type: Array,
            default: () => [],
        },
        selected: {
            type: Array,
            default: () => [],
        },
        itemsName: {
            type: String,
            default: 'items',
        },
    },
    methods: {
        // updateAvailable(available) {
        //     this.$emit('update:available', available);
        // },
        updateSelected(selected) {
            this.$emit('update:selected', selected);
        }

    }
}
</script>
