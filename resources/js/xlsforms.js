import BootstrapVue from 'bootstrap-vue';
import Vue from 'vue';
import VueEcho from 'vue-echo-laravel';

window.Pusher = require('pusher-js');


import FormBuilderStageOne from "./components/FormBuilderStageOne"
import FormBuilderStageTwo from "./components/FormBuilderStageTwo"
import FormBuilderStageThree from "./components/FormBuilderStageThree"
import TestComponent from "./components/TestComponent"


Vue.component('TestComponent', TestComponent)
Vue.component('FormBuilderStageOne', FormBuilderStageOne)
Vue.component('FormBuilderStageTwo', FormBuilderStageTwo)
Vue.component('FormBuilderStageThree', FormBuilderStageThree)

Vue.use(BootstrapVue);

Vue.use(VueEcho, {
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: process.env.MIX_PUSHER_HOST,
    wsPort: process.env.MIX_PUSHER_PORT,
    wssPort: process.env.MIX_PUSHER_PORT,
    disableStats: true,
    encrypted: true,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

const app = new Vue({
    el: '#app'
});
