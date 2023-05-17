<template lang="pug">
v-app
	newheader
	subheader(v-if="isSubheaderVisible")
	//- filters-item(v-if="isFiltersVisible")

	.error-message(v-if="$root.errorCode == 429")
		|С вашего IP поступает слишком много запросов! Обновите страницу через несколько минут.
	.error-message(v-if="$root.errorCode == true")
		|Ошибка соединения с сетью или плохое соединение.
	
	main.main(
		role="main" 
		:class="'main-'+ $route.name.replace('.', '-')"
	)
		.content-wrapper
			router-view
			Loader(v-if='getLoadingState')
		.new-bid-md
				router-link(
					v-if="$route.name == 'bids.list'"
					:to="{name:'new.bid'}"
					class='btn btn-newbid'
				)
					<svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
						<line y1="15.5" x2="31" y2="15.5" stroke="white" stroke-width="3"/>
						<line x1="15.5" y1="31" x2="15.5" stroke="white" stroke-width="3"/>
					</svg>
		.new-bid-md
				router-link(
					v-if="$route.name == 'sells.list'"
					:to="{name:'new.sell'}"
					class='btn btn-newbid'
				)
					<svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
						<line y1="15.5" x2="31" y2="15.5" stroke="white" stroke-width="3"/>
						<line x1="15.5" y1="31" x2="15.5" stroke="white" stroke-width="3"/>
					</svg>

	b-modal#modalMsg(
		modal-class='modal-msg'
		:size='getModalMsgState.size'
		v-model='getModalMsgState.toggle'
		ref='modalMsg'
	)
		template(slot='modal-title')
			.text-center.py-5
				logoheader
			hr
		div(v-html='getModalMsgState.content')
		.text-center.mt-5(v-if='getModalMsgState.btns')
			b-button(
				v-for='(btn, ind) in getModalMsgState.btns'
				:key='ind'
				variant='outline-primary'
				size='lg'
				@click.stop='btn.action()'
			)
				.px-5 {{btn.text}}
		div(slot='modal-footer')

	Footer
	b-modal#registrationModal(
		modal-class="modal-signin"
		ref="registrationModal"
	)
		component(:is="activeComponent" @close="$refs.registrationModal.hide();")
		div(slot='modal-footer')
	v-snackbar(
		v-model="getSnackbarState.toggle"
		:color="getSnackbarState.color"
		:timeout="6000"
		top=true
		right=true
	) {{getSnackbarState.text}}
		v-btn(
			dark
			flat
			@click="$store.dispatch('setSnackbar', {toggle: false})"
		) Закрыть
	div.cookie-note(:class="{'cookie-note--active' : showCookie}")
		.container.cookie-note__container
			.cookie-note__box
				p.cookie-note__message Сайт tamtem.ru использует файлы #[a(href="/files/politic.doc")  cookie], чтобы сделать пользование сайтом проще.
				button.cookie-note__btn(@click.prevent="saveCookies") Ок
</template>

<script>
import Cookies from "js-cookie";

// import modalWrapper from "./GeneralComponents/components/modal-wrapper";
import Loader from "./GeneralComponents/components/Loader";
import newheader from "./GeneralComponents/components/Header";
import Footer from "./GeneralComponents/components/Footer";

import filtersItem from "./GeneralComponents/components/filtersitem";
import subheader from "./GeneralComponents/components/Subheader";

import authorizationForm from "./Auth/forms/authorizationForm";
import registrationForm from "./Auth/forms/registrationForm";
import passwordResetForm from "./Auth/forms/passwordResetForm";
import registrationSuccessForm from "./Auth/forms/registrationSuccessForm";
import resetPasswordSuccessForm from "./Auth/forms/resetPasswordSuccessForm";

