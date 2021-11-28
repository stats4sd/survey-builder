import BootstrapVue from 'bootstrap-vue';
import Vue from 'vue';

import FormBuilderStageOne from "./components/FormBuilderStageOne"
import FormBuilderStageTwo from "./components/FormBuilderStageTwo"
import FormBuilderStageThree from "./components/FormBuilderStageThree"
import TestComponent from "./components/TestComponent"

Vue.component('TestComponent', TestComponent)
Vue.component('FormBuilderStageOne', FormBuilderStageOne)
Vue.component('FormBuilderStageTwo', FormBuilderStageTwo)
Vue.component('FormBuilderStageThree', FormBuilderStageThree)

Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app'
});
