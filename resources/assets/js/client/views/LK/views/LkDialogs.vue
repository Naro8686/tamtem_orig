<template lang="pug">
.dialogs
	//.dialogs-filter
		b-btn(
			:class="{'active': isInDialog}"
			@click.stop="toggle('in')"
			size='sm'
			variant='outline-primary'
		) Входящие
		b-btn(
			:class="{'active': isOutDialog}"
			@click.stop="toggle('out')"
			size='sm'
			variant='outline-primary'
		) Исходящие

	template(v-if='dialogList && dialogList.count > 0')
		router-view
		DialogItem(
			v-for='(dialog, index) in dialogList.items' 
			:dialog='dialog'
			:key='index'
		)
	p(v-else) Диалогов нет

</template>

<script>
import DialogItem from "../components/DialogItem.vue";

export default {
  components: {
    DialogItem
  },
  data() {
    return {
      isInDialog: true,
      isOutDialog: false,
      typeDialog: "in",
      dialogList: null
    };
  },
  methods: {
    getDialogs() {
      this.$parent.loading = true;
      axios
        .get("/api/v1/dialogs/")
        .then(resp => {
          this.dialogList = resp.data.data;
          this.dialogList.items.sort((a, b) => {
            if (a.last_message_date.date > b.last_message_date.date) {
              return 0;
            }
            return 1;
          });
        })
        .catch(error => {
          this.printErrorOnConsole(error);
        })
        .then(() => {
          this.$parent.loading = false;
        });
    },
    toggle(type) {
      this.typeDialog = type;
      switch (type) {
        case "in":
          this.isInDialog = true;
          this.isOutDialog = false;
          break;
        case "out":
          this.isInDialog = false;
          this.isOutDialog = true;
          break;
      }
    }
  },
  created() {
    this.getDialogs();
  }
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
.row .col-content .card,
.card {
  @include media-breakpoint-down(sm) {
    border: none;
    box-shadow: none;
  }
}
.dialogs {
  &-filter {
    margin-bottom: 1.5rem;
    .btn {
      padding-left: 2.5rem;
      padding-right: 2.5rem;
      border-color: $gray;
      color: $gray;
      background: $color-base-accent;
      &.active {
        background: $color-base-accent;
        border-color: $color-base-dark;
        color: $color-base-dark;
      }
      & + .btn {
        margin-left: 1.5rem;
      }
    }
  }
}
</style>