import { mapGetters, mapActions } from "vuex";
import { log } from 'util';
export default {
	components: {
		// modalWrapper,
		Loader,
		newheader,
		filtersItem,
		subheader,
		Footer,
		authorizationForm,
		registrationForm,
		passwordResetForm,
		registrationSuccessForm,
		resetPasswordSuccessForm
	},
	name: "APP",
	data() {
		return {
			componentsList: [
				"authorizationForm",
				"registrationForm",
				"passwordResetForm",
				"registrationSuccessForm",
				"resetPasswordSuccessForm"
			],
			activeComponent: "authorizationForm",
			pageTitle: "Main page title",
			breadcrumbs: [],
			supportvisible: false,
			showCookie: false
		};
	},
	created() {
		this.checkCookies();
		this.checkSuccessRegistration();
		this.checkSuccessPasswordReset();
		this.checkSignin();
		this.checkMark();
		this.$root.$on("showForm", payload => {
			this.activeComponent = payload;
			this.$refs.registrationModal.show();
		});
		if(this.$route.query.unsubscribed) {
			this.$router.replace({name: 'unsub', query:{'unsub':this.$route.query.unsubscribed} })
		}
	},
	beforeDestroy() {
		this.$root.off("showForm");
	},
	methods: {
		saveCookies() {
			Cookies.set("cookiesInfo", true, { expires: 365 });
			this.checkCookies();
		},
		checkCookies() {
			this.showCookie = !Cookies.get("cookiesInfo");
			if (!this.showCookie) {
				Cookies.set("cookiesInfo", true, { expires: 365 });
			}
		},
		checkSuccessPasswordReset() {
			if (
				this.$route.fullPath.includes("reset-password=true") &&
				!this.$root.profile
			) {
				// вырезаем из роута метку
				this.$router.replace({ name: this.$route.name });
				// // показать форму успешного завершения
				this.activeComponent = "resetPasswordSuccessForm";
				this.$nextTick(()=>{

					this.$refs.registrationModal.show();
				})
			}
		},
		checkSuccessRegistration() {
			if (
				this.$route.fullPath.includes("registration=true") &&
				!this.$root.profile
			) {
				// вырезаем из роута метку
				this.$router.replace({ name: this.$route.name });
				// // показать форму успешного завершения
				this.activeComponent = "registrationSuccessForm";
				this.$nextTick(()=>{

					this.$refs.registrationModal.show();
				})
			}
		},
		checkSignin() {
			if (
				this.$route.fullPath.includes("destination=regorg") &&
				!this.$root.profile
			) {
				// вырезаем из роута метку
				this.$router.replace({ name: this.$route.name });
				// показать форму входа
				this.activeComponent = "registrationForm";
				this.$nextTick(()=>{

					this.$refs.registrationModal.show();
				})
			}
		},
		checkMark() {
			if (
				(this.$route.fullPath.includes("itm=signup") ||
					this.$route.fullPath.includes("itm=signin")) &&
				!this.$root.profile
			) {
				console.log(this.$route.fullPath);
				// показать форму
				this.$route.fullPath.includes("itm=signup")
					? (this.activeComponent = "registrationForm")
					: "authorizationForm";
				// вырезаем из роута метку
				this.$router.replace({ name: this.$route.name });

				this.$nextTick(()=>{

					this.$refs.registrationModal.show();
				})
			}
		},
		hideregistrationModal() {
			this.$refs.registrationModal.hide();
		}
	},
	computed: {
		...mapGetters(["getModalMsgState", "getSnackbarState", "getLoadingState"]),
		isFiltersVisible() {
			// массив имен роутов на которых видны фильтры
			const routesArray = ["bids.list", "bids.detail", "sells.list", "sells.detail"];
			// ищем имя текущего роута в массиве
			return routesArray.find(item => {
				return item == this.$route.name;
			});
		},
		isSubheaderVisible() {
			// пока что по аналогии с фильтрацией, затем вычисление видимости надо будет переделать. Наверное, этот компонент будет везде, кроме домашней страницы.

			// массив имен роутов, где будет виден субхедер
			const routesArray = ["bids.list", "bids.detail", "sells.list", "sells.detail"];
			return routesArray.find(item => {
				return item == this.$route.name;
			});
		}
	},
	mounted() {
		if (window.isDevMode) {
			console.log("%cRoute: %O", "color:gray;", this.$route);
		}
	}
};
</script>
<style>
.body-overflow {
	height: 100vh;
	overflow-y: hidden;
}
.main {
	background-color: #fff;
	padding-top: 42px;
	padding-bottom: 30px;
	flex-grow: 2;
	/* z-index: -1; */
}
.main-supplier {
	padding: 0;
}
.main-homepage {
	padding: 0;
}
.main-bids-detail {
	padding-top: 0;
}
.main-new-bid,
.main-new-sell,
.main-sells-detail,
.main-bids-list,
.main-sells-list {
	padding-top: 0;
}
.main-page404 {
	padding-top: 0;
	padding-bottom: 0;
}
.v-snack {
	z-index: 10000;
}
.label_39 {
	z-index: 1 !important;
}
</style>
<style lang="scss">
.component-fade-enter-active,
.component-fade-leave-active {
	transition: opacity 0.3s ease;
}
.component-fade-enter, .component-fade-leave-to
/* .component-fade-leave-active below version 2.1.8 */ {
	opacity: 0;
}

