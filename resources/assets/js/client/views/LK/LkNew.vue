<template lang="pug">
section.content
  .title__block-mobile(v-if="isMobile")
    svg.close__svg(width="18", height="17", viewBox="0 0 18 17", fill="none", xmlns="http://www.w3.org/2000/svg", @click="$router.push({ name: 'bids.list'})")
      line(x1="16", y1="1.41421", x2="2.41421", y2="15", stroke="#888888", stroke-width="2", stroke-linecap="round")
      line(x1="1", y1="-1", x2="20.2132", y2="-1", transform="matrix(0.707107 0.707107 0.707107 -0.707107 2 0)", stroke="#888888", stroke-width="2", stroke-linecap="round")
    svg.back__svg(v-if="hasBackArrow()", width="31", height="16", viewBox="0 0 31 16", fill="none", xmlns="http://www.w3.org/2000/svg", @click="backArrowPage")
      path(d="M0.292891 7.29289C-0.0976334 7.68342 -0.0976334 8.31658 0.292891 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928932C7.68054 0.538408 7.04738 0.538408 6.65685 0.928932L0.292891 7.29289ZM31 7L0.999998 7V9L31 9V7Z", fill="#888888")
    .title-text__block-mobile {{$route.meta.title}}
    .title-text__bids-mobile(v-if="this.$route.name == 'lk.deals' && isMobile")
      div(@click="changeType('buy')", :class="{'active' : mobileTabsClick == 'buy'}") Заказы
      div(@click="changeType('sell')", :class="{'active' : mobileTabsClick == 'sell'}") Предложения
  .custom-container(v-if="$root.profile")
    Breadcrumbs(v-if="!isMobile")
    .content__block
      LkNavigationNew(v-if="!isMobile || (this.$route.name == 'lk.mobile' && isMobile)", :profile="profile")
      .main-content(v-if="this.$route.name != 'lk.mobile'")
        router-view(@profileUpdateEmit="loadProfile", :profile="profile", :mobileTabsClick="mobileTabsClick")
        Loader(v-if="this.loading")
</template>

<script>
import LkNavigationNew from "./components/LkNavigationNew"
import { mapGetters, mapActions } from "vuex"
import Breadcrumbs from "./components/Breadcrumbs"

export default {
  components: {
    LkNavigationNew,
    Breadcrumbs
  },
  data() {
    return {
      proAccount: true,
      loading: false,
      supplier: true,
      profile: null,
      isAccessOpen: false,
      snackbar: {
        toggle: false,
        timeout: 3000,
        color: "",
        text: ""
      },
      err: null,
      errMedia: {},
      paylink: null,
      mobileTabsClick: "buy"
    }
  },
  mounted() {
    this.loadProfile()
  },
  methods: {
    ...mapActions(["setSnackbar"]),
    changeType(type) {
      this.mobileTabsClick = type
    },
    backArrowPage() {
      if (this.$route.fullPath.indexOf("lk/mobile") == -1 && this.$route.fullPath.indexOf("lk/dialogs/") != -1) {
        this.$router.push({ name: "lk.dialogs" })
      } else if (this.$route.fullPath.indexOf("lk/mobile") == -1 && this.$route.fullPath.indexOf("lk/") != -1) {
        this.$router.push({ name: "lk.mobile" })
      }
    },
    hasBackArrow() {
      if (this.$route.fullPath.indexOf("lk/mobile") == -1) {
        return true
      } else {
        return false
      }
    },
    async loadProfile() {
      this.profile = this.getProfileState
      // this.profile = await Api.loadProfile()
      this.$root.profile = this.profile
    },
    getBillPay(data) {
      this.loading = true
      axios
        .post("/api/v1/paymentservice/get/score", {
          params: {
            unique_id: this.profile.profile.unique_id,
            summ: parseInt(data.price, 10)
          }
        })
        .then((resp) => {
          if (resp.data.result && resp.data.result === true) {
            this.paylink = resp.data.link
            // this.scoreNumber = resp.data.scoreNumber
            this.setSnackbar({
              color: "success",
              text: "Счёт сформирован",
              toggle: true
            })

            if (window.isProdMode) {
              window.ym(76387882, "reachGoal", "create_schet") // Выставить счет
            }
            window.open(this.paylink, "_blank").focus()
            this.loading = false
          } else {
            this.printErrorOnConsole("Произошла ошибка, попробуйте позднее", "warning")
            this.setSnackbar({
              color: "success",
              text: "Произошла ошибка, попробуйте позднее",
              toggle: true
            })
          }
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
          this.loading = false
        })
    }
  },
  computed: {
    ...mapGetters(["getProfileState"])
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      //if (vm.$root.profile.company.organization.status == 'approve' || !vm.$root.profile) {
      next()
      //} else {
      //next({name: 'map.categories'});
      // }
    })
  }
}
</script>

<style>
.title-text__bids-mobile {
  display: flex;
  margin-top: 16px;
  margin-bottom: 12px;
}

.title-text__bids-mobile div {
  font-family: Montserrat;
  font-style: normal;
  font-weight: 600;
  font-size: 20px;
  line-height: 24px;
  color: #888888;
}

.title-text__bids-mobile div:first-child {
  margin-right: 50px;
}

.title-text__bids-mobile div.active {
  color: #2fc06e;
}

.title__block-mobile {
  min-height: 118px;
  background: #ebf9f1;
  padding-right: 55px;
  padding-left: 55px;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  width: 100%;
  padding-top: 40px;
  flex-direction: column;
  justify-content: space-between;
}

.title__block-mobile .close__svg {
  position: absolute;
  right: 25px;
  top: 18px;
}

.title__block-mobile .back__svg {
  position: absolute;
  left: 25px;
  top: 22px;
}

.title-text__block-mobile {
  font-family: Montserrat;
  font-style: normal;
  font-weight: 800;
  font-size: 22px;
  line-height: 24px;
  color: #2c3444;
  text-align: center;
}

.nav-tabs {
  border: none !important;
}

.subtab {
  height: 100%;
  display: flex;
  align-items: center;
  padding: 0;
}
</style>
