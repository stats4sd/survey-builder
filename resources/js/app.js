import axios from 'axios';
import _ from 'lodash';


window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
