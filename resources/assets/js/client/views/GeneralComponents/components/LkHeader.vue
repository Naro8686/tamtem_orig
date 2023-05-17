<template lang="pug">
header(v-if="profile")
  .header
    .custom-container
      .header__block(:class="burgerMenu ? 'header__burger-menu_open' : ''")
        .header__burger-menu(aria-label="Open navigation menu", tabindex="0", @click="openBurgerMenu")
          span.header__burger-line.header__burger-line_top
          span.header__burger-line.header__burger-line_middle
          span.header__burger-line.header__burger-line_bottom
        .header__logo
          router-link.header__logo-link(:to="{name:'homepage'}")
            svg(width="150", height="44", viewBox="0 0 150 44", fill="none", xmlns="http://www.w3.org/2000/svg", xmlns:xlink="http://www.w3.org/1999/xlink")
              rect(width="150", height="44", fill="url(#pattern0)")
              defs
                pattern#pattern0(patternContentUnits="objectBoundingBox", width="1", height="1")
                  use(xlink:href="#image0", transform="scale(0.00666667 0.0227273)")
                image#image0(
                  width="150",
                  height="44",
                  xlink:href="data:image/png;base64,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"
                )
        nav
          ul.header__nav-list
            li.header__nav-item
              a.header__nav-item-link.header__nav-item-link_active(href="/contact") О нас
            li.header__nav-item
              a.header__nav-item-link(href="/!sales") Оптовые объявления
            li.header__nav-item
              a.header__nav-item-link(href="/faq", @click="tofaq()") Помощь
          .header__nav-info
            a.header__nav-support(href="#") Поддержка
            a.header__nav-dd-link(href="tel:8 800 700 14 76") 8 800 700 14 76
            span.header__nav-dd-tel-info Бесплатно по России
            a.header__nav-dd-link(href="mailto:team@tamtem.ru") team@tamtem.ru
        .header__btnNew
          a.custom-btn.header__btnNew(@click="toSellCreate()", href="/newsell")
            span +
            | Разместить объявление
        .panel__widgets(v-if="isMobile && $root.isAuth")
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
          router-link.panel__account(:to="isMobile ? {name: 'lk.mobile'} : { name: 'lk.deals' }")
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
</template>

<script>
import { mapGetters, mapActions } from "vuex"

export default {
  data() {
    return {
      burgerMenu: false,
      profile: null
    }
  },
  mounted() {
    this.loadProfile()
  },
  methods: {
    async loadProfile() {
      this.profile = this.getProfileState
      this.$root.profile = this.profile
    },
    openBurgerMenu() {
      this.burgerMenu = !this.burgerMenu
    },
    tofaq() {
      event.preventDefault()
      this.yandexReachGoal("tamtem_faq")
      this.googleReachGoal("tamtem_faq")
      location.href = "/faq"
    },
    toSellCreate() {
      this.yandexReachGoal("sozdobnn")
      event.preventDefault()
      const currentPath = this.$route.name
      if (currentPath != "new.sell") {
        this.$router.push({ name: "new.sell" }).catch(() => {})
      } else {
        this.$root.$emit("resetFormCreate")
      }
    }
  },
  computed: {
    ...mapGetters(["getProfileState"]),
    hasNotifications() {
      if (this.profile.unreadMsg > 0 || (this.profile.notifications && (this.profile.notifications.unreaded_owner_deal > 0 || this.profile.notifications.unreaded_owner_response > 0))) {
        this.countNot = this.profile.unreadMsg || 0 + this.profile.notifications.unreaded_owner_deal || 0 + this.profile.notifications.unreaded_owner_response || 0
        return this.countNot
      }
    }
  }
}
</script>
