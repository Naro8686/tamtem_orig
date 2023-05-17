<template lang="pug">
v-app
  LkHeader(v-if="$route.name.indexOf('lk') != -1")
  newheader(v-else)
  filters-item(v-if="isFiltersVisible")

  .error-message(v-if="$root.errorCode == 429")
    | С вашего IP поступает слишком много запросов! Обновите страницу через несколько минут.
  .error-message(v-if="$root.errorCode == true")
    | Ошибка соединения с сетью или плохое соединение.

  main.main.custom__main(role="main", :class="'main-' + $route.name.replace('.', '-')")
    LkPanel(v-if="($route.name.indexOf('lk') != -1 || $route.name == 'bids.list' || $route.name == 'sells.list') && !isMobile")
    SliderCategories(v-if="$route.name.indexOf('lk') != -1 || $route.name == 'bids.list' || $route.name == 'sells.list'")
    router-view
    notifications
    Loader(v-if="getLoadingState")
    .new-bid-md
      router-link.btn.btn-newbid(v-if="$route.name == 'bids.list'", :to="{ name: 'new.bid' }") Добавить заказ
    .new-bid-md
      router-link.btn.btn-newbid(v-if="$route.name == 'sells.list'", :to="{ name: 'new.sell' }") Добавить объявление
  b-modal#modalMsg(modal-class="modal-msg", :size="getModalMsgState.size", v-model="getModalMsgState.toggle", ref="modalMsg")
    template(slot="modal-title")
      .text-center.py-5
        logoheader
      hr
    div(v-html="getModalMsgState.content")
    .text-center.mt-5(v-if="getModalMsgState.btns")
      b-button(v-for="(btn, ind) in getModalMsgState.btns", :key="ind", variant="outline-primary", size="lg", @click.stop="btn.action()")
        .px-5 {{ btn.text }}
    div(slot="modal-footer")

  Footer
  b-modal#registrationModal(modal-class="modal-signin", ref="registrationModal")
    component(:is="activeComponent", @close="$refs.registrationModal.hide()")
    div(slot="modal-footer")
  v-snackbar(v-model="getSnackbarState.toggle", :color="getSnackbarState.color", :timeout="6000", top, right) {{ getSnackbarState.text }}
    v-btn(dark, flat, @click="$store.dispatch('setSnackbar', { toggle: false })") Закрыть
  .cookie-note(:class="{ 'cookie-note--active': showCookie }")
    .container.cookie-note__container
      .cookie-note__box
        p.cookie-note__message Сайт tamtem.ru использует файлы#[a(href="/files/politic.doc") cookie], чтобы сделать пользование сайтом проще.
        button.cookie-note__btn(@click.prevent="saveCookies") Ок
</template>

<script>
import Cookies from "js-cookie"

// import modalWrapper from "./GeneralComponents/components/modal-wrapper";
import Loader from "./GeneralComponents/components/Loader"
import newheader from "./GeneralComponents/components/Header"
import LkHeader from "./GeneralComponents/components/LkHeader"
import LkPanel from "./GeneralComponents/components/LkPanel"
import SliderCategories from "./GeneralComponents/components/SliderCategories"
import Footer from "./GeneralComponents/components/Footer"

import filtersItem from "./GeneralComponents/components/filtersitem"

import authorizationForm from "./Auth/forms/authorizationForm"
import registrationForm from "./Auth/forms/registrationForm"
import passwordResetForm from "./Auth/forms/passwordResetForm"
import registrationSuccessForm from "./Auth/forms/registrationSuccessForm"
import resetPasswordSuccessForm from "./Auth/forms/resetPasswordSuccessForm"
import pendingPopUp from "./GeneralComponents/components/pendingPopUp"
import chosenPopUp from "./GeneralComponents/components/chosenPopUp"

