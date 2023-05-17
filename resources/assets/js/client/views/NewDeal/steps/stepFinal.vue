<template lang="pug">
div.newbid
	section.newbid-content.tomoder
		div.container
			h2.tomoder__title.newbid__title {{currTitle}}
			section.tomoder__card
				div.tomoder__time {{currModerTime}}
					p.tomoder__time-pic
						img(src="https://tamtem.ru/images/pics/time.png")
					p.tomoder__time-txt 
				h3.tomoder__cap {{currViewLK}}
				router-link.tomoder__link(v-if="$root.isAuth" :to="{name:'lk.deals',hash:'#moderated'}") Перейти
				a.tomoder__link(v-else href="javascript:void(0);" @click="$root.$emit('showForm','authorizationForm'); ") Перейти
				p.tomoder__help В случае возникновения вопросов #[br] обратитесь в службу поддержки #[br] #[a(href="tel:+79307202300") +7 930 720 23 00]
</template>

<script>
import iconCalendar from "../../Icons/iconCalendar"

export default {
	components: {
		iconCalendar
	},
	props: {
		typeDeal: {
			type: String
		}
	},
	data() {
		return {
			sellTitle: 'Ваше объявление отправлено на модерацию',
			buyTitle: 'Ваш заказ отправлен на модерацию',
			buyModerTime: 'При успешном размещении заказ будет активен на сервисе tamtem.ru в течение 30 дней',
			sellModerTime: 'При успешном размещении объявление будет опубликовано на сервисе tamtem.ru',
			buyViewLK: 'Посмотреть статус заказа в личном кабинете',
			sellViewLK: 'Посмотреть статус объявления в личном кабинете'
		}
	},
	computed: {
		currTitle() {
			if(this.typeDeal == 'buy') {
				return this.buyTitle
			} else if(this.typeDeal == 'sell') {
				return this.sellTitle
			}
		},
		currModerTime (){
			if(this.typeDeal == 'buy') {
				return this.buyModerTime
			} else if(this.typeDeal == 'sell') {
				return this.sellModerTime
			}
		},
		currViewLK (){
			if(this.typeDeal == 'buy') {
				return this.buyViewLK
			} else if(this.typeDeal == 'sell') {
				return this.sellViewLK
			}
		}
	}
}
</script>

<style lang="scss" scoped>
@import "~styleguide";
.tomoder {
	&__title {
		text-align: center;
	}
	&__card {
		@include contentbox;
		display: flex;
		flex-direction: column;
		align-items: center;
		padding-bottom: 50px;
		@media(max-width: 425px) {
			padding-bottom: 20px;
		}
	}
	&__time {
		display: flex;
		align-items: center;
		margin-top: 50px;
		@media(max-width: 425px) {
			margin-top: 20px;
		}
		&-txt {
			font-size: 14px;
			line-height: 19px;
			span {
				color: $color-base-accent;
			}
			@media(max-width: 425px) {
				font-size: 12px;
			}
		}
		&-pic {
			margin-right: 20px;
			@media(max-width: 425px) {
				margin-right: 12px;
			}
		}
	}
	&__cap {
		font-size: 18px;
		font-weight: 500;
		line-height: 28px;
		text-align: center;
		margin-top: 65px;
	}
	&__link {
		color: #ffffff;
		background-color: $color-base-accent;
		border-radius: 35px;
		font-weight: 600;
		font-size: 14px;
		line-height: 24px;
		display: flex;
		align-items: center;
		justify-content: center;
		height: 43px;
		width: 153px;
		margin-top: 30px;
		@media(max-width: 425px) {
			width: 128px;
			height: 40px;
			margin-top: 23px;
		}
	}
	&__help {
		font-size: 14px;
		line-height: 19px;
		text-align: center;
		margin-top: 65px;
		a {
			color: inherit;
		}
		@media(max-width: 425px) {
			font-size: 12px;
		}
		@media(min-width: 426px) {
			br {
				display: none;
			}
		}
	}
}
</style>