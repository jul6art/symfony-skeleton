// any CSS you require will output into a single css file (app.css in this case)
import '../css/app.scss';

// bootstrap
import 'bootstrap';

// plugins
import 'bootstrap-notify';
import 'jquery-slimscroll';
import 'node-waves';
import 'blockui';
import 'bootstrap-select';
import 'jquery-cookie-bubble/js/cookieBubble';

// routing
window.Routing = require('./routing');

// theme
import './skeleton_script';
import './skeleton_custom';

// polyfill
import 'time-input-polyfill/auto';

$(document).ready(function () {
    $(document).trigger('dom.element.new', ['body']);
});

// import functions from other js file
// import {validationCaptchaCallback} from './form';

