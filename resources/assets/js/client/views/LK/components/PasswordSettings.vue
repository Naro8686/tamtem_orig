<template lang="pug">
.content
  .password
    form.password__form(@submit.prevent="changePassword")
      CustomPassInput(dataTitle="Текущий пароль", dataName="userPass", dataId="userPass", @input="user.password_old = $event", :dataError="err.password_old")
      a.recovery-link(href="javascript:void(0);", @click="$root.$emit('showForm','passwordResetForm')") Забыли пароль?
      CustomPassInput(dataTitle="Новый пароль", dataName="userPassNew", dataId="userPassNew", @input="user.password = $event", :dataError="err.password")
      CustomPassInput(dataTitle="Повторите пароль", dataName="userPassRepeat", dataId="userPassRepeat", @input="user.password_confirmation = $event", :dataError="err.password_confirmation")
      .custom-btn-green.password__btn-block
        button.custom-btn-green__btn(type="submit", :disabled="loading")
          | Сменить пароль
          b-spinner(v-if="loading", label="Загрузка...")
</template>

<script>
import CustomPassInput from "../../GeneralComponents/components/CustomPassInput"

export default {
  components: {
    CustomPassInput
  },
  data() {
    return {
      loading: false,
      user: {
        password_old: "",
        password: "",
        password_confirmation: ""
      },
      err: {}
    }
  },
  methods: {
    changePassword() {
      this.loading = true
      this.err = []
      axios
        .post("/api/v1/user/password/change", this.user)
        .then((resp) => {
          this.$store.dispatch("setSnackbar", {
            color: "success",
            text: "Пароль успешно обновлён",
            toggle: true
          })
        })
        .catch((err) => {
          this.err = err.response.data.errors || err.response.data.error.errors
        })
        .then(() => {
          this.loading = false
        })
    }
  }
}
</script>
