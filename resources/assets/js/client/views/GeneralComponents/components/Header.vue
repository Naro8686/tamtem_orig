<template lang="pug">
header(:class="{'mainheader--homepage' : isHomePage}").mainheader
	.container.mainheader__container
		router-link.logo(:to="{name:'homepage'}")
			logoheader(variant="homepage" v-if="isHomePage")
			logoheader(variant="white" v-else)
		nav.mainmenu(v-click-outside="hideModalmenu")
			a.mainmenu__burger(@click="showModalmenu()")
				svg(xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 91 91")
					g(fill="#ffffff")
						path(d="M85.713 12.142H5.861c-2.305 0-4.174-1.869-4.174-4.176 0-2.305 1.869-4.174 4.174-4.174h79.852c2.305 0 4.174 1.869 4.174 4.174 0 2.307-1.869 4.176-4.174 4.176zM85.713 49.858H5.861c-2.305 0-4.174-1.869-4.174-4.175 0-2.307 1.869-4.176 4.174-4.176h79.852c2.305 0 4.174 1.869 4.174 4.176 0 2.306-1.869 4.175-4.174 4.175zM85.713 87.571H5.861c-2.305 0-4.174-1.869-4.174-4.176s1.869-4.176 4.174-4.176h79.852c2.305 0 4.174 1.869 4.174 4.176s-1.869 4.176-4.174 4.176z")
			ul.mainmenu__list.notablet
				li.mainmenu__item
					a(href="/" @click.prevent="goToUrlWithReachGoal('onas', '/')" :class="{ 'active' : $route.name === 'homepage' }").mainmenu__link.animation-link-underline О нас
				li.mainmenu__item
					a(href="/!sales" @click.prevent="goToUrlWithReachGoal('optovieob', '/!sales')" :class="{ 'active' : $route.name === 'sells.list' }").mainmenu__link.animation-link-underline Оптовые объявления
				li.mainmenu__item
					a(href="/faq").mainmenu__link.animation-link-underline Помощь
			section.modalmenu(
				:class="{'modalmenu--active' : modalmenuShow}"
			)
				header.modalmenu__header
					router-link.modalmenu__logo(:to="{name: 'homepage'}")
						logoheader(variant="modalmenu")
					.modalmenu__close
						a(href="close").modalmenu__logo(@click.prevent="hideModalmenu()")
							svg(xmlns="http://www.w3.org/2000/svg" width="15.557" height="15.556" viewBox="0 0 15.557 15.556")
								g(fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2")
									path(d="M14.142 1.414L1.414 14.1419")
									path(d="M1.414 1.414l12.728 12.7279")
	
				nav.modalmenu__navigation
					ul.modalmenu__menu-list
						li.modalmenu__menu-item
							a(@click="hideModalmenu()" href="/!sales" v-if="!isSellsPage").modalmenu__menu-link Оптовые объявления
							//a(@click="hideModalmenu()" href="/bids" v-else).modalmenu__menu-link Заказы
						li.modalmenu__menu-item
							a(href="/faq" @click="tofaq();").modalmenu__menu-link Помощь
						li.modalmenu__menu-item
							a(href="http://blog.tamtem.ru/").modalmenu__menu-link Блог
				footer.modalmenu__footer
					nav.modalmenu__footer-nav
						a(href="/contact").modalmenu__footer-contact Поддержка
						a(href="tel:+79307202300").modalmenu__footer-phone +7 930 720 23 00
						p.modalmenu__footer-phonelabel Бесплатно по России
						a(href="mailto:team@tamtem.ru").modalmenu__footer-mail team@tamtem.ru
		section.curr(v-if="!isHomePage")
			div.curr__main
				p.curr__main-icon
					img(src="images/icon-ru.svg" alt="")
				p.curr__main-symb ₽
				button(@click.prevent="toggleCurrModal").curr__main-arrow
					<svg width="8" height="6" viewBox="0 0 8 6" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M3.67063 5.8272C3.70733 5.88049 3.75644 5.92407 3.81372 5.95417C3.871 5.98427 3.93475 6 3.99946 6C4.06417 6 4.12791 5.98427 4.1852 5.95417C4.24248 5.92407 4.29158 5.88049 4.32828 5.8272L7.92857 0.627557C7.97024 0.567584 7.99468 0.497339 7.99923 0.424453C8.00377 0.351568 7.98826 0.27883 7.95436 0.214143C7.92047 0.149456 7.86949 0.0952928 7.80697 0.0575393C7.74445 0.0197859 7.67278 -0.00011453 7.59974 4.95827e-07H0.399176C0.326308 0.000301439 0.2549 0.0204577 0.192632 0.0583016C0.130364 0.0961456 0.079592 0.150245 0.0457757 0.214783C0.0119594 0.279321 -0.00362155 0.351854 0.000708374 0.424584C0.0050383 0.497313 0.0291153 0.567486 0.0703501 0.627557L3.67063 5.8272Z" fill="white"/>
					</svg>

			div.curr__modal(v-if="currModalView" v-click-outside="hideCurrModal")
				ul.curr__modal-list
					li.curr__modal-item
						p.curr__modal-icon
							img(src="images/icon-ru.svg" alt="")
						p.curr__modal-symb ₽
						p.curr__modal-name Russia (Россия)
					li.curr__modal-item
						p.curr__modal-icon
							img(src="images/icon-br.svg" alt="")
						p.curr__modal-symb Br
						p.curr__modal-name Belarus (Беларусь)
		section.createorder.mainheader__createorder(v-if="!isHomePage")
			template(v-if="isSellsPage")
				a(
					@click="toSellCreate()"
					href="/newsell"
				).createorder__btn 
					span Разместить объявление
			template(v-else)
				a(
					@click="toBidCreate()"
					href="/newbid"
				).createorder__btn 
					span Разместить заказ

		section.mainheader__account.head-acc
			div.profile__section(v-if="!$root.isAuth")
					a(href="/lk/profile").profile__box-homepage
						div.profile__box-homepage-pic
							img(src="images/home/log-in.png" alt="log in")
						p.profile__box-homepage-txt Войти
				
			div.head-acc__logged(v-else)
				div.head-acc__favorites
					router-link(to='lk/favorites')
						<svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M7.50031 19.5L15.0006 24V6C15.0006 5.20435 14.6845 4.44129 14.1219 3.87868C13.5593 3.31607 12.7962 3 12.0005 3H3.00013C2.20444 3 1.44135 3.31607 0.878716 3.87868C0.316084 4.44129 0 5.20435 0 6V24L7.50031 19.5ZM1.50006 21.351L7.50031 17.751L13.5006 21.351V6C13.5006 5.60218 13.3425 5.22064 13.0612 4.93934C12.7799 4.65804 12.3983 4.5 12.0005 4.5H3.00013C2.60228 4.5 2.22074 4.65804 1.93942 4.93934C1.6581 5.22064 1.50006 5.60218 1.50006 6V21.351Z" fill="#2FC06E"/>
							<path d="M18.001 21L16.5009 20.1V3C16.5009 2.60218 16.3428 2.22064 16.0615 1.93934C15.7802 1.65804 15.3987 1.5 15.0008 1.5H3.40234C3.66565 1.04395 4.04437 0.665247 4.50043 0.401943C4.95649 0.138639 5.47383 1.33777e-05 6.00045 0L15.0008 0C15.7965 0 16.5596 0.316071 17.1222 0.87868C17.6849 1.44129 18.001 2.20435 18.001 3V21Z" fill="#2FC06E"/>
						</svg>
						template(v-if='favCount > 0')
							span.head-acc__favorites-count {{ favCount }}
				div.head-acc__user(@click.stop="toggleProfile")
					a
						span(v-if="$root.unreadMsg").head-acc__user-badge {{$root.unreadMsg}}
						<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M20.1667 22C20.1667 22 22 22 22 20.1667C22 18.3333 20.1667 12.8333 11 12.8333C1.83333 12.8333 0 18.3333 0 20.1667C0 22 1.83333 22 1.83333 22H20.1667ZM1.8425 20.2693V20.2657V20.2693ZM1.87367 20.1667H20.1263C20.1349 20.1657 20.1435 20.1644 20.152 20.163L20.1667 20.1593C20.1648 19.7083 19.8843 18.3517 18.6413 17.1087C17.446 15.9133 15.1965 14.6667 11 14.6667C6.80167 14.6667 4.554 15.9133 3.35867 17.1087C2.11567 18.3517 1.837 19.7083 1.83333 20.1593C1.84675 20.1619 1.8602 20.1644 1.87367 20.1667ZM20.1593 20.2693V20.2657V20.2693ZM11 9.16667C11.9725 9.16667 12.9051 8.78036 13.5927 8.09272C14.2804 7.40509 14.6667 6.47246 14.6667 5.5C14.6667 4.52754 14.2804 3.59491 13.5927 2.90727C12.9051 2.21964 11.9725 1.83333 11 1.83333C10.0275 1.83333 9.09491 2.21964 8.40728 2.90727C7.71964 3.59491 7.33333 4.52754 7.33333 5.5C7.33333 6.47246 7.71964 7.40509 8.40728 8.09272C9.09491 8.78036 10.0275 9.16667 11 9.16667ZM16.5 5.5C16.5 6.95869 15.9205 8.35764 14.8891 9.38909C13.8576 10.4205 12.4587 11 11 11C9.54131 11 8.14236 10.4205 7.11091 9.38909C6.07946 8.35764 5.5 6.95869 5.5 5.5C5.5 4.04131 6.07946 2.64236 7.11091 1.61091C8.14236 0.579463 9.54131 0 11 0C12.4587 0 13.8576 0.579463 14.8891 1.61091C15.9205 2.64236 16.5 4.04131 16.5 5.5Z" fill="#2FC06E"/>
						</svg>
						span.head-acc__account-name {{ profile.profile.name }}
				div.head-acc__modal(v-if="logMenuShow" v-click-outside="hideProfileModal")
					div.head-acc__modal-inner
						router-link(:to="{name: 'lk.deals'}").head-acc__modal-link
							img(src="images/icon-account.svg" alt="")
							span Личный кабинет
						a(href="logout" @click.prevent="logout").head-acc__modal-link
							img(src="images/icon-exit.svg" alt="")
							span Выход
</template>
<script>
import { mapState, mapActions, mapGetters } from "vuex";
export default {
	data() {
		return {
			isProfileNoty: false,
			modalmenuShow: false,
			modalprofileShow: false,
			isSellsPage: false,
			isHomePage: false,
			currModalView: false,
			currentCurr: "ru",
			logMenuShow: false,
		};
	},
	computed: {
		...mapState(['profile']),
		...mapGetters({
			favCount: 'favourites/getFavouritesCount'
		}),
		hasNotifications(){
			return (this.profile.unreadMsg>0 || (this.profile.notifications && (this.profile.notifications.unreaded_owner_deal>0 || this.profile.notifications.unreaded_owner_response>0)))
		}
	},
	watch: {
		$route() {
			this.modalmenuShow = false;
			this.modalprofileShow = false;
			document.body.classList.remove("body-overlay");
		},
		$route(to, from) {
			if(to.meta.type == 'sell') {
				this.isSellsPage = true
			} else {
				this.isSellsPage = false
			}
		},
		$route(to, from) {
			if(to.name == 'homepage') {
				this.isHomePage = true
			} else {
				this.isHomePage = false
			}
		}
	},
	methods: {
		...mapActions("favourites", ["setCount"]),
		toBidsList(){
			event.preventDefault();
			this.yandexReachGoal('zakaz_glavnaya');
			this.googleReachGoal('zakaz_glavnaya');
			this.$router.push({name: 'bids.list'}).catch(()=>{});
		},
		toSellsList(){
			event.preventDefault();
			this.$router.push({name: 'sells.list'}).catch(()=>{});
		},
		tofaq(){
			event.preventDefault();
			this.yandexReachGoal('tamtem_faq');
			this.googleReachGoal('tamtem_faq');
			location.href="/faq";
		},
		toBidCreate(){
			event.preventDefault();
			this.yandexReachGoal('sozdzaknn');
			this.googleReachGoal('sozdzaknn');
			const currentPath = this.$route.name;
			if (currentPath!='new.bid'){
				this.$router.push({name: 'new.bid'}).catch(()=>{});
			}else{
				this.$root.$emit('resetFormCreate');
			}
			
		},
		toSellCreate() {
			this.yandexReachGoal('razmob');
			event.preventDefault();
			const currentPath = this.$route.name;
			if (currentPath != 'new.sell'){
				this.$router.push({name: 'new.sell'}).catch(()=>{});
			} else {
				this.$root.$emit('resetFormCreate');
			}
		},
		showForms(form){
			if (form=='registrationForm'){
				this.yandexReachGoal('tamtem_registration');
				this.googleReachGoal('tamtem_registration');
			}
			if(form=='authorizationForm'){
				this.yandexReachGoal('vhod_glavnaya');
				this.googleReachGoal('vhod_glavnaya');
			} 
			this.hideModalmenu();
			this.hideProfileModal();
			this.$root.$emit('showForm',form);
		},
		showModalmenu() {
			this.modalmenuShow = true;
			const modal = document.querySelector('.modalmenu');
			document.body.classList.add("body-overlay");
			document.scrollingElement.style.overflow = "hidden";
			modal.addEventListener('transitionend', function(e){
				for(let i=0; i< modal.children.length; i++) {
					modal.children[i].classList.add('active')
				}
			})
		},
		hideModalmenu() {
			this.modalmenuShow = false;
			const modal = document.querySelector('.modalmenu');
			document.body.classList.remove("body-overlay");
			document.scrollingElement.style.overflow = "visible";
			modal.addEventListener('transitionend', function(e){
				for(let i=0; i< modal.children.length; i++) {
					modal.children[i].classList.remove('active')
				}
			})
		},
		toggleProfile() {
			// this.modalprofileShow = !this.modalprofileShow;
			this.logMenuShow = !this.logMenuShow;
		},
		toggleCurrModal() {
			this.currModalView = !this.currModalView;
		},
		hideProfileModal() {
			// this.modalprofileShow = false;
			this.logMenuShow = false;
		},
		hideCurrModal() {
			this.currModalView = false;
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
		defineSellPage() {
			if(this.$route.meta.type == 'sell') {
				this.isSellsPage = true
			}
		},
		defineHomePage() {
			if(this.$route.name == 'homepage') {
				this.isHomePage = true
			}
		}
	},
	created() {
		this.defineSellPage();
		this.defineHomePage();
		if (this.$root.isAuth) {
			this.setCount(); // set Favourite count
		}
	}
};
</script>
<style lang="scss">
.container {
	max-width: 1320px;
	// padding-left: 100px;
	// Костыль для нейтрализации бага в vuetify-custom с определением максимальной ширины контейнера.
	@media (max-width: 1440px) {
		max-width: 100vw;
		padding: 0 25px;
		// скорее всего придется внести некоторое разнообразие классов, чтобы на разных страницах работали разные контейнеры
		padding-left: 100px;
	}
	@media (max-width: 1200px) {
		padding: 0 25px;
	}
	@media (max-width: 640px) {
		padding: 0 15px;
	}
}
</style>
<style lang="scss" scoped>
@import "~styleguide";

.mainheader {
	background-color: $color-base-accent;
	padding: 17px 0;
	font: $font-regular;
	font-size: 15px;
	height: 73px;
	@media(max-width: 640px) {
		height: 60px;
		padding: 10px 0;
	}
	&__container {
		display: flex;
		align-items: center;
		height: 100%;
		position: relative;
	}
	&__createorder {
		margin-left: 40px;
		order: 4;
		@media (max-width: 425px) {
			display: none;
		}
	}
	&__profile {
		order: 4;
		margin-left: 1.6%;
		@media (max-width: 425px) {
			margin-left: auto;
		}
	}
	.logo {
		width: 156px;
		// height: 30px;
		order: 1;
		cursor: pointer;
		@media (max-width: 768px) {
            order: 2;
            width: 130px;
		}
		@media (max-width: 640px) {
			order: 3;
			margin-left: auto;
		}
        @media (max-width: 425px) {
            width: 100px;
        }
		& /deep/ svg {
			width: 100%;
			height: auto;
			@media (max-width: 640px) {
				width: 87px;
				height: 25px;
			}
		}
	}
	.mainmenu {
		margin-left: 3.5%;
		margin-right: 10px;
		order: 2;
		@media (max-width: 768px) {
			margin-left: 0;
			order: 1;
		}
		&__list {
			list-style: none;
			display: flex;
			flex-wrap: wrap;
		}
		&__item {
			margin-top: 5px;
			margin-bottom: 5px;
			&:not(:last-child) {
				margin-right: 30px;
			}
		}
		&__link {
			font: $font-medium;
			font-size: 15px;
			line-height: 19px;
			color: $color-text-light;
			text-decoration: none;
			padding-bottom: 3px;
			position: relative;
			&::after {
				background-color: #fff;
			}
			&__text {
				&--blue-text {
					font: $font-semibold;
					font-size: 15px;
					color: $color-agent-link;
				}
				&--white-text{
					font: $font-medium;
					font-size: 15px;
					color: #fff;
				}
			}
			&--active {
				border-bottom: 2px solid $color-text-light;
			}
			&.active {
				font-weight: bold;
			}
		}
		&__burger {
			display: none;
			@media (max-width: 768px) {
				display: block;
			}
		}
	}
	.modalmenu {
		flex-direction: column;
		position: fixed;
		top: 0;
		left: 0;
		background: #fff;
		height: auto;
		width: 100%;
		overflow: hidden;
		z-index: 5;
		transform: translateX(-200%);
		transition: 0.4s cubic-bezier(0.645,  0.045, 0.355, 1.000);
		& > * {
			transform: translateY(-200px);
			opacity: 0;
			transition: 0.5s;
		}
		& > *.active {
			opacity: 1;
			transform: translateY(0);
		}
		a {
			&:hover {
				text-decoration: none;
			}
		}
		&__header {
			padding-top: 20px;
			padding-bottom: 20px;
			padding-left: 40px;
			padding-right: 15px;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
			position: relative;
		}
		
		
		&__logo {
			max-width: 137px;
			@media(max-width: 320px) {
				max-width: 127px;
			}
			img {
				width: 100%;
				height: auto;
			}
			& /deep/ svg {
				width: 100%;
				height: auto;
			}
		}
		&__close {
			cursor: pointer;
			position: absolute;
			top: 50%;
			right: 16px;
			transform: translateY(-50%);
		}
		
		&__menu-list {
			list-style: none;
		}
		&__menu-item {
			padding-top: 10px;
			padding-bottom: 10px;
			border-bottom: 1px solid #e6e6e6;
		}
		&__menu-link {
			display: flex;
			font-weight: 600;
			font-size: 18px;
			line-height: 20px;
			padding: 15px 15px;
			color: #2FC06E;
			justify-content: center;
			flex-shrink: 0;
			
		}
		&__footer {
			&-nav {
				color: #000;
				display: flex;
				flex-direction: column;
				align-items: center;
				padding: 36px 0;
			}
			&-contact {
				font-weight: 500;
				font-size: 14px;
				line-height: 20px;
				text-align: center;
				text-decoration: underline;
				color: #888888;
				margin-bottom: 16px;
			}
			&-phone {
				font-weight: 500;
				font-size: 14px;
				line-height: 20px;
				color: inherit;
			}
			&-phonelabel {
				font-weight: normal;
				font-size: 11px;
				line-height: 20px;
				margin-bottom: 8px;
				color: inherit;
			}
			&-mail {
				font-weight: 500;
				font-size: 14px;
				line-height: 20px;
				color: inherit;
			}
		}
		
		&--active {
			width: 100%;
			transform: translateX(0);
		}
	}

	.createorder {
		color: $color-text-light;
		
		@media (max-width: 640px) {
			display: none;
		}
		&__btn {
			color: #ffffff;
			background-color: transparent;
			text-decoration: none;
			text-align: center;
			padding: 5px 20px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 10px;
			font-weight: 600;
			font-size: 14px;
			line-height: 17px;
			border: 2px solid #fff;
			&:hover {
				// background-color: #0079b2;
			}
			&::before {
				content: "+";
				font-size: 24px;
				line-height: 29px;
				letter-spacing: 0.02em;
				margin-right: 5px;
			}
			@media (max-width: 640px) {
			
			}
		}
	}
	.curr {
		order:3;
		margin-left: auto;
		@media(max-width: 640px) {
			margin-left: 15px;
			order: 2;
		}
		&__main {
			display: flex;
			align-items: center;
		}
		&__main-symb {
			font-weight: 500;
			font-size: 18px;
			line-height: 22px;
			color: #FFFFFF;
			margin-left: 7px;
		}
		&__main-arrow {
			outline: none;
			cursor: pointer;
			margin-left: 7px;
		}
		&__modal {
			position: absolute;
			top: calc(100% + 17px);
			right: calc(1.4rem / 2);
			z-index: 10;
			width: 350px;
		}
		&__modal-item {
			display: flex;
			align-items: center;
			padding: 11px 9px;
			background: #fff;
			&.is-active {
				background: #F2F2F2;
			} 
		}
		&__modal-symb {
			margin-left: 7px;
			color: #2E3647;
		}
		&__modal-name {
			margin-left: 7px;
			color: #2E3647;
		}
	}
	.profile {
		color: $color-text-light;
		position: relative;

		&__box{
			display: flex;
			padding: 7px;
			border-radius: 10px;
			background-color: $color-base-accent;
			&--hover {
				@media(min-width: 768px) {
					&:hover {
						background-color: #329b78;
					}
				}
			}
		}
		&__box-homepage {
			color: $color-base-accent;
			min-width: 160px;
			height: 56px;
			display: flex;
			align-items: center;
			padding: 7px;
			border-radius: 100px;
			border: 1px solid $color-base-accent;
			cursor: pointer;
			&-txt {
				text-transform: uppercase;
				font-style: normal;
				font-weight: 600;
				font-size: 15px;
				line-height: 18px;
			}
			&-pic {
				width: 42px;
				height: 42px;
				margin-right: 18px;
				img {
					width: 100%;
					height: auto;
				}
			}
			@media screen and (max-width: 768px) {
				min-width: 114px;
				height: 40px;
				&-txt {
					font-size: 11px;
					line-height: 13px;
				}
				&-pic {
					width: 30px;
					height: 30px;
				}
			}
			@media screen and (max-width: 425px) {
                min-width: 114px;
                height: 38px;

                &-txt {
                    font-size: 13px;
                    line-height: 16px;
                }

                &-pic {
                    width: 28px;
                    height: 28px;
                    margin-right: 15px;
                }
            }
		}
		&__content {
			display: flex;
			flex-direction: column;
			justify-content: center;
			margin-left: 10px;
		}
		&__title {
			font: $font-semibold;
			color: $color-text-light;
			font-size: 13px;
			line-height: 16px;
			margin-bottom: 3px;
		}
		&__pic {
			cursor: pointer;
			&--notice {
				position: relative;
				&::after {
					content: "";
					position: absolute;
					display: block;
					width: 10px;
					height: 10px;
					border-radius: 50%;
					background: #ff2626;
					right: 0;
					top: 0;
				}
			}
		}
		&__enter,
		&__company {
			text-decoration: none;
			color: inherit;
			font-size: 13px;
			line-height: 16px;
		}
		&__enter {
			font-weight: 500;
			&:hover {
				color: #0a5f87;
			}
		}
		&__pic--logged {
			width: 37px;
			height: 37px;
			border-radius: 50%;
			background-color: #f6f6f6;
			background: url(/images/login_thumb.png) no-repeat;
			background-size: cover;
			background-position: center;
		}
		&--homepage {
			margin-left: auto;
		}
	}
	.notablet {
		@media (max-width: 768px) {
		display: none;
		}
	}
	
	.head-acc {
		@media(min-width: 641px) {
			display: none;
		}
		order: 5;
		margin-left: auto;
		&__logged {
			display: flex;
		}
		&__favorites, &__user {
			path {
				fill: #fff;
			}
		}
		&__favorites {
			margin-right: 24px;
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
		
		&__user {
			display: flex;
			align-items: center;
			position: relative;
			cursor: pointer;
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
		&__account-name {
			margin-left: 10px;
			font-style: normal;
			font-weight: 500;
			font-size: 14px;
			line-height: 17px;
			color: $color-base-accent;
			@media screen and (max-width: 640px) {
				display: none;
			}
		}
		&__modal {
			position: absolute;
			background: #FFFFFF;
			box-shadow: 0px 2px 8px rgba(69, 91, 99, 0.18);
			border-radius: 10px;
			padding: 27px 15px 30px;
			top: calc(100% + 10px);
			right: 15px;
			width: 310px;
			z-index: 10;
			@media(max-width: 320px) {
				width: 290px;
			}
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
				position: relative;
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
	&--homepage {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		z-index: 2;
		background-color: transparent;
		height: 136px;
		@media screen and (max-width: 768px) {
			height: 68px;
		}
		.mainheader__container {
			height: 100%;
			padding: 0 60px;
			max-width: 1170px;
			
			@media screen and (max-width: 1024px) {
				padding: 0 30px;
			}
			@media screen and (max-width: 768px) {
				padding: 0 30px;
			}
			@media screen and (max-width: 640px) {
				padding: 0 15px;
			}
		}
		.mainmenu {
			&__link {
				color: #2C3444;
				&::after {
					background-color: #2C3444;
				}
				&.animation-link-underline {
					&::before {
						background-color: #2C3444;
					}
				}
			}
			&__burger {
				g {
					fill: #2C3444;
				}
			}
		}
		.createorder {
			color: $color-text-light;
			&__btn {
				color: #2C3444;
				background-color: transparent;
				&:hover {
					background: transparent;
					color: $color-base-accent;
				}
			}
		}
		.profile {
			&__box {
				background: transparent;
			}
			&__title {
				color: #2C3444;
			}
			&__box--hover:hover {
				background: transparent;
			}
		}
		.logo {
			@media(max-width: 640px) {
				margin-left: 15px;
				order: 2;
			}
		}
		.head-acc {
			@media(min-width: 641px) {
				display: flex;
			}
			& /deep/ path {
				// stroke: #2FC06E;
				fill: #2FC06E;
			}
		}
	}
}
.overlay {
  overflow: hidden;
  &::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100vh;
    z-index: 2;
    background: transparentize(#000, 0.5);
  }
}
</style>
