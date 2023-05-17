<template lang="pug">
.tabs
  .maintabs
    button.maintab.active Сообщения
  .content
    .messages(v-if="dialogList && dialogList.count > 0", :class="{'messages_mobile' : isMobile}")
      .messages__pick-block(v-if="isMobile")
        span Выберите чат, чтобы начать переписку
      .messages__left-block
        ul.messages__list(role="list")
          router-view
          DialogItemNew(v-for="(dialog, index) in dialogList.items", :dialog="dialog", :key="index")
      .messages__right-block(v-if="!isMobile")
        .messages__dialog-default
          span Выберите чат, чтобы начать переписку
    p(v-else) Диалогов нет
</template>

<script>
import DialogItemNew from "../components/DialogItemNew.vue"

export default {
  components: {
    DialogItemNew
  },
  data() {
    return {
      isInDialog: true,
      isOutDialog: false,
      typeDialog: "in",
      dialogList: null
    }
  },
  methods: {
    getDialogs() {
      this.$parent.loading = true
      axios
        .get("/api/v1/dialogs/")
        .then((resp) => {
          this.dialogList = resp.data.data
          this.dialogList.items.sort((a, b) => {
            if (a.last_message_date.date > b.last_message_date.date) {
              return 0
            }
            return 1
          })
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
        .then(() => {
          this.$parent.loading = false
        })
    },
    toggle(type) {
      this.typeDialog = type
      switch (type) {
        case "in":
          this.isInDialog = true
          this.isOutDialog = false
          break
        case "out":
          this.isInDialog = false
          this.isOutDialog = true
          break
      }
    }
  },
  created() {
    this.getDialogs()
  }
}
</script>

<style>
.messages__date {
  text-align: center;
  width: 50px;
  overflow-wrap: break-word;
}
</style>
