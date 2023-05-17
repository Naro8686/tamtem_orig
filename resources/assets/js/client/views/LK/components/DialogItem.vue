<template lang="pug">
router-link(
	v-if='dialog'
	:class="{'dialog-item--unread': dialog.count_unreaded_messages > 0, 'dialog-item': true}"
	:to="{name:'lk.dialogs.private', params: {id: dialog.id}}"
)
	.dialog-item__userpic
		img(v-if='dialog.user.photo' :src='dialog.user.photo' alt=' ')
		img(v-else :src="'https://via.placeholder.com/80x80?text='+ dialog.user.name.charAt(0)" :alt="dialog.user.name")
	.dialog-item__date {{dateTimeformat(dateTimezone(dialog.last_message_date))}}
	.dialog-item__title
		|{{dialog.user.name}} - 
		router-link(
			:to="{name: 'bids.detail', params: {id: dialog.deal.id}}"
		) {{dialog.deal.name}}
	.dialog-item__msg
		p {{dialog.last_message}}
	// button.button.flex-box.f-align-center.f-content-center(@click="deleteDialog") Удалить диалог
	// router-link(
	// 	:to="{name:'lk.dialogs.complaint', params: {id: dialog.organization.id, data: dialog}}" 
	// 	class="button flex-box f-align-center f-content-center"
	// ) Пожаловаться

</template>

<script>
export default {
  props: ["dialog"],
  data() {
    return {};
  },
  methods: {
    dateTimezone(d) {
      if (d) {
        let date = new Date(d.date);
        date.setHours(date.getHours() + d.timezone_type);
        return date;
      }
      return null;
    },
    deleteDialog() {
      axios
        .delete("/api/v1/dialogs/" + this.dialog.id)
        .then(resp => {
          this.$store.dispatch("setSnackbar", {
            color: "success",
            text: "Диалог успешно удален",
            toggle: true
          });
          this.$router.push({ name: "lk.dialogs" });
        })
        .catch(error => {
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: "Ошибка",
            toggle: true
          });
        });
    }
  },
  mounted() {}
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
.dialog-item {
  font: $font-regular;
	font-size: 14px;
	padding: 5px;
	display: grid;
	grid-column-gap: 10px;
	grid-template-columns: auto 1fr 1fr 1fr 1fr;
	grid-template-areas: 
		"userpic name name . time"
		"userpic msg msg msg msg";
  background: $color-base-light;
  text-decoration: none;
  margin-bottom: 15px;
  transform: translateY(0);
  transition: box-shadow 0.2s ease, transform 0.2s ease;
  @include hover-focus {
    box-shadow: 0 0.1rem 0.6rem 0 rgba($black, 0.16);
    transform: translateY(-0.1rem);
  }
  &--unread {
    .dialog-item__msg {
			background-color: $color-grey;
			padding: 3px 8px;
			border-radius: 6px;
    }
  }

  &__userpic {
		width: 59px;
		height: 59px;
		overflow: hidden;
		position: relative;
		border-radius: $border-radius-standart;
		grid-area: userpic;
		img {
			@include imgAbsCenter();
		}
	}
  &__date {
		grid-area: time;
		justify-self: end;
		align-self: start;
		color: $color-text-dark;
  }
  &__title {
		font: $font-medium;
		font-size: 14px;
		grid-area: name;
		align-self: center;
		color: $color-base-dark;
		overflow: hidden;
		a {
			color: inherit;
		}
	}
	&__msg {
		grid-area: msg;
		align-self: center;
		color: $color-text-dark;
		overflow: hidden;
		
	}
}
</style>