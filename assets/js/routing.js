const routes = require("../../public/js/fos_js_routes.json");
const Routing = require("../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js");
Routing.setRoutingData(routes);

module.exports = Routing;
