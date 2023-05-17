<template lang="pug">
	section.subheader.subheader--fixed
		div.container.subheader__container
			div.subheader__inner
				nav.subheader__types
					a(href="/bids" :class="{'is-active' : activeType == 'buy'}").subheader__type Заказы
					a(href="/!sales" :class="{'is-active' : activeType == 'sell'}").subheader__type Предложения
				section.subheader__profile
					div.subheader__autorize(v-if="!$root.isAuth")
						a(
							href="autorization"
							@click.prevent="showForms('authorizationForm');"
						).subheader__autorize-login Войти
						span &nbsp;/&nbsp;
						a(
							href="registration"
							@click.prevent="showForms('registrationForm');"
						).subheader__autorize-register Зарегистрироваться
					div.subheader__logged.s-p-log(v-else)
						div.s-p-log__fav
						div.s-p-log__user(@click.prevent="toggleProfile")
							p.s-p-log__user-icon
								span(v-if="$root.unreadMsg").s-p-log__user-badge {{$root.unreadMsg}}
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M20.1667 22C20.1667 22 22 22 22 20.1667C22 18.3333 20.1667 12.8333 11 12.8333C1.83333 12.8333 0 18.3333 0 20.1667C0 22 1.83333 22 1.83333 22H20.1667ZM1.8425 20.2693V20.2657V20.2693ZM1.87367 20.1667H20.1263C20.1349 20.1657 20.1435 20.1644 20.152 20.163L20.1667 20.1593C20.1648 19.7083 19.8843 18.3517 18.6413 17.1087C17.446 15.9133 15.1965 14.6667 11 14.6667C6.80167 14.6667 4.554 15.9133 3.35867 17.1087C2.11567 18.3517 1.837 19.7083 1.83333 20.1593C1.84675 20.1619 1.8602 20.1644 1.87367 20.1667ZM20.1593 20.2693V20.2657V20.2693ZM11 9.16667C11.9725 9.16667 12.9051 8.78036 13.5927 8.09272C14.2804 7.40509 14.6667 6.47246 14.6667 5.5C14.6667 4.52754 14.2804 3.59491 13.5927 2.90727C12.9051 2.21964 11.9725 1.83333 11 1.83333C10.0275 1.83333 9.09491 2.21964 8.40728 2.90727C7.71964 3.59491 7.33333 4.52754 7.33333 5.5C7.33333 6.47246 7.71964 7.40509 8.40728 8.09272C9.09491 8.78036 10.0275 9.16667 11 9.16667ZM16.5 5.5C16.5 6.95869 15.9205 8.35764 14.8891 9.38909C13.8576 10.4205 12.4587 11 11 11C9.54131 11 8.14236 10.4205 7.11091 9.38909C6.07946 8.35764 5.5 6.95869 5.5 5.5C5.5 4.04131 6.07946 2.64236 7.11091 1.61091C8.14236 0.579463 9.54131 0 11 0C12.4587 0 13.8576 0.579463 14.8891 1.61091C15.9205 2.64236 16.5 4.04131 16.5 5.5Z" fill="#2FC06E"/>
								</svg>

							p.s-p-log__user-name {{profile.profile.name}}
							div.s-p-log__modal(v-if="logMenuShow" v-click-outside="hideProfileModal")
								div.s-p-log__modal-inner
									router-link(:to="{name: 'lk.profile'}").s-p-log__modal-link
										img(src="images/icon-account.svg" alt="")
										span Личный кабинет
									a(href="logout" @click.prevent="logout").s-p-log__modal-link
										img(src="images/icon-exit.svg" alt="")
										span Выход
		div.subheader__categories
			category-carousel
</template>

