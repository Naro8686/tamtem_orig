<template lang="pug">
.tabs
  .maintabs
    button.maintab.active Настройки профиля и данных о компании
  div(v-if="profile")
    b-tabs(v-model="tabInd", nav-wrapper-class="subtabs", @input="changeTab")
      b-tab(title="Профиль", title-link-class="subtab")
        ProfileSettings(v-if="tabInd == 0", @loadingSet="loadingSet", :profile="profile")
      b-tab(title="Компания", title-link-class="subtab")
        CompanySettingsNew(v-if="tabInd == 1", @loadingSet="loadingSet", :profile="profile")
      b-tab(title="Уведомления", title-link-class="subtab")
        AlertsSettings(v-if="tabInd == 2", @loadingSet="loadingSet", :profile="profile")
      b-tab(:title="isMobile ? 'Пароль' : 'Сменить пароль'", title-link-class="subtab")
        PasswordSettings(v-if="tabInd == 3", @loadingSet="loadingSet")
</template>

<script>
import ProfileSettings from "../components/ProfileSettings"
import CompanySettingsNew from "../components/CompanySettingsNew"
import AlertsSettings from "../components/AlertsSettings"
import PasswordSettings from "../components/PasswordSettings"
import { Validator } from "vee-validate"

export default {
  components: {
    ProfileSettings,
    AlertsSettings,
    PasswordSettings,
    CompanySettingsNew
  },
  data() {
    return {
      tabInd: 0,
      tabs: ["profile", "company", "alerts", "password"]
    }
  },
  props: {
    profile: Object
  },
  created() {
    Validator.extend("mobilePhone", {
      field: (field) => `Поле ${field} имеет ошибочный формат`,
      validate: (value) => {
        return value.replace(/[^0-9]/gi, "").replace(/^[78]/, "").length == 10
      }
    })
  },
  methods: {
    loadingSet(val) {
      this.$parent.loading = val
    },
    changeTab() {
      this.setHash(this.tabs[this.tabInd])
    },
    setHash(hash) {
      this.$router
        .replace({
          hash
        })
        .catch(() => {})
    },
    init() {
      const name = this.$router.history.current.hash.replace("#", "").split("&")[0]

      const ind = this.tabs.indexOf(name)

      this.$nextTick(() => {
        if (ind >= 0) {
          this.tabInd = ind
        } else {
          this.tabInd = 0
        }
        this.setHash(this.tabs[this.tabInd])
      })
    },
    validateBeforeSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.err = {}

          for (let key in this.form) {
            if (this.form[key]) this.formData.set(key, this.form[key])
          }

          this.profileUpdate()
        }
      })
    },
    profileUpdate() {
      this.loading = true
      axios
        .post("/api/v1/user/update", this.formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then((resp) => {
          this.loading = false
          if (resp.data.error) {
            this.err = resp.data.error
          } else {
            this.$store.dispatch("setProfile", { profile: resp.data.data })
            this.$emit("profileUpdateEmit")
            this.$router.push({ name: "lk.profile" })
            this.$store.dispatch("setSnackbar", {
              color: "success",
              text: "Данные успешно обновлены",
              toggle: true
            })
          }
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
          this.loading = false
          this.err = error.response.data.errors
        })
    }
    // updateFields() {
    //   if (this.profile) {
    //     this.form.name = this.profile.profile.name
    //     this.form.phone = this.profile.profile.phone
    //   }
    // }
  },
  mounted() {},
  watch: {}
}
</script>
