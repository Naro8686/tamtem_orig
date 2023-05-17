<template lang="pug">
li.messages__item.messages__item_active(role="tab", v-if="dialog")
  router-link(:class="{'dialog-item--unread': dialog.count_unreaded_messages > 0, 'dialog-item': true}", :to="{name:'lk.dialogs.private', params: {id: dialog.id}}")
    .messages__user-img
      img(v-if="dialog.user.photo", :src="dialog.user.photo", alt=" ")
      img(v-else, src="/images/icon form.png")
    .messages__info
      .messages__title {{dialog.user.name}} - {{dialog.deal.name}}
      .messages__msg {{textPrev}}
    .messages__date {{dateFormat(dateTimezone(dialog.last_message_date), 'DD MMMM')}}
</template>

<script>
export default {
  props: ["dialog"],
  data() {
    return {
      textPrev: String
    }
  },
  methods: {
    dateTimezone(d) {
      if (d) {
        let date = new Date(d.date)
        date.setHours(date.getHours() + d.timezone_type)
        return date
      }
      return null
    },
    deleteDialog() {
      axios
        .delete("/api/v1/dialogs/" + this.dialog.id)
        .then((resp) => {
          this.$store.dispatch("setSnackbar", {
            color: "success",
            text: "Диалог успешно удален",
            toggle: true
          })
          this.$router.push({ name: "lk.dialogs" })
        })
        .catch((error) => {
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: "Ошибка",
            toggle: true
          })
        })
    }
  },
  mounted() {
    let size = 23
    console.log(this.dialog.last_message)
    if (this.dialog.last_message.length > size) {
      this.textPrev = this.dialog.last_message.slice(0, size) + "..."
    } else {
      this.textPrev = this.dialog.last_message
    }
  }
}
</script>