<script>
import categoryCarousel from "../../GeneralComponents/components/CategoryCarousel";
import { mapState, mapActions } from "vuex";
export default {
	data() {
		return {
			logMenuShow: false,
			activeType: ""
		}
	},
	components: {
		categoryCarousel
	},
	methods: {
		showForms(form){
			// this.hideModalmenu();
			// this.hideProfileModal();
			this.$root.$emit('showForm',form);
		},
		toggleProfile() {
			this.logMenuShow = !this.logMenuShow;
		},
		hideProfileModal() {
			this.logMenuShow = false;
		},
		logout(e) {
			e.preventDefault;
			this.$root.logout();
			if (this.$route.name != "homepage") {
				this.$router.push({
					name: "homepage"
				});
			}
		},
		defineTypePage() {
			this.activeType = this.$route.meta.type
		},
	},
	computed: {
		...mapState(['profile']),
		hasNotifications(){
			return (this.profile.unreadMsg>0 || (this.profile.notifications && (this.profile.notifications.unreaded_owner_deal>0 || this.profile.notifications.unreaded_owner_response>0)))
		}
	},
	watch: {
		$route(to, from) {
			this.defineTypePage();
		}
	},
	created() {
		this.defineTypePage();
	}
}
</script>

<style lang="scss" scoped>
.subheader {
	$color-grey-base: #888888;
	&__inner {
		display: flex;
		justify-content: space-between;
		padding-top: 42px;
		padding-bottom: 26px;
	}
	&__types {
		@media (max-width: 640px) {
			width: 100%;
			display: flex;
			justify-content: space-around;
		}
	}
	&__type {
		font-style: normal;
		font-weight: normal;
		font-size: 16px;
		line-height: 20px;
		color: $color-grey-base;
		text-decoration: none;
		position: relative;
		&.is-active {
			color: #000000;
			@media (max-width: 640px) {
				color: #fff;
			}
			&::after {
				content: "";
				width: 100%;
				height: 2px;
				background-color: currentColor;
				position: absolute;
				left: 0;
				top: calc(100% + 5px);
				@media (max-width: 640px) {
					display: none;
				}
			}
		}
		&:not(:last-child) {
			margin-right: 18px;
		}
		@media (max-width: 640px) {
			color: rgba(255, 255, 255, 0.55);
			font-weight: bold;
			font-size: 20px;
			line-height: 24px;
		}
		
	}
	&__profile {
		@media (max-width: 640px) {
			display: none;
		}
	}
	&__autorize {
		display: flex;
		color: $color-grey-base;
		font-size: 16px;
		line-height: 20px;
		a {
			text-decoration: none;
			color: inherit;
		}
		@media (max-width: 640px) {
			display: none;
		}
	}
	&__categories {
		@media (max-width: 640px) {
			display: none;
		}
	}
	&--static {
		position: static;
	}
	&--fixed {
		position: absolute;
		z-index: 2;
		top: 73px;
		width: 100%;
		@media (max-width: 640px) {
			background: #2FC06E;
			top: 60px;
		}
	}
}

.s-p-log {
	&__user {
		display: flex;
		align-items: center;
		position: relative;
		cursor: pointer;
	}
	&__user-icon {
		margin-right: 10px;
		position: relative;
	}
	&__user-name {
		font-weight: 500;
		font-size: 14px;
		line-height: 17px;
		color: #2FC06E;
	}
	&__user-badge {
		border-radius: 50%;
		width: 22px;
		height: 22px;
		background: #18A0FB;
		color: #fff;
		font-weight: 600;
		font-size: 14px;
		line-height: 17px;
		position: absolute;
		bottom: calc(100% - 10px);
		left: calc(100% - 10px);
		display: flex;
		justify-content: center;
		align-items: center;
	}
	&__modal {
		position: absolute;
		background: #FFFFFF;
		box-shadow: 0px 2px 8px rgba(69, 91, 99, 0.18);
		border-radius: 10px;
		padding: 27px 15px 30px;
		top: calc(100% + 10px);
		right: 0;
		width: 310px;
		z-index: 10;
		&-inner {
			display: flex;
			flex-direction: column;
		}
		&-link {
			font-weight: normal;
			font-size: 15px;
			line-height: 18px;
			color: #222222;
			display: flex;
			align-items: center;
			img {
				margin-right: 15px;
			}
			&:first-child {
				padding-bottom: 23px;
				margin-bottom: 23px;
				position: relative;
				&::after {
					content: "";
					width: 233px;
					height: 1px;
					background-color: #888888;
					position: absolute;
					bottom: 0;
					left: 34px;
				}
			}
		}
	}
}
</style>