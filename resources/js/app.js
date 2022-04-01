import axios from 'axios';
import _ from 'lodash';

import bootstrap from 'bootstrap';

window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            document.body.classList.toggle('sb-sidenav-toggled');
        }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sidebar-lg-show');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sidebar-lg-show'));
        });
    }

});
