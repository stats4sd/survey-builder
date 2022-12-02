import BootstrapVue from 'bootstrap-vue';
import Vue from 'vue';
import VueEcho from 'vue-echo-laravel';

window.Pusher = require('pusher-js');


import FormBuilderStageOne from "./components/FormBuilderStageOne"
import FormBuilderStageTwo from "./components/FormBuilderStageTwo"
import FormBuilderStageThree from "./components/FormBuilderStageThree"
import FormBuilderStageFour from "./components/FormBuilderStageFour";
import FormKeyDetailsView from "./components/FormKeyDetailsView";
import HelpLink from "./components/HelpLink";

Vue.component('FormKeyDetailsView', FormKeyDetailsView)
Vue.component('FormBuilderStageOne', FormBuilderStageOne)
Vue.component('FormBuilderStageTwo', FormBuilderStageTwo)
Vue.component('FormBuilderStageThree', FormBuilderStageThree)
Vue.component('FormBuilderStageFour', FormBuilderStageFour)
Vue.component('HelpLink', HelpLink)

Vue.use(BootstrapVue);

Vue.use(VueEcho, {
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: process.env.MIX_PUSHER_HOST,
    wsPort: process.env.MIX_PUSHER_PROXY_PORT,
    wssPort: process.env.MIX_PUSHER_PROXY_PORT,
    disableStats: true,
    encrypted: true,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

const app = new Vue({
    el: '#app',
    data() {
        return {
            tabIndex: 3
        }
    },
});