.modal-signin {
	@media (max-width: 320px) {
		padding: 0 !important;
	}
	.modal-header {
		.close {
			font: 400 20px/24px "Montserrat", sans-serif;
			background: #cecece;
			display: flex;
			justify-content: center;
			align-items: center;
			transform: none;
			text-shadow: none;
			font-weight: 300;
			border-radius: 6px;
			min-width: 24px;
			min-height: 24px;
			color: #fff;
			&::before,
			&::after {
				display: none;
			}
			&:hover {
				background-color: #27d066;
			}
		}
	}
	.modal-dialog {
		@media (min-width: 720px) {
			width: 457px;
		}
		@media (min-width: 576px) {
			margin-top: 100px;
		}
		@media (max-width: 320px) {
			margin: 0;
		}
	}
	.modal-content {
		border-radius: 10px;
		@media (max-width: 320px) {
			border: none;
			border-radius: 0;
		}
	}
	.modal-body {
		@media (min-width: 720px) {
			padding: 0 86px;
		}
		@media (max-width: 320px) {
			padding: 0 42px;
		}
	}
}
.modal-header-custom {
	.close {
		font: 400 20px/24px "Montserrat", sans-serif;
		background: #cecece;
		display: flex;
		justify-content: center;
		align-items: center;
		transform: none;
		text-shadow: none;
		font-weight: 300;
		border-radius: 6px;
		min-width: 24px;
		min-height: 24px;
		color: #fff;
		&::before,
		&::after {
			display: none;
		}
		&:hover {
			background-color: #27d066;
		}
	}
}
.modal-content-custom {
	border-radius: 10px;
	@media (max-width: 320px) {
		border: none;
		border-radius: 0;
	}
}
.modal-body-custom {
	@media (min-width: 720px) {
		padding: 0 86px;
	}
	@media (max-width: 320px) {
		padding: 0 42px;
	}
}
.custom-modal-wide {
	.modal-dialog {
		max-width: 862px;
		@media (max-width: 870px) {
			width: 750px;
		}
		@media (max-width: 768px) {
			max-width: 90%;
		}
		@media (max-width: 576px) {
			margin: 25px auto;
		}
		@media (min-width: 576px) {
			margin-top: 100px;
		}
		@media (max-width: 425px) {
			margin: 0;
			max-width: 100%;
		}
	}
}
.custom-modal {
	.newbid-categories {
		padding: 0 37px;
		@media (max-width: 525px) {
			padding: 0 20px;
		}
		@media (max-width: 425px) {
			padding: 0 10px;
		}
	}
	.newbid-modal-centerbody {
		min-height: 490px;
		display: flex;
		align-items: center;
	}
}
.custom-modal {
	.modal-header {
		.close {
			font: 400 20px/24px "Montserrat", sans-serif;
			background: #cecece;
			display: flex;
			justify-content: center;
			align-items: center;
			transform: none;
			text-shadow: none;
			font-weight: 300;
			border-radius: 6px;
			min-width: 24px;
			min-height: 24px;
			color: #fff;
			&::before,
			&::after {
				display: none;
			}
			&:hover {
				background-color: #27d066;
			}
		}
	}
	.modal-dialog {
		@media (min-width: 720px) {
			width: 457px;
		}
		@media (min-width: 576px) {
			margin-top: 100px;
		}
		@media (max-width: 320px) {
			margin: 0;
		}
	}
	.modal-content {
		border-radius: 10px;
		@media (max-width: 320px) {
			border: none;
			border-radius: 0;
		}
	}
	.modal-body {
		@media (min-width: 720px) {
			padding: 0 86px;
		}
		@media (max-width: 320px) {
			padding: 0 42px;
		}
	}
	.wider-body {
		@media(min-width: 720px) {
			padding: 0 70px;
		}
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
			background-image: url(../../../images/blur.png);
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
	text-align: center;
	position: fixed;
	z-index: 3;
	bottom: 10%;
	right: 20px;
	@media(min-width: 769px) {
		display: none;
	}

	.btn-newbid {
		width: 63px;
		height: 63px;
		background-color: #2FC06E;
		border: none;
		border-radius: 50%;
		display: flex;
		justify-content: center;
		align-items: center;
		&:hover,
		&:focus,
		&:active {
			background-color: $color-base-accent;
			box-shadow: none;
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
