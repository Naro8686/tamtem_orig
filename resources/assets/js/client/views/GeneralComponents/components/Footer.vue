<template lang="pug">
footer.mainfooter
	section.mainfooter__content
		.container.mainfooter__container
			section.mainfooter__logo
				footer-logo
			p.mainfooter__copyright © ООО «Акстек», 2021 ОГРН 1187847338920
			ul.mainfooter__services.mainfooter__list
				//- li
				//- 	router-link( :to="{name:'new.bid'}").animation-link-underline Добавить заказ
				li
					span.mainfooter__list-cap Возможности tamtem
				li
					a(href="/newbid" @click="toBidCreate()").animation-link-underline Создать заказ
				li
					a(href="/newsell" @click.prevent="goToUrlWithReachGoal('sozdobfut', '/newsell')").animation-link-underline Создать объявление
				li
					//- router-link(:to="{name:'tarifs'}").animation-link-underline Оплата
					a(href="/price" @click.prevent="goToUrlWithReachGoal('oplatafut', '/price')").animation-link-underline Оплата

			ul.mainfooter__info.mainfooter__list
				li
					span.mainfooter__list-cap Информация
				li
					a(@click="tofaq()" href="/faq").animation-link-underline FAQ
					//- router-link(:to="{name: 'faq'}").animation-link-underline FAQ
				li
					a(href="http://blog.tamtem.ru/" target="_blank").animation-link-underline Блог
				//- li
					a(href="/about").animation-link-underline О сервисе
				li
					//- router-link(:to="{name:'contacts'}").animation-link-underline Контакты
					a(href="/contact" @click.prevent="goToUrlWithReachGoal('contact', '/contact')").animation-link-underline Контакты
			.mainfooter__contacts
				a(href="tel:+79307202300").mainfooter__contacts-phone +7 930 720 23 00
				div.mainfooter__contacts-cap.mainfooter__messengers
					span Пишите
					ul.messengers-list
						li
							icon-whatsup
						li
							icon-viber
						li
							icon-telegram
				a(href="mailto:team@tamtem.ru").mainfooter__email team@tamtem.ru
			ul.mainfooter__socials
				li
					a(href="https://vk.com/tamtem_b2b?roistat_visit=102953").mainfooter__socials-link.mainfooter__socials-link--vk
						icon-vk
				li
					a.mainfooter__socials-link(href="https://ok.ru/group/54744477925527?roistat_visit=102953").mainfooter__socials-link--ok
						icon-ok
				li
					a.mainfooter__socials-link(href="https://www.facebook.com/%D0%A2%D0%B0%D0%BC%D0%A2%D0%B5%D0%BC-417576149054496/?roistat_visit=102953").mainfooter__socials-link--fb
						icon-fb
				li
					a.mainfooter__socials-link(href="https://www.instagram.com/tamtemb2b/?roistat_visit=102953").mainfooter__socials-link--inst
						icon-insta
				li
					a.mainfooter__socials-link(href="https://twitter.com/tamtemb2b").mainfooter__socials-link--twi
						icon-twitter
				li
					a.mainfooter__socials-link(href="https://www.youtube.com/watch?v=CcP_-_eKiWw").mainfooter__socials-link--tube
						icon-tube
	section.filebar
		.container.filebar__container
			a(href="/files/agreement.pdf").filebar__item.animation-link-underline Условия использования
			a(href="/files/politic.doc").filebar__item.animation-link-underline Политика конфиденциальности
			a(href="/files/terms_of_use.pdf").filebar__item.animation-link-underline Правила использования
</template>

<script>
import footerLogo from "../../Icons/footerLogo.vue"
import iconVk from "../../Icons/socials/iconVk.vue"
import iconOk from "../../Icons/socials/iconOk.vue"
import iconFb from "../../Icons/socials/iconFb.vue"
import iconInsta from "../../Icons/socials/iconInsta.vue"
import iconTwitter from "../../Icons/socials/iconTwitter.vue"
import iconWhatsup from "../../Icons/socials/iconWhatsup.vue"
import iconViber from "../../Icons/socials/iconViber.vue"
import iconTelegram from "../../Icons/socials/iconTelegram.vue"
import iconTube from "../../Icons/socials/iconTube.vue"
export default {
  components: {
    footerLogo,
		iconVk,
		iconOk,
		iconFb,
		iconInsta,
		iconTwitter,
		iconWhatsup,
		iconViber,
		iconTelegram,
		iconTube
  },
  data() {
    return {
      fileurl: "/files/"
    };
  },
  methods: {
		tofaq(){
			event.preventDefault();
			this.yandexReachGoal('faq');
			this.googleReachGoal('faq');
			location.href="/faq";
		},
		toBidCreate(){
			event.preventDefault();
			this.yandexReachGoal('sozdzakfut');
			this.googleReachGoal('sozdzakfut');
			const currentPath = this.$route.name;
			if (currentPath!='new.bid' && currentPath!='new.sell'){
				this.$router.push({name: 'new.bid'}).catch(()=>{});
			} else{
				this.$root.$emit('resetFormCreate');
			}
			
		},
	},
  mounted() {}
};
</script>

