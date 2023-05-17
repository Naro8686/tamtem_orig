<template lang="pug">
.card.profile-card
  .card-body
    .messages
      .messages__header
        router-link.messages__back(:to="{ name: 'lk.dialogs' }")
          arrowleft
        .messages__bidname
          router-link(
            v-if="dialog",
            :to="{ name: 'bids.detail', params: { id: dialog.deal.id } }"
          ) Заказ: {{ dialog.deal.name }}

      ul.messages__dialoglist.dialoglist(v-if="privateDialog", ref="list")
        li.text-center(v-if="dialog ? !!dialog.hasMore : false")
          b-button(variant="outline-primary", @click="getMore()")
            span(v-if="isLoadingMore") Загрузка...
            span(v-else) Загрузить ещe
        li(
          v-for="(item, ind) in privateDialog",
          :class="{ 'm-right': item.user.id === profile.id, 'm-left': item.user.id !== profile.id, 'm-response': item.type === 'responce' }",
          :key="item.id"
        )
          .messages__date-day(v-if="item.date_create && dates[item.id]") {{ dateTimeformat(dateTimezone(item.date_create)) }}
          .messages-item(:data-id="item.user.id")
            .messages__userpic(:title="item.user.name")
              router-link(
                :to="{ name: 'users.profile', params: { id: item.user.id } }"
              )
                img(v-if="item.user.photo", :src="item.user.photo", alt=" ")
                img(
                  v-else,
                  :src="'https://via.placeholder.com/80x80?text=' + item.user.name.charAt(0)",
                  alt=" "
                )
            .messages-info
              .spinner-border.text-primary.ml-auto.mr-3(
                v-if="item.id === 0",
                role="status"
              )
                span.sr-only Loading...
              .messages-bubble.dialoglist__bubble
                //- h6(v-if="item.type === 'responce'")
                //- span(v-if='item.organization') {{item.organization.name}} 
                //- span {{item.user.name}}
                p.text-break {{ item.message }}
                //- a.messages-file(
                //- v-if='item.files && item.files.length'
                //- :href='item.files[0].file_full_path'
                //- target='_blank'
                //- )
                  feather(type='file')
                  span {{item.files[0].file_name}}
              //- .messages__date(v-if='item.date_create') {{dateFormat(dateTimezone(item.date_create), 'HH:mm')}}
      p.mb-5(v-else) Сообщений нет
      .messages__form.message-form
        .message-form__field
          textarea(
            ref="textmsg",
            v-model="messageText",
            placeholder="Сообщение",
            :disabled="statusSending",
            rows="1",
            @keydown="keydownMessage"
          )
        .message-form__send(@click="sendMessage")
          .message-form__send-icon
</template>

<script>
import Vue from "vue";
import { mapGetters } from "vuex";
const SendIcon = Vue.component("SendIcon", {
  template: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.264 61.263">
		<g id="navigation" transform="rotate(45 13.61 37.683)">
			<path id="Shape" d="M0 19.573L41.32 0 21.747 41.32 17.4 23.922 0 19.573z" class="cls-1"/>
		</g>
	</svg>`
});

export default {
  components: {
    SendIcon
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
      profile: null
    };
  },
  computed: {
    ...mapGetters(["getProfileState"]),
    dates() {
      const arr = this.dialog.items.slice().reverse();
      const datesObj = {};
      let prevDate = null;
      for (let ind in arr) {
        const item = arr[ind];
        if (item.date_create) {
          const curDate = item.date_create.date.split(" ")[0];

          if (curDate != prevDate) {
            prevDate = curDate;
            datesObj[item.id] = true;
          } else {
            datesObj[item.id] = false;
          }
        }
      }
      return datesObj;
    },
    privateDialog() {
      if (this.dialog) {
        const result = this.dialog.items.slice().reverse();
        return result;
      }
      return false;
    }
  },
  methods: {
    createEchoInstance() {
      window.Echo.private("dialog." + this.$route.params.id).listen(
        "ChatMessage",
        (msg) => {
          this.printNewMsg(msg);
          this.setScrollAndFocus();
          this.setReadedMsg(msg.message_id);
        }
      );
    },
    getDialog() {
      this.$parent.loading = true;
      axios
        .get("/api/v1/dialogs/" + this.$route.params.id)
        .then((resp) => {
          this.dialog = resp.data.data;

          this.createEchoInstance();

          this.$root.checkNewMsg();
          this.setScrollAndFocus();
        })
        .catch((error) => {
          this.printErrorOnConsole(error);
          this.$router.replace({ name: "page404" });
        })
        .then(() => {
          this.$parent.loading = false;
        });
    },
    keydownMessage(evt) {
      if (evt.keyCode === 13 && !evt.shiftKey) {
        evt.preventDefault();
        this.sendMessage();
      }
    },
    sendMessage() {
      if (this.messageText !== "") {
        this.statusSending = true;
        this.printFakeMessage();
        axios
          .post("/api/v1/dialogs/" + this.$route.params.id + "/send", {
            message: this.messageText
          })
          .then((resp) => {
            this.messageText = "";
            this.$nextTick(() => {
              this.setScrollAndFocus();
            });
          })
          .catch((error) => {
            this.printErrorOnConsole(error);
          })
          .then(() => {
            this.statusSending = false;
          });
      }
    },
    scrollToBottom() {
      const list = this.$refs.list;
      if (list) {
        // list.scrollTop = list.scrollHeight
        $(list).animate(
          {
            scrollTop: list.scrollHeight
          },
          300
        );
      }
    },
    dateTimezone(d) {
      let date = new Date(d.date);
      date.setHours(date.getHours() + d.timezone_type);
      return date;
    },
    getMore() {
      this.isLoadingMore = true;
      axios
        .get(
          "/api/v1/dialogs/" + this.$route.params.id + "/?page=" + this.nextPage
        )
        .then((resp) => {
          this.dialog.hasMore = resp.data.data.hasMore;
          this.dialog.items = this.dialog.items.concat(resp.data.data.items);
          this.nextPage++;
        })
        .catch((error) => {
          this.printErrorOnConsole(error);
        })
        .then(() => {
          this.isLoadingMore = false;
        });
    },
    setReadedMsg(id) {
      if (id) {
        axios
          .post("/api/v1/dialogs/messages/markreaded", {
            id: id
          })
          .then((resp) => {})
          .catch((error) => {
            this.printErrorOnConsole(error);
          });
      }
    },
    printFakeMessage() {
      const fakeMsg = {
        id: 0,
        message: this.messageText,
        user: this.profile
      };
      this.printNewMsg(fakeMsg);
    },
    printNewMsg(msg) {
      if (this.dialog.items) {
        this.dialog.items = $.grep(this.dialog.items, (item) => {
          return item.id !== 0;
        });
        this.dialog.items.unshift(msg);
      }
    },
    uploadFile() {
      // TODO: upload file api
    },
    setScrollAndFocus() {
      setTimeout(() => {
        this.scrollToBottom();
        if (!this.isMobile) {
          this.$refs.textmsg.focus();
        }
      }, 100);
    }
  },
  mounted() {
    const user = this.getProfileState;

    this.profile = user.profile;
    this.$root.notification = false;
    this.getDialog();
  },
  watch: {} //this.scrollToBottom()
};
</script>

<style lang="scss" scope>
@import "~main";
</style>
