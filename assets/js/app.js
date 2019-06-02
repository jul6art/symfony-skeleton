// any CSS you require will output into a single css file (app.css in this case)
import '../css/app.scss';

const $ = require('jquery');

// bootstrap
import 'bootstrap';

// plugins
import 'bootstrap-notify';
import 'sweetalert';
import 'jquery-slimscroll';
import 'node-waves';

// routing
window.routing = require('./routing');

// theme
import './skeleton_script';
import './skeleton_custom';
