<template lang="pug">
ul.lk-nav(
	v-if='$root.profile'
)
	li
		router-link(:to="{name: 'lk.deals'}")
			feather(type='file-text')
			span 
				|Заявки
				span.badge.badge-pill.badge-blue(v-if="profile.notifications && profile.notifications.unreaded_owner_deal>0") {{profile.notifications.unreaded_owner_deal}}
	li
		router-link(:to="{name: 'lk.responses'}")
			feather(type='pocket')
			span 
				|Мои предложения
				span.badge.badge-pill.badge-blue(v-if="profile.notifications && profile.notifications.unreaded_owner_response>0") {{profile.notifications.unreaded_owner_response}}
	li
		router-link(:to="{name: 'lk.dialogs'}")
			feather(type='message-circle')
			span
				|Сообщения
				span.badge.badge-pill.badge-danger(
					v-if="profile.unreadMsg"
				) {{profile.unreadMsg}}
	li
		router-link(:to="{name: 'lk.profile'}")
			feather(type='user')
			span Профиль
	li
		router-link(:to="{name: 'lk.favorites'}")
			feather(type='star')
			span Избранное
	li
		router-link(:to="{name: 'lk.wallet'}")
			feather(type="credit-card")
			span Кошелёк 
			span.float-right.mt-2 {{priceFormat(profile.profile.ballance)}}&nbsp;&#8381;
	li
		a(href="/price")
			feather(type="award")
			span Услуги
	li(
		v-if='profile.profile.is_org_created'
	)
		router-link(:to="{name: 'lk.company'}")
			feather(type='briefcase')
			span Компания
	li
		a(
			@click='logout' 
			href='javascript:void(0)'
		)
			feather(type='log-out')
			span Выйти
	
</template>

<script>
import { mapGetters, mapState } from "vuex";
export default {
  data() {
    return {};
  },
  computed: {
    ...mapGetters([
      "getDefaultValue",
      "getUnreadedMsg",
      "getProfileBalance",
      "getProfileOrganizationIsCreated"
    ]),
    ...mapState(["profile"])
  },
  methods: {
    logout() {
      this.$root.logout();
      if (this.$route.name != "homepage") {
        this.$router.push({
          name: "homepage"
        });
      }
    }
  },
  mounted() {}
};
</script>

<style lang="scss" scope>
@import "../../../../../sass/styleguide.scss";
@import "../../../../../sass/mixins.scss";

.lk-nav {
  list-style: none;
  margin: 0;
  padding: 0;
  border-radius: $border-radius-standart;
  box-shadow: $box-shadow-aside;

  a {
    font: $font-regular;
    font-size: $fontsize-base;
    line-height: 19px;
    display: block;
    padding: 13px 30px;
    color: $color-text-gray;
    white-space: nowrap;
    text-decoration: none;
    transition: background 0.2s ease, color 0.2s ease;

    &:hover,
    &:focus {
      box-shadow: $box-shadow-standart;
    }
    span {
      vertical-align: middle;
      white-space: initial;
    }
    .feather {
      color: $color-green-elements;
      margin-right: 2.5rem;
      width: 2.4rem;
      height: 2.4rem;
      vertical-align: middle;

      @include media-breakpoint-down(md) {
        margin-right: 1rem;
      }
    }
    .badge {
      position: absolute;
      top: 50%;
      right: 1rem;
      margin-top: -0.8rem;
      &-blue {
        background-color: $color-blue-badge-background;
        color: white;
      }
    }
  }
  .router-link-active {
    cursor: default;
    font: $font-semibold;
    font-size: $fontsize-base;
    background: $color-green-elements;
    color: #fff;
    .feather {
      color: #fff;
    }
  }
}
</style>
