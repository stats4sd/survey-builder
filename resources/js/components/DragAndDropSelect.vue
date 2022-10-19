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
                        v-for="element in startModules"
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
                    @end="checkAndSendFailMessage"
                    :move="preventCore"
                >
                    <b-list-group-item
                        :class="element.module.core ? 'core ' : ''"
                        :variant="element.module.core ? 'light' : 'primary'"
                        v-for="element in selected"
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
                        v-for="element in endModules"
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
        startModules: {
            type: Array,
            default: () => [],
        },
        endModules: {
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

            // if the drag breaks any 'requires_before' rules, prevent:
            console.log(e.relatedContext.list.map(item => item.module_id))
            console.log(e)

            let currentList = e.relatedContext.list.map(item => item.module)

            // remove item being dragged
            let currentDraggedItem = currentList.splice(e.draggedContext.index, 1)[0]

            let newBefore = currentList.slice(0, e.draggedContext.futureIndex)
            let newAfter = currentList.slice(e.draggedContext.futureIndex, currentList.length)

            // console.log('list:', currentList)
            // console.log('new before:', newBefore)
            // console.log('new after:', newAfter)
            // console.log('currentdrag:', currentDraggedItem)

            let check = true;

            // ensure that the dragged item is not 'requires_before' by any module that is now after it.
            newAfter.forEach(module => {
                console.log(module.requires_before)
                if(module.requires_before && module.requires_before.includes(currentDraggedItem.id)) {
                    this.orderValidationMessage = `The module ${module.title} must appear before ${currentDraggedItem.title} in the survey.`
                    this.sendOrderingValidationError = true
                    check = false;
                    return false
                }
            })

            if(!check) return false;

            // ensure that the dragged module is after any of it's own 'requires_before' modules.
            newBefore.forEach(module => {
                console.log(module.requires_before)
                if(currentDraggedItem.requires_before && currentDraggedItem.requires_before.includes(module.id)) {
                    this.orderValidationMessage = `The module ${currentDraggedItem.title} must appear before ${module.title} in the survey.`
                    this.sendOrderingValidationError = true
                    check = false;
                    return false;
                }
            })

            if(!check) return false;



            return true;

            },
        checkAndSendFailMessage() {
            if (this.sendCorePreventMessage) {
                new Noty({
                    'type': 'error',
                    'text': 'You cannot remove the core modules from the survey.'
                }).show();
                this.sendCorePreventMessage = false;
            }

            if(this.sendOrderingValidationError) {
                new Noty({
                    'type': 'error',
                    'text': this.orderValidationMessage
                }).show()
                this.sendOrderingValidationError = false;
            }
        }

    }
}
</script>
