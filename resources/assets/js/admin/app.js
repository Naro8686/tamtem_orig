// require("../bootstrap");
// require("../../../../node_modules/jquery/dist/jquery");
// require("../../../../node_modules/jquery-ui/ui/widget");
// require("../../../../node_modules/bootstrap/dist/js/bootstrap");
require("../../../../node_modules/admin-lte/dist/js/adminlte.min.js");
require("../../../../node_modules/datatables.net/js/jquery.dataTables");
require("../../../../node_modules/datatables.net-bs/js/dataTables.bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";
import App from "./views/App";
import routes from "./router";
import LocaleMixins from "./mixins/locale";
import Message from "vue-m-message";
import ApplicationMixin from "./mixins/application";
import ViewMixins from "./mixins/view";
import ProgressBar from "./views/Components/Progressbar";

//подключение новых компонентов  -начало

// блокировать/разблокировать пользователя
import userblock from "./views/Components/blockcomponent";
Vue.component("userblock", userblock);

// формирование и отправка счёта
import scoreform from "./views/Components/scoreform";
Vue.component("scoreform", scoreform);
// подключение новых компонентов  -конец

import wysiwyg from "vue-wysiwyg";
Vue.use(wysiwyg, {}); // config is optional. more below

Vue.use(Message);
Vue.use(VueRouter);
Vue.mixin(LocaleMixins);

Vue.component("datatable", require("./views/Components/Datatable.vue"));
Vue.component("progressbar", ProgressBar);

const router = new VueRouter({
  mode: "history",
  routes: routes
});

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
  console.error(
    "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
  );
}


const app = new Vue({
  router,
  mixins: [ApplicationMixin, ViewMixins],
  render: h => h(App)
});