<style scoped lang="scss">
@import "~styleguide";
// некоторые миксины для удобства.
@mixin w-h($width, $height: $width) {
    width: $width;
    height: $height;
}
@mixin flex-centerizer {
    display: flex;
    align-items: center;
    justify-content: center;
}
.mainfooter {
	background-color: $color-hamster-cold;
	color: #fff;
	font-family:$font-family;
	// margin-top: 60px;
	ul li {
		list-style: none;
	}
	&__content {
		padding-top: 40px;
		padding-bottom: 20px;
		.animation-link-underline::before {
			background-color: $color-base-accent;
		}
	}
	&__container {
		display: grid;
		gap: 20px;
		grid-template-columns: repeat(12, 1fr);
		@media(max-width: 768px) {
			column-gap: 10px;
		}
		@media(max-width: 500px) {

		}
	}
	&__logo {
		grid-column: 1 / 3;
		grid-row: 1;
		& /deep/ svg {
			max-width: 100%;
		}
		@media(max-width: 768px) {
			grid-column: 1 / 4;
		}
		@media(max-width: 600px) {
			& /deep/ svg {
				max-width: 90%;
			}
		}
		@media(max-width: 500px) {
			grid-column: 1 / 13;
			grid-row: 1;
		}
	}
	&__services {
		grid-column: 4 / 7;
		grid-row: 1;
		@media(max-width: 500px) {
			grid-column: 1 / 13;
			grid-row: 2;
		}
		a {
			cursor: pointer;
		}
	}
	&__info {
		grid-column: 7 / 9;
		grid-row: 1;
		// justify-self: end;
		@media(max-width: 500px) {
			grid-column: 1 / 13;
			grid-row: 3;
		}
		a {
			cursor: pointer;
		}
	}
	&__contacts {
		grid-column: 9 / 13;
		grid-row: 1;
		justify-self: end;
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		a {
			text-decoration: none;
			color: #fff;
		}
		&-phone {
			font-size: 18px;
			font-weight: 500;
			line-height: 25px;
		}
		&-cap {
			font-style: normal;
			font-size: 10px;
			line-height: 13px;
			margin-bottom: 12px;
		}
		@media(max-width: 768px) {
			grid-column: 9 / 13;
			// grid-row: 1 / 3;
		}
		@media(max-width: 500px) {
			grid-column: 1 / 13;
			grid-row: 4;
			align-items: flex-start;
			justify-self: start;
		}
	}
	&__messengers {
		display: flex;
		align-items: center;
	}
	&__copyright {
		grid-column: 1 / 6;
		grid-row: 2;
		color: $color-base-accent;
		font-weight: 600;
		font-size: 14px;
		align-self: end;
		@media(max-width: 768px) {
			// grid-column: 1 / 9;
			font-size: 13px;
		}
		@media(max-width: 500px) {
			grid-column: 1 / 13;
			grid-row: 6;
			font-size: 12px;
		}
	}
	&__list {
		max-width: 100%;
		li {
			margin-bottom: 5px;
			font-size: 15px;
			line-height: 25px;
			@media(max-width: 950px) {
				font-size: 14px;
			}
			@media(max-width: 768px) {
				font-size: 12px;
				line-height: 18px;
			}
			@media(max-width: 500px) {
				margin-bottom: 7px;
			}
		}
		a {
			text-decoration: none;
			color: #fff;
		}
		&-cap {
			font-size: 16px;
		}
	}
	&__socials {
		grid-column: 6 / 13;
		grid-row: 2;
		justify-self: end;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		
		@media(max-width: 768px) {
			margin-top: auto;
		}
		@media(max-width: 500px) {
			grid-row: 5;
			grid-column: 1 / 13;
			justify-self: start;
		}
		li {
			margin: 5px 0;
			@media(max-width: 768px) {
				margin-bottom: 0px;
			}
			&:not(:last-child) {
				margin-right: 8px;
				@media(max-width: 500px) {
					margin-right: 15px;
				}
			}
		}
		.mainfooter__socials-link {
			@include w-h(32px);
			@include flex-centerizer;
			border-radius: 50%;
			background-color: $color-base-accent;
			@media(max-width: 768px) {
				@include w-h(30px);
			}
			@media(max-width: 500px) {
				@include w-h(34px);
			}
			& /deep/ svg {
				max-height: 50%;
				max-width: 60%;
				path {
					fill: #fff;
				}
			}
			&--vk {
				&:hover {
					background: #5181b8;
				}
				& /deep/ svg {
					max-height: 30%;
				}
			}
			&--ok {
				&:hover {
					background: #f7931e;
				}
			}
			&--fb {
				&:hover {
					background: #157dc3;
				}
			}
			&--inst {
				&:hover {
					background: linear-gradient(29deg, #fba64f 13%, #f9b353 19%, #ec505e 45%, #ce3f97 73%, #a733ba 86%);
				}
			}
			&--twi {
				&:hover {
					background: #1da1f2;
				}
			}
			&--tube {
				&:hover {
					background: #D42428;
				}
			}
		}
		
	}
	&__phone {
		font-weight: 500;
		font-size: 18px;
		line-height: 25px;
		letter-spacing: 0.31px;
		margin-bottom: 5px;
		@media(max-width: 768px) {
			font-size: 17px;
		}
	}
	&__email {
		font-weight: 500;
		font-size: 16px;
		line-height: 25px;
		@media(max-width: 768px) {
			font-size: 15px;
		}
	}
}
.messengers-list {
	display: flex;
	li {
		margin-left: 7px;
	}
}
.filebar {
	background-color: $color-base-accent;
	&__container {
		min-height: 60px;
		display: flex;
		align-items: center;
	}
	&__item {
		color: #fff;
		text-decoration: none;
		font-size: 14px;
		a {
			color: #fff;
		}
		&:not(:last-child) {
			// margin-right: 30px;
			@media(max-width: 320px) {
				margin-right: 10px;
			}
			&:hover::before {
				width: calc(100% - 25px)
			}
			&::after {
				content: "|";
				margin: 0 10px;
				@media(max-width: 320px) {
					display: none;
				}
			}
		}
		@media(max-width: 768px) {
			font-size: 12px;
		}
		@media(max-width: 425px) {
			font-size: 9px;
		}
	}
	
}
</style>