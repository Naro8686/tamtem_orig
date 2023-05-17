<template lang="pug">
.custom-checkbox-block
  input.custom-checkbox(v-if="typeCheck == 'newOrders' && profile.profile.notice_allowed", checked="true", type="checkbox", :name="dataName", :id="dataId")
  input.custom-checkbox(v-else, type="checkbox", :name="dataName", :id="dataId")
  label(:for="dataId", @click="changeSubscribe()") {{dataTitle}}
</template>

<script>
export default {
  props: {
    dataTitle: String,
    dataName: String,
    dataId: String,
    disabled: Boolean,
    typeCheck: String,
    profile: Object
  },
  methods: {
    changeSubscribe() {
      axios
        .post("/api/v1/user/subscribe", { notice_allowed: +this.profile.profile.notice_allowed })
        .then((response) => {
          if (response.data.result) {
            this.profile.profile.notice_allowed = !this.profile.profile.notice_allowed
          } else {
            this.$store.dispatch("setSnackbar", {
              color: "error",
              text: `Произошла ошибка, попробуйте позднее`,
              toggle: true
            })
          }
        })
        .catch((err) => {
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: `Произошла ошибка, попробуйте позднее`,
            toggle: true
          })
        })
    }
  }
}
</script>
