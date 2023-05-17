<template lang="pug">
.tabs
  .maintabs
    button.maintab.active Сообщения
  .content
    .messages.messages__list-private
      .messages__left-block(v-if="!isMobile")
        ul.messages__list(v-if="dialog", role="list")
          DialogItemNew(v-for="(dialog, index) in dialogList.items", :dialog="dialog", :key="index")
      .messages__right-block
        ul.messages__dialog-list(v-if="privateDialog", ref="list")
          li.text-center(v-if="dialog ? !!dialog.hasMore : false")
            b-button(variant="outline-primary", @click="getMore()")
              span(v-if="isLoadingMore") Загрузка...
              span(v-else) Загрузить ещe
          li(v-for="(item, ind) in privateDialog", :class="{'messages__dialog-right': (item.user.id === profile.id), 'messages__dialog-left': (item.user.id !== profile.id), 'm-response': item.type === 'responce'}", :key="item.id")
            .messages__dialog-date(v-if="item.date_create && dates[item.id]") {{dateTimeformat(dateTimezone(item.date_create))}}
            .messages__dialog-item(:data-id="item.user.id")
              .messages__dialog-body
                .messages__dialog-user
                  a {{dialog.user.name}}-{{dialog.deal.name}}
                .messages__dialog-message
                  p.messages__dialog-text {{item.message}}
              .messages__dialog-time(v-if="item.date_create")
                span {{dateFormat(dateTimezone(item.date_create), 'HH:mm')}}
        p.mb-5(v-else) Сообщений нет
        .messages__form
          .messages__textarea-block
            textarea#message-text.messages__textarea(placeholder="Введите сообщение", v-model="messageText", rows="1", :disabled="statusSending", @keydown="keydownMessage")
          .messages__send-block(@click="sendMessage")
            svg(width="41", height="41", viewBox="0 0 41 41", fill="none", xmlns="http://www.w3.org/2000/svg")
              circle(cx="20.5", cy="20.5", r="20.5", fill="#F1F1F1")
              path(d="M28 21L18 29.6603V12.3397L28 21Z", fill="#B3B3B3")
</template>

<script>
import Vue from "vue"
import { mapGetters } from "vuex"
import DialogItemNew from "../components/DialogItemNew.vue"

const SendIcon = Vue.component("SendIcon", {
  template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.264 61.263">
    <g id="navigation" transform="rotate(45 13.61 37.683)">
      <path id="Shape" d="M0 19.573L41.32 0 21.747 41.32 17.4 23.922 0 19.573z" class="cls-1"/>
    </g>
  </svg>`
})

export default {
  components: {
    SendIcon,
    DialogItemNew
  },
  data() {
    return {
      dialog: null,
      messageText: "",
      optionsFullDate: {
        year: "numeric",
        month: "short",
        day: "numeric",
        weekday: "short",
        hour: "numeric",
        minute: "numeric"
      },
      optionsCurrentDate: {
        hour: "numeric",
        minute: "numeric"
      },
      statusSending: false,
      nextPage: 2,
      isLoadingMore: false,
      profile: null,
      dialogList: null
    }
  },
  computed: {
    ...mapGetters(["getProfileState"]),
    dates() {
      const arr = this.dialog.items.slice().reverse()
      const datesObj = {}
      let prevDate = null
      for (let ind in arr) {
        const item = arr[ind]
        if (item.date_create) {
          const curDate = item.date_create.date.split(" ")[0]

          if (curDate != prevDate) {
            prevDate = curDate
            datesObj[item.id] = true
          } else {
            datesObj[item.id] = false
          }
        }
      }
      return datesObj
    },
    privateDialog() {
      if (this.dialog) {
        const result = this.dialog.items.slice().reverse()
        return result
      }
      return false
    }
  },
  methods: {
    createEchoInstance() {
      window.Echo.private("dialog." + this.$route.params.id).listen("ChatMessage", (msg) => {
        this.printNewMsg(msg)
        // this.setScrollAndFocus()
        this.setReadedMsg(msg.message_id)
      })
    },
    getDialog() {
      this.$parent.loading = true
      axios
        .get("/api/v1/dialogs/" + this.$route.params.id)
        .then((resp) => {
          this.dialog = resp.data.data

          this.createEchoInstance()

          this.$root.checkNewMsg()
          // this.setScrollAndFocus()
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
          this.$router.replace({ name: "page404" })
        })
        .then(() => {
          this.$parent.loading = false
        })
    },
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
    keydownMessage(evt) {
      if (evt.keyCode === 13 && !evt.shiftKey) {
        evt.preventDefault()
        this.sendMessage()
      }
    },
    sendMessage() {
      if (this.messageText !== "") {
        this.statusSending = true
        this.printFakeMessage()
        axios
          .post("/api/v1/dialogs/" + this.$route.params.id + "/send", {
            message: this.messageText
          })
          .then((resp) => {
            this.messageText = ""
            this.$nextTick(() => {
              // this.setScrollAndFocus()
            })
          })
          .catch((error) => {
            this.printErrorOnConsole(error)
          })
          .then(() => {
            this.statusSending = false
          })
      }
    },
    scrollToBottom() {
      const list = this.$refs.list
      if (list) {
        // list.scrollTop = list.scrollHeight
        $(list).animate(
          {
            scrollTop: list.scrollHeight
          },
          300
        )
      }
    },
    dateTimezone(d) {
      let date = new Date(d.date)
      date.setHours(date.getHours() + d.timezone_type)
      return date
    },
    getMore() {
      this.isLoadingMore = true
      axios
        .get("/api/v1/dialogs/" + this.$route.params.id + "/?page=" + this.nextPage)
        .then((resp) => {
          this.dialog.hasMore = resp.data.data.hasMore
          this.dialog.items = this.dialog.items.concat(resp.data.data.items)
          this.nextPage++
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
        .then(() => {
          this.isLoadingMore = false
        })
    },
    setReadedMsg(id) {
      if (id) {
        axios
          .post("/api/v1/dialogs/messages/markreaded", {
            id: id
          })
          .then((resp) => {})
          .catch((error) => {
            this.printErrorOnConsole(error)
          })
      }
    },
    printFakeMessage() {
      const fakeMsg = {
        id: 0,
        message: this.messageText,
        user: this.profile
      }
      this.printNewMsg(fakeMsg)
    },
    printNewMsg(msg) {
      if (this.dialog.items) {
        this.dialog.items = $.grep(this.dialog.items, (item) => {
          return item.id !== 0
        })
        this.dialog.items.unshift(msg)
      }
    },
    uploadFile() {
      // TODO: upload file api
    }
    // setScrollAndFocus() {
    //   setTimeout(() => {
    //     this.scrollToBottom()
    //     if (!this.isMobile) {
    //       this.$refs.textmsg.focus()
    //     }
    //   }, 100)
    // }
  },
  mounted() {
    const user = this.getProfileState

    this.profile = user.profile
    this.$root.notification = false
    this.getDialog()
  },
  created() {
    this.getDialogs()
  },
  watch: {} //this.scrollToBottom()
}
</script>

<style>
.messages__date {
  text-align: center;
  width: 50px;
  overflow-wrap: break-word;
}

@media (max-width: 768px) {
  .messages__list-private {
    border: none;
    margin-left: -15px;
    margin-right: -15px;
  }

  .messages__textarea-block {
    margin-left: 15px;
  }
}
</style>
