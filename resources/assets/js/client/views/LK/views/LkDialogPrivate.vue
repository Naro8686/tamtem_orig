<template lang="pug">
.card.profile-card
	.card-body
		.messages
			.messages__header
				router-link(:to="{name: 'lk.dialogs'}").messages__back
					arrowleft
				.messages__bidname
					router-link(
						v-if='dialog'
						:to="{name: 'bids.detail', params: {id: dialog.deal.id}}"
					) Заказ: {{dialog.deal.name}}

			ul(v-if='privateDialog' ref='list').messages__dialoglist.dialoglist
				li.text-center(v-if='dialog ? !!dialog.hasMore : false')
					b-button(variant='outline-primary' @click='getMore()')
						span(v-if='isLoadingMore') Загрузка...
						span(v-else) Загрузить ещe
				li(
					v-for='(item, ind) in privateDialog' 
					:class="{'m-right': (item.user.id === profile.id), 'm-left': (item.user.id !== profile.id), 'm-response': item.type === 'responce'}" 
					:key='item.id'
				)
					div.messages__date-day(v-if='item.date_create && dates[item.id]') {{dateTimeformat(dateTimezone(item.date_create))}}
					div.messages-item(:data-id='item.user.id')
						.messages__userpic(:title='item.user.name')
							router-link(:to="{name: 'users.profile', params: {id: item.user.id}}")
								img(v-if='item.user.photo' :src='item.user.photo' alt=' ')
								img(v-else :src="'https://via.placeholder.com/80x80?text='+ item.user.name.charAt(0)" alt=' ')
						.messages-info
							.spinner-border.text-primary.ml-auto.mr-3(v-if='item.id === 0' role="status")
								span.sr-only Loading...
							.messages-bubble.dialoglist__bubble
								//- h6(v-if="item.type === 'responce'")
								//- 	span(v-if='item.organization') {{item.organization.name}} 
								//- 	span {{item.user.name}}
								p.text-break {{item.message}}
								//- a.messages-file(
								//- 	v-if='item.files && item.files.length'
								//- 	:href='item.files[0].file_full_path'
								//- 	target='_blank'
								//- )
									feather(type='file')
									span {{item.files[0].file_name}}
							//- .messages__date(v-if='item.date_create') {{dateFormat(dateTimezone(item.date_create), 'HH:mm')}}
			p.mb-5(v-else) Сообщений нет
			.messages__form.message-form
				.message-form__field
					textarea(
						ref='textmsg'
						v-model='messageText'
						placeholder='Сообщение'
						:disabled='statusSending'
						rows='1'
						@keydown="keydownMessage"
					)
				.message-form__send(@click='sendMessage')
					div.message-form__send-icon

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
        msg => {
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
        .then(resp => {
          this.dialog = resp.data.data;

          this.createEchoInstance();

          this.$root.checkNewMsg();
          this.setScrollAndFocus();
        })
        .catch(error => {
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
          .then(resp => {
            this.messageText = "";
            this.$nextTick(() => {
              this.setScrollAndFocus();
            });
          })
          .catch(error => {
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
        .then(resp => {
          this.dialog.hasMore = resp.data.data.hasMore;
          this.dialog.items = this.dialog.items.concat(resp.data.data.items);
          this.nextPage++;
        })
        .catch(error => {
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
          .then(resp => {})
          .catch(error => {
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
        this.dialog.items = $.grep(this.dialog.items, item => {
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
@import "~styleguide";
@import "~mixins";
.row .col-content .card,
.card {
  @include media-breakpoint-down(sm) {
    border: none;
    box-shadow: none;
  }
  &-body {
    @include media-breakpoint-down(sm) {
      padding: 0;
    }
  }
}
.messages {
  &__header {
    position: relative;
    padding: 0.3rem 6rem 0;
    padding-left: 9rem;
    margin-bottom: 3rem;
    @include media-breakpoint-down(sm) {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }
  &__back {
    position: absolute;
    left: 0;
    top: 50%;
    margin-top: -15px;
    display: flex;
    width: 37px;
    height: 30px;
    line-height: 3rem;
    border: 2px solid $color-base-accent;
    color: $color-base-accent;
    text-align: center;
    border-radius: 5px;
    align-items: center;
    justify-content: center;
    @include media-breakpoint-down(sm) {
      display: none;
    }
    &:hover,
    &:focus {
      color: $color-base-accent;
    }
    .feather {
      width: 1.8rem;
      height: 1.8rem;
    }
  }
  &__bidname {
    font: $font-medium;
    font-size: 26px;
    line-height: 42px;
    color: $color-text-dark;
    a {
      text-decoration: none;
      color: $color-base-accent;
    }
    @media (max-width: 420px) {
      font-size: 22px;
    }
  }
  &__dialoglist {
    list-style: none;
    margin: 0 0 3rem;
    padding: 0 6rem;
    padding-left: 9rem;
    height: calc(100vh - 30rem);
    overflow-y: auto;
    @include media-breakpoint-down(sm) {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
    li {
      margin: 0 0 1rem;
      padding: 0;
      &.m-left {
        .messages__userpic {
          margin: 0 2rem 0 0;
        }
        .messages-info {
          text-align: left;
        }
        .messages-bubble {
          background: $color-base-gray-light;
          border-top-left-radius: 0;
          &::before {
            display: block;
            left: -1rem;
            border: 0.5rem solid transparent;
            border-top: 0.5rem solid $color-base-gray-light;
            border-right: 0.5rem solid $color-base-gray-light;
          }
        }
        & + .m-left {
          .messages__userpic img {
            display: none;
          }
          .messages-bubble {
            border-top-left-radius: $border-radius-small;
            &::before {
              display: none;
            }
          }
          .messages-date-day + .messages-item {
            .messages__userpic img {
              display: block;
            }
            .messages-bubble {
              border-top-left-radius: 0;
              &::before {
                display: block;
                left: -1rem;
                border: 0.5rem solid transparent;
                border-top: 0.5rem solid $color-border-gray;
                border-right: 0.5rem solid $color-border-gray;
              }
            }
          }
        }
      }
      &.m-right {
        .messages-item {
          flex-direction: row-reverse;
        }
        .messages-date {
          text-align: right;
        }
        .messages__userpic {
          margin: 0 0 0 2rem;
        }
        .messages-info {
          text-align: right;
        }
        .messages-bubble {
          background: $color-pale-green;
          border-top-right-radius: 0;

          &::before {
            display: block;
            right: -1rem;
            border: 0.5rem solid transparent;
            border-top: 0.5rem solid $color-pale-green;
            border-left: 0.5rem solid $color-pale-green;
          }
        }
        & + .m-right {
          .messages__userpic img {
            display: none;
          }
          .messages-bubble {
            border-top-right-radius: $border-radius-small;
            &::before {
              display: none;
            }
          }
          .messages-date-day + .messages-item {
            .messages__userpic img {
              display: block;
            }
            .messages-bubble {
              border-top-right-radius: 0;

              &::before {
                display: block;
                right: -1rem;
                border: 0.5rem solid transparent;
                border-top: 0.5rem solid $color-border-gray;
                border-left: 0.5rem solid $color-border-gray;
              }
            }
          }
        }
      }
      &.m-response {
        .messages-bubble {
          // padding: 0;
          // background: none;
          // &::before {
          // 	display: none;
          // }
        }
      }
    }
  }
  &-item {
    display: flex;
  }
  &__date {
    line-height: 1;
    margin-top: 0.5rem;
    font-size: 1.2rem;
    color: $gray;
  }
  &__date-day {
    text-align: center;
    font-size: 12px;
    color: $gray;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
  }
  &__userpic {
    width: 4.5rem;
    height: 4.5rem;
    overflow: hidden;
    position: relative;
    border-radius: $border-radius-small;
    @include media-breakpoint-down(sm) {
      display: none;
    }
    img {
      @include imgAbsCenter();
    }
  }
  &-info {
    flex: 1;
  }
  &-bubble {
    display: inline-block;
    position: relative;
    border-radius: $border-radius-standart;
    padding: 10px 17px;
    margin-top: 5px;
    max-width: 85%;
    &::before {
      content: "";
      position: absolute;
      top: 0;
      background: none;
    }
    h6 {
      font-size: 1.6rem;
    }
    p {
      font-size: 1.4rem;
    }
  }
}
.message-form {
  padding-left: 9rem;
  padding-top: 10px;
  padding-bottom: 10px;
  padding-right: 0px;
  background-color: #f4f5f6;
  position: relative;
  display: flex;
  align-items: center;
  @include media-breakpoint-down(sm) {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    margin: 0 calc(-#{$grid-gutter-width} / 2);
  }
  &__field {
    width: 100%;
    position: relative;
    z-index: 2;
    align-items: flex-start;
    display: flex;
    textarea {
      box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
      border-radius: 10px;
      padding: 10px 20px;
      width: 100%;
      background-color: $color-base-light;
      resize: none;
    }
    @media (max-width: 420px) {
      width: 96%;
    }
  }
  &__send {
    width: 6rem;
    height: 4rem;
    cursor: pointer;
    @include media-breakpoint-down(sm) {
      width: 3rem;
      height: 3rem;
    }
    display: flex;
    align-items: center;
    &-icon {
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 12px 0 12px 20px;
      border-color: transparent transparent transparent $color-base-accent;
    }
  }
}
</style>