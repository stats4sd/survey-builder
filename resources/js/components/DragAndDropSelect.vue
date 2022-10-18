<template>
    <b-row>
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
            <div style="height:75vh; overflow-y: scroll">

                <draggable
                    id="available-modules-draggable-list"
                    class="list-group flex-grow-1"
                    :value="available"
                    :group="itemsName"

                >
                    <b-list-group-item
                        :variant="element.required ? 'light' : 'primary'"
                        v-for="element in available"
                        :key="element.id"
                    >
                        <slot name="listItem" v-bind:element="element">
                            {{ element.title || (element.label || 'item ID: ' + element.id) }}
                        </slot>
                    </b-list-group-item>
                    <b-list-group-item
                        v-if="available.length === 0"
                        style="min-height: 15px" class="bg-white"
                    >
                        ~~ Drag items here to remove from list ~~
                    </b-list-group-item>
                </draggable>
            </div>
        </b-card>
        <b-card
            class="col-6 d-flex flex-column"
            bg-variant="light"
            border-variant="success"
        >
            <h4>Selected {{ itemsName }}</h4>
            <p>
                <slot name="selectedinfo">Drag to reorder the {{ itemsName }}.</slot>
            </p>

            <div style="height:75vh; overflow-y: scroll">

<!--                Include modules locked to the start of the survey -->
                <b-list-group>
                    <b-list-group-item
                        :class="element.module.core ? 'core ' : ''"
                        :variant="element.module.core ? 'light' : (element.module.locked_to_start || element.module.locked_to_end ? 'secondary' : 'primary')"
                        v-for="element in selected.filter((el) => el.module.locked_to_start === 1)"
                        :key="element.id"
                    >
                        <slot name="listItem" v-bind:element="element">
                            {{ element.title || (element.label || 'item ID: ' + element.id) }}
                        </slot>
                    </b-list-group-item>
                </b-list-group>
                <draggable
                    id="selected-modules-draggable-list"
                    class="list-group flex-grow-1"
                    :value="selected"
                    :group="itemsName"
                    @input="updateSelected"
                    @end="sendCoreRemoveFailMessage"
                    :move="preventCore"
                >
                    <b-list-group-item
                        :class="element.module.core ? 'core ' : ''"
                        :variant="element.module.core ? 'light' : (element.module.locked_to_start || element.module.locked_to_end ? 'secondary' : 'primary')"
                        v-for="element in selected.filter(el => el.module.locked_to_start !== 1 && el.module.locked_to_end !== 1)"
                        :key="element.id"
                    >
                        <slot name="listItem" v-bind:element="element">
                            {{ element.title || (element.label || 'item ID: ' + element.id) }}
                        </slot>
                    </b-list-group-item>
                    <b-list-group-item
                        v-if="selected.length === 0"
                        style="min-height: 15px" class="bg-white"
                    >
                        ~~ Drag new items here ~~
                    </b-list-group-item>
                </draggable>

<!--                END LOCKED ITEMS -->
                <b-list-group>
                    <b-list-group-item
                        :class="element.module.core ? 'core ' : ''"
                        :variant="element.module.core ? 'light' : (element.module.locked_to_start || element.module.locked_to_end ? 'secondary' : 'primary')"
                        v-for="element in selected.filter((el) => el.module.locked_to_end === 1)"
                        :key="element.id"
                    >
                        <slot name="listItem" v-bind:element="element">
                            {{ element.title || (element.label || 'item ID: ' + element.id) }}
                        </slot>
                    </b-list-group-item>
                </b-list-group>
            </div>
        </b-card>
    </b-row>
</template>
<script>
import Draggable from "vuedraggable"
import Noty from "noty"

export default {
    name: 'drag-and-drop-select',
    components: {Draggable},
    props: {
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
        },

        // prevent core modules from being removed from 'selected' list
        preventCore(e, oldE) {
            // if the module is a core module AND it's being dragged into the 'available' list:
            if (e.draggedContext.element.module.core === 1 && e.relatedContext.component.$attrs.id === "available-modules-draggable-list") {
                // setup callback to send message on mouse up
                this.sendCorePreventMessage = true;
                // prevent the drag
                return false;
            }

            // if the module is locked, prevent the drag
            if(e.draggedContext.element.module.locked_to_start) {
                return false;

            }

        },
        sendCoreRemoveFailMessage() {
            if (this.sendCorePreventMessage) {
                new Noty({
                    'type': 'error',
                    'text': 'You cannot remove the core modules from the survey.'
                }).show();
            }
            this.sendCorePreventMessage = false;
        }

    }
}
</script>
