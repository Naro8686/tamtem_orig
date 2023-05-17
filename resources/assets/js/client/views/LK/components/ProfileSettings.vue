<template lang="pug">
.content
  .profileNew(v-if="profile")
    form.profile__form(@submit.prevent="validateBeforeSubmit")
      CustomInput(dataId="firstname", dataTitle="Имя", dataName="firstname", :dataValue="profile.profile.name", @input="form.name = $event")
      CustomInput(dataId="userphone", dataTitle="Телефон", dataName="userphone", :dataValue="phoneFormat(profile.profile.phone)", @input="form.phone = $event")
      CustomInput(dataId="usermail", dataTitle="E-mail", dataName="usermail", :dataValue="profile.profile.email", :disabled="true", message="E-mail изменить нельзя")
      .custom-btn-green
        button.custom-btn-green__btn.profile__btn(type="submit", :disabled="loading")
          | Сохранить
</template>

<script>
import CustomInput from "../../GeneralComponents/components/CustomInput"

export default {
  components: {
    CustomInput
  },
  props: {
    profile: Object
  },
  data() {
    return {
      loading: false,
      err: {},
      form: {
        name: null,
        phone: null
      },
      formData: new FormData()
    }
  },
  mounted() {},
  methods: {
    validateBeforeSubmit() {
      // this.form.phone = this.form.phone.toString().preg_replace('/[^0-9,]/', '');

      if (this.form.phone) {
        this.form.phone = this.form.phone.replace(/[^0-9]/g, "").substring(1)
      }
      console.log(this.form.phone)
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
  }
}
</script>
