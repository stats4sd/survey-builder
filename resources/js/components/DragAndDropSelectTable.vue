<template>
    <b-row>
        <b-card
            class="col-6 d-flex flex-column"
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
                >
                    <tr
                        :variant="element.required ? 'light' : 'primary'"
                        v-for="element in selected"
                        :key="element.id"
                    >
                        <slot name="listItem" v-bind:element="element">
                            {{ element.title || (element.label || 'item ID: ' + element.id) }}
                        </slot>
                    </tr>
                </draggable>
            </table>
        </b-card>
        <b-card
            class="col-6 d-flex flex-column"
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
                </draggable>
            </table>
        </b-card>
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
