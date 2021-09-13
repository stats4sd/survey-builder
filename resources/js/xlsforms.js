import BootstrapVue from 'bootstrap-vue';
import Vue from 'vue';

import FormBuilder from "./components/FormBuilder"

Vue.component('FormBuilder', FormBuilder)
Vue.use(BootstrapVue);


const app = new Vue({
    el: '#app'
});
