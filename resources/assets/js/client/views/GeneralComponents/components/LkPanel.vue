<template lang="pug">
section#panel(v-if="profile")
  .custom-container
    .panel
      .panel__tabs
        ul.panel__tabs-list
          li.panel__tabs-item
            a.panel__tabs-item-link(href="/bids" :class="{'panel__tabs-item-link_active' : activeType == 'buy'}" @click.prevent="goToUrlWithReachGoal('zakaz', '/bids')") Заказы
          li.panel__tabs-item
            a.panel__tabs-item-link(href="/!sales" :class="{'panel__tabs-item-link_active' : activeType == 'sell'}" @click.prevent="goToUrlWithReachGoal('predlog', '/!sales')") Предложения
      .panel__widgets(v-if="!isMobile && $root.isAuth", :class="{'custom-dropdown_open': dropDownPanel}")
        .panel__favorites
          router-link(:to="{ name: 'lk.favorites' }")
            svg(width="18", height="24", viewBox="0 0 18 24", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(
                fill-rule="evenodd",
                clip-rule="evenodd",
                d="M7.50031 19.5L15.0006 24V6C15.0006 5.20435 14.6845 4.44129 14.1219 3.87868C13.5593 3.31607 12.7962 3 12.0005 3H3.00013C2.20444 3 1.44135 3.31607 0.878716 3.87868C0.316084 4.44129 0 5.20435 0 6V24L7.50031 19.5ZM1.50006 21.351L7.50031 17.751L13.5006 21.351V6C13.5006 5.60218 13.3425 5.22064 13.0612 4.93934C12.7799 4.65804 12.3983 4.5 12.0005 4.5H3.00013C2.60228 4.5 2.22074 4.65804 1.93942 4.93934C1.6581 5.22064 1.50006 5.60218 1.50006 6V21.351Z",
                fill="#2FC06E"
              )
              path(d="M18.001 21L16.5009 20.1V3C16.5009 2.60218 16.3428 2.22064 16.0615 1.93934C15.7802 1.65804 15.3987 1.5 15.0008 1.5H3.40234C3.66565 1.04395 4.04437 0.665247 4.50043 0.401943C4.95649 0.138639 5.47383 1.33777e-05 6.00045 0L15.0008 0C15.7965 0 16.5596 0.316071 17.1222 0.87868C17.6849 1.44129 18.001 2.20435 18.001 3V21Z", fill="#2FC06E")
            template(v-if='favCount > 0')
              span.panel__favorites-count {{ favCount }}
        button.panel__account(@click="openDropDownPanel")
          .panel__account-icon
            svg(width="22", height="22", viewBox="0 0 22 22", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(
                fill-rule="evenodd",
                clip-rule="evenodd",
                d="M20.1667 22C20.1667 22 22 22 22 20.1667C22 18.3333 20.1667 12.8333 11 12.8333C1.83333 12.8333 0 18.3333 0 20.1667C0 22 1.83333 22 1.83333 22H20.1667ZM1.8425 20.2693V20.2657V20.2693ZM1.87367 20.1667H20.1263C20.1349 20.1657 20.1435 20.1644 20.152 20.163L20.1667 20.1593C20.1648 19.7083 19.8843 18.3517 18.6413 17.1087C17.446 15.9133 15.1965 14.6667 11 14.6667C6.80167 14.6667 4.554 15.9133 3.35867 17.1087C2.11567 18.3517 1.837 19.7083 1.83333 20.1593C1.84675 20.1619 1.8602 20.1644 1.87367 20.1667ZM20.1593 20.2693V20.2657V20.2693ZM11 9.16667C11.9725 9.16667 12.9051 8.78036 13.5927 8.09272C14.2804 7.40509 14.6667 6.47246 14.6667 5.5C14.6667 4.52754 14.2804 3.59491 13.5927 2.90727C12.9051 2.21964 11.9725 1.83333 11 1.83333C10.0275 1.83333 9.09491 2.21964 8.40728 2.90727C7.71964 3.59491 7.33333 4.52754 7.33333 5.5C7.33333 6.47246 7.71964 7.40509 8.40728 8.09272C9.09491 8.78036 10.0275 9.16667 11 9.16667ZM16.5 5.5C16.5 6.95869 15.9205 8.35764 14.8891 9.38909C13.8576 10.4205 12.4587 11 11 11C9.54131 11 8.14236 10.4205 7.11091 9.38909C6.07946 8.35764 5.5 6.95869 5.5 5.5C5.5 4.04131 6.07946 2.64236 7.11091 1.61091C8.14236 0.579463 9.54131 0 11 0C12.4587 0 13.8576 0.579463 14.8891 1.61091C15.9205 2.64236 16.5 4.04131 16.5 5.5Z",
                fill="#2FC06E"
              )
            span.panel__accoutn-alerts(v-if="hasNotifications && profile") {{countNot}}
          .panel__account-name {{profile.profile.name}}
        .panel__account-dropdown.custom-dropdown(role="list", v-click-outside="closeDropDownPanel", @click="closeDropDownPanel")
          ul.panel__account-dropdown-list
            li.panel__account-dropdown-item
              router-link.account-dropdown-link(:to="{name: 'lk.settings'}")
                svg(width="24", height="20", viewBox="0 0 24 20", fill="none", xmlns="http://www.w3.org/2000/svg")
                  path(
                    d="M11.7647 0L0 10.5882H3.52941V20H20V10.5882H23.5294L11.7647 0ZM11.7647 6.76471C12.4667 6.76471 13.14 7.04359 13.6365 7.54001C14.1329 8.03643 14.4118 8.70972 14.4118 9.41177C14.4118 10.1138 14.1329 10.7871 13.6365 11.2835C13.14 11.7799 12.4667 12.0588 11.7647 12.0588C11.0627 12.0588 10.3894 11.7799 9.89295 11.2835C9.39653 10.7871 9.11765 10.1138 9.11765 9.41177C9.11765 8.70972 9.39653 8.03643 9.89295 7.54001C10.3894 7.04359 11.0627 6.76471 11.7647 6.76471ZM11.7647 14.1176C13.5294 14.1176 17.0588 15 17.0588 16.7647V17.6471H6.47059V16.7647C6.47059 15 10 14.1176 11.7647 14.1176Z",
                    fill="#18A0FB"
                  )
                div Личный кабинет
            li.panel__account-dropdown-item(@click="logout")
              a.account-dropdown-link(href="javascript:void(0)")
                svg(width="21", height="18", viewBox="0 0 21 18", fill="none", xmlns="http://www.w3.org/2000/svg")
                  path(d="M13.4062 0V2.57143H3.28125V15.4286H13.4062V18H0.75V0H13.4062ZM15.9375 5.14286L21 9L15.9375 12.8571V10.2857H5.8125V7.71429H15.9375V5.14286Z", fill="#2FC06E")
                div Выход
      .panel__widgets(v-else)
        .panel__auth
            a.panel__auth-link(href='/lk/profile') Войти / Зарегистрироваться
</template>

<script>
import { mapGetters, mapActions } from "vuex"

export default {
  data() {
    return {
      profile: null,
      countNot: null,
      dropDownPanel: false,
      activeType: ""
    }
  },
  mounted() {
    this.loadProfile()
  },
  methods: {
		...mapActions("favourites", ["setCount"]),
    async loadProfile() {
      this.profile = this.getProfileState
      this.$root.profile = this.profile
    },
    openDropDownPanel() {
      this.dropDownPanel = !this.dropDownPanel
    },
    closeDropDownPanel() {
      let blockTop = document.getElementsByClassName("panel__account")
      let itemDropDown = document.getElementsByClassName("panel__account-dropdown-item")
      if ((event.composedPath().indexOf(blockTop[0]) == -1 || event.composedPath().indexOf(itemDropDown[0]) != -1) && this.dropDownPanel) this.dropDownPanel = !this.dropDownPanel
    },
    logout() {
      this.$root.logout()
      if (this.$route.name != "homepage") {
        this.$router.push({
          name: "homepage"
        })
      }
    },
    defineTypePage() {
			this.activeType = this.$route.meta.type
		},
  },
  computed: {
    ...mapGetters(["getProfileState"]),
		...mapGetters({
			favCount: 'favourites/getFavouritesCount'
		}),
    hasNotifications() {
      if (this.profile.unreadMsg > 0 || (this.profile.notifications && (this.profile.notifications.unreaded_owner_deal > 0 || this.profile.notifications.unreaded_owner_response > 0))) {
        this.countNot = this.profile.unreadMsg || 0 + this.profile.notifications.unreaded_owner_deal || 0 + this.profile.notifications.unreaded_owner_response || 0
        return this.countNot
      }
    }
  },
  watch: {
		$route(to, from) {
			this.defineTypePage();
		}
	},
  created() {
		this.defineTypePage();
		if (this.$root.isAuth) {
			this.setCount(); // get favourites count
		}
	}
}
</script>

<style lang="scss">
.panel {
	&__favorites {
		position: relative;

		&-count {
			position: absolute;
			top: -13px;
			right: -13px;
			color: white;
			background-color: #18A0FB;
			display: flex;
			width: 22px;
			height: 22px;
			justify-content: center;
			align-items: center;
			font-size: 14px;
			border-radius: 50%;
			font-weight: bold;
		}
	}
}
</style>
