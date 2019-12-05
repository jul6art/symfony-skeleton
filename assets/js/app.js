// any CSS you require will output into a single css file (app.css in this case)
import "../css/app.scss";

// polyfills
import "time-input-polyfill/auto";
import "event-source-polyfill";

// bootstrap
import "bootstrap";

// plugins
import "bootstrap-notify";
import "jquery-slimscroll";
import "node-waves";
import "blockui";
import "bootstrap-select";
import "jquery-cookie-bubble/js/cookieBubble";

// routing
window.Routing = require("./routing");

// translator
window.Translator = require("bazinga-translator");

// theme
import "./skeleton_script";
import "./skeleton_custom";
import "./push";

// import functions from other js file
// import {validationCaptchaCallback} from './form';
