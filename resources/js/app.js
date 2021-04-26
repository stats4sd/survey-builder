import axios from 'axios';
import _ from 'lodash';
import Vue from 'vue';

require('bootstrap');
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'


window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');

import ExampleComponent from "./components/ExampleComponent.vue"

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component(ExampleComponent);

new Vue({
    el: '#app',
});
