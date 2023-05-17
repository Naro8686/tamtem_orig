require("../bootstrap")
require("./api/api")
// require('./api/api.filter');
require("./imports/icons.js")
require("./imports/components.js")
import "babel-polyfill"
import Vue from "vue"
import VueRouter from "vue-router"
import App from "./views/App"
import AppNew from "./views/AppNew"
import { store } from "./store"
import Notifications from 'vue-notification'

import routes from "./routes/routes"
import ApplicationMixin from "./mixins/application"
import GlobalMixin from "./mixins/global"
import Vuetify from "vuetify"
import Cookies from "js-cookie"
import { mapActions } from "vuex"
import vClickOutside from "v-click-outside"
Vue.use(vClickOutside)
import "vuetify/dist/vuetify.min.css"
import "material-design-icons-iconfont/dist/material-design-icons.css"

require("../../sass/client/utils/vuetify-custom.scss")
window.io = require("socket.io-client")

Vue.use(Vuetify)
Vue.mixin(GlobalMixin)
Vue.use(VueRouter)
Vue.use(Notifications)

import VueFeather from "vue-feather"
Vue.component(VueFeather.name, VueFeather)

try {
  window.$ = window.jQuery = require("jquery")
  require("bootstrap")
} catch (e) {}

import BootstrapVue from "bootstrap-vue"
Vue.use(BootstrapVue)

window.axios = require("axios")

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content
} else {
  console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token")
}

import VeeValidate, { Validator } from "vee-validate"
import VeeValidateRu from "vee-validate/dist/locale/ru"
VeeValidateRu.messages.confirmed = () => "Пароли не совпадают."

VeeValidateRu.messages.decimal = (field) => {
  return "Поле " + field + " должно быть числовым и может содержать 2 знака после точки."
}

Validator.localize({
  ru: VeeValidateRu
})
Vue.use(VeeValidate, {
  locale: "ru",
  events: "input|blur"
})

const router = new VueRouter({
  mode: "history",
  routes: routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.name == "sells.list") {
      if (from.name = "sells.list") {
        return {}
      }
    }
    if (to.name == "bids.list") {
      if (from.name = "bids.list") {
        return {}
      }
      if (from.name == "homepage") {
        return new Promise((resolve, reject) => {
          app.$nextTick(() => {
            resolve({ x: 0, y: 0 })
          })
        })
      }
      if (savedPosition) {
        return new Promise((resolve, reject) => {
          setTimeout(() => {
            resolve(savedPosition)
          }, 1500)
        })
      } else {
        let coord = {
          x: 0,
          y: document.body.pageYOffset
        }
        return new Promise((resolve, reject) => {
          setTimeout(() => {
            resolve(coord)
          }, 1500)
        })
      }
    } else if (to.hash) {
      return { selector: to.hash }
    } else {
      return { x: 0, y: 0 }
    }
  }
})

router.beforeEach((to, from, next) => {
  if (to.name == "success.reset") {
    next("/?reset-password=true")
  }
  let token = Cookies.get("api_auth")

  if (to.name == "lk.profile" && !token) {
    next("/?itm=signin")
    return
  }

  if (!token && to.matched.some((m) => m.meta.auth)) {
    next({ name: "bids.list" })
  } else {
    document.title = to.meta.title
    next()
  }
})

if (BUILD_MODE === "production") {
  window.isProdMode = true
} else if (BUILD_MODE === "development") {
  window.isDevMode = true
}

const app = new Vue({
  store,
  router,
  render: (h) => h(AppNew),
  mixins: [ApplicationMixin],
  components: {},
  created() {
    this.loadCategories()
    this.loadRegions()
    this.setWindowWidth()
  },
  methods: {
    ...mapActions("categories", ["loadCategories"]),
    ...mapActions("regions", ["loadRegions"]),
    ...mapActions(["setWindowWidth"])
  }
})

Vue.prototype.$snackbar = app.$store.getters.getSnackbarState

console.group(`%cGit info:`, "background:#222; color:#bada55")
console.log("%cdate: %s", "background:#222; color:#bada55", GIT_DATE)
console.log("%cbranch: %s", "background:#222; color:#bada55", GIT_BRANCH)
console.log("%cver: %s", "background:#222; color:#bada55", GIT_VERSION)
console.log("%chash: %s", "background:#222; color:#bada55", GIT_COMMITHASH)
console.groupEnd()

Vue.config.errorHandler = (err, vm, msg) => {
  app.printErrorOnConsole(err, "error", msg)
}