import { mapGetters, mapActions } from "vuex"
import { log } from "util"
export default {
  components: {
    // modalWrapper,
    Loader,
    newheader,
    filtersItem,
    Footer,
    authorizationForm,
    registrationForm,
    passwordResetForm,
    registrationSuccessForm,
    resetPasswordSuccessForm,
    LkHeader,
    LkPanel,
    SliderCategories,
    pendingPopUp,
    chosenPopUp
  },
  name: "APP",
  data() {
    return {
      componentsList: ["authorizationForm", "registrationForm", "passwordResetForm", "registrationSuccessForm", "resetPasswordSuccessForm", "pendingPopUp", "chosenPopUp"],
      activeComponent: "authorizationForm",
      pageTitle: "Main page title",
      breadcrumbs: [],
      supportvisible: false,
      showCookie: false
    }
  },
  created() {
    this.checkCookies()
    this.checkSuccessRegistration()
    this.checkSuccessPasswordReset()
    this.checkSignin()
    this.checkMark()
    this.$root.$on("showForm", (payload) => {
      this.activeComponent = payload
      this.$refs.registrationModal.show()
    })
    this.$root.$on("closeForm", (payload) => {
      this.$refs.registrationModal.hide()
    })
    if (this.$route.query.unsubscribed) {
      this.$router.replace({
        name: "unsub",
        query: { unsub: this.$route.query.unsubscribed }
      })
    }
  },
  beforeDestroy() {
    this.$root.off("showForm")
  },
  methods: {
    saveCookies() {
      Cookies.set("cookiesInfo", true, { expires: 365 })
      this.checkCookies()
    },
    checkCookies() {
      this.showCookie = !Cookies.get("cookiesInfo")
      if (!this.showCookie) {
        Cookies.set("cookiesInfo", true, { expires: 365 })
      }
    },
    checkSuccessPasswordReset() {
      if (this.$route.fullPath.includes("reset-password=true") && !this.$root.profile) {
        // вырезаем из роута метку
        this.$router.replace({ name: this.$route.name })
        // // показать форму успешного завершения
        this.activeComponent = "resetPasswordSuccessForm"
        this.$nextTick(() => {
          this.$refs.registrationModal.show()
        })
      }
    },
    checkSuccessRegistration() {
      if (this.$route.fullPath.includes("registration=true") && !this.$root.profile) {
        // вырезаем из роута метку
        this.$router.replace({ name: this.$route.name })
        // // показать форму успешного завершения
        this.activeComponent = "registrationSuccessForm"
        this.$nextTick(() => {
          this.$refs.registrationModal.show()
        })
      }
    },
    checkSignin() {
      if (this.$route.fullPath.includes("destination=regorg") && !this.$root.profile) {
        // вырезаем из роута метку
        this.$router.replace({ name: this.$route.name })
        // показать форму входа
        this.activeComponent = "registrationForm"
        this.$nextTick(() => {
          this.$refs.registrationModal.show()
        })
      }
    },
    checkMark() {
      if ((this.$route.fullPath.includes("itm=signup") || this.$route.fullPath.includes("itm=signin")) && !this.$root.profile) {
        console.log(this.$route.fullPath)
        // показать форму
        this.$route.fullPath.includes("itm=signup") ? (this.activeComponent = "registrationForm") : "authorizationForm"
        // вырезаем из роута метку
        this.$router.replace({ name: this.$route.name })

        this.$nextTick(() => {
          this.$refs.registrationModal.show()
        })
      }
    },
    hideregistrationModal() {
      this.$refs.registrationModal.hide()
    }
  },
  computed: {
    ...mapGetters(["getModalMsgState", "getSnackbarState", "getLoadingState"]),
    isFiltersVisible() {
      // массив имен роутов на которых видны фильтры
      const routesArray = ["b"]
      // ищем имя текущего роута в массиве
      return routesArray.find((item) => {
        return item == this.$route.name
      })
    }
  },
  mounted() {
    if (window.isDevMode) {
      console.log("%cRoute: %O", "color:gray;", this.$route)
    }
  }
}
</script>

<style>
@import "../../../sass/main.css";
@import "../../../sass/style.css";
@import "../../../sass/settings.css";

.custom__main {
  padding-bottom: 0;
  margin-bottom: 40px;
  background: url("/images/back.png") #fff no-repeat;
}
@media all and (max-width: 767px) {
	.custom__main {
		background: #fff;
	}
}
</style>

<style lang="scss" scope>
@import "../../../sass/styleguide.scss";
@import "../../../sass/mixins.scss";
body {
	color: #000;
	&.body-overlay {
		overflow: hidden;
		height: 100vh;
		width: 100vw;
		&::before {
			content: "";
			position: absolute;
			width: 100%;
			height: 100vh;
			z-index: 3;
			transition: 0.8s;
			background: transparentize(#000, 0.5);
		}
		&--transparent {
			overflow: hidden;
			height: 100vh;
			width: 100vw;
			&::before {
				content: "";
				position: absolute;
				width: 100%;
				height: 100vh;
				z-index: 2;
				background: transparent;
			}
		}
	}
}
.error-message {
	font-size: 14px;
	font-family: Tahoma;
	background: #fb5b32;
	color: #fff;
	padding: 10px;
	position: fixed;
	width: 100%;
	left: 0;
	top: 0;
	z-index: 9999;
}
.new-bid-md {
	display: none;
	text-align: center;
	position: fixed;
	width: 100%;
	z-index: 1;
	bottom: 3rem;
	@include media-breakpoint-down(md) {
		display: block;
	}
	.btn-newbid {
		font: $font-semibold;
		font-size: 14px;
		// height: 4.2rem;
		height: 47px;
		line-height: 4.2rem;
		padding: 0 calc(#{$grid-gutter-width} / 2);
		// font-size: 1.3rem;
		color: $color-base-light;
		background-color: $color-base-accent;
		border: none;
		&:hover,
		&:focus,
		&:active {
			background-color: $color-base-accent;
			box-shadow: none;
		}
		@include media-breakpoint-down(sm) {
			width: 100%;
			border-radius: 0;
		}
	}
}
.cookie-note {
	position: fixed;
	bottom: 0;
	width: 100%;
	background: #f6f6f6;
	font-family: $font-family;
	z-index: 5;
	height: 0;
	overflow: hidden;
	transform: translateY(-100%);
	transition: 0.3s;
	&--active {
		height: auto;
		transform: translateY(0);
	}
	&__box {
		display: flex;
		align-items: center;
		padding: 10px 0;
	}
	&__message {
		font-size: 14px;
		line-height: 19px;
		margin-right: 30px;
		@media (max-width: 425px) {
			margin-right: 0;
			// text-align: center;
			font-size: 11px;
			line-height: 16px;
		}
		br {
			@media (min-width: 768px) {
				display: none;
			}
		}
		a {
			color: $color-base-accent;
			text-decoration: underline;
			@media (max-width: 768px) {
				margin-top: 8px;
				display: inline-block;
			}
		}
	}
	&__btn {
		display: flex;
		color: #ffffff;
		background-color: $color-base-accent;
		border-radius: 32px;
		width: 93px;
		height: 43px;
		justify-content: center;
		align-items: center;
		margin-left: auto;
		flex-shrink: 0;
		@media (max-width: 425px) {
			margin: 0;
			font-size: 12px;
			height: 27px;
			width: 57px;
		}
	}
}
</style>
