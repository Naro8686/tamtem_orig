<template lang="pug">
section.supplier
	div.supplier-firstscreen
		.container
			.row
				.col
					h1.supplier-firstscreen__title Станьте поставщиком на сервисе tamtem
					b.supplier-firstscreen__attention Только лучшее предложение #[br] получает контактные данные заказчика
					a(href="/registration" @click.prevent="beASupplier('stat_postavschikom');").supplier-firstscreen__button.supplier__button Стать поставщиком
					div.supplier-featurecard
						div.supplier-featurecard__item
							strong Просто
							p Без лишних звонков. Отправьте предложение в пару кликов.
						div.supplier-featurecard__item
							strong Выгодно
							p Контактные данные заказчика, в случае победы, получаете только вы. Без дополнительных тарифов.
						div.supplier-featurecard__item
							strong Безопасно
							p Каждый размещенный заказ проверен на актуальность и юридические данные компании подтверждены службой безопасности.
	div.supplier-steps
		.container
			.row
				.col
					h2.supplier__title.supplier-steps__title Как получить заказ
					ul.supplier-steps__list
						li
							div.supplier-steps__pic
								img(src="/images/supplier/step1.png" alt="Выберите заказ")
							strong.supplier-steps__cap 1. Выберите заказ
							p.supplier-steps__txt Выберите интересующий вас заказ. Ознакомьтесь с его условиями и описанием.
						li
							div.supplier-steps__pic
								img(src="/images/supplier/step2.png" alt="Оставьте предложение")
							strong.supplier-steps__cap 2. Оставьте предложение
							p.supplier-steps__txt Отметьте условия, которые вы можете выполнить. Оставьте своё ценовое предложение и комментарий для заказчика.
						li
							div.supplier-steps__pic
								img(src="/images/supplier/step3.png" alt="Получите контактные данные")
							strong.supplier-steps__cap 3. Получите контактные данные заказчика
							p.supplier-steps__txt Если заказчик выбирает вас, оплачивайте получение контакта. Звоните заказчику.
					a(@click.prevent="showBids('view_zakazy');" href="/bids").supplier-steps__button.supplier__button Посмотреть заказы
	div.supplier-features
		.container
			.row
				.col
					h2.supplier__title.supplier-features__title Мы предлагаем
					div.feature.feature--right
						div.feature__pic
							img(src="/images/supplier/feat1.png" alt="Гарантия возврата средств")
						div.feature__content
							h4.feature__cap Гарантия возврата средств
							p.feature__text В случае выбора вас победителем в заказе и оплаты контактов, сделка не совершилась по вине заказчика (введены не верные данные, не выходит на контакт и т.д.) вы можете вернуть потраченные средства за контакт.
							a(@click.prevent="showfaq();" href="/faq#answer-failing-order").feature__link Подробнее
					div.feature.feature--left
						div.feature__pic
							img(src="/images/supplier/feat2.png" alt="Экономия на рекламе")
						div.feature__content
							h4.feature__cap Экономия средств на рекламе для привлечения заказов
							p.feature__text Больше не нужно тратить средства на доски объявлений и рекламу. Выбираете заказы и отправляете предложения тем заказчикам, которым нужны ваши товары прямо сейчас.
							a.feature__link
					div.feature.feature--right
						div.feature__pic
							img(src="/images/supplier/feat3.png" alt="Выгодные заказы")
						div.feature__content
							h4.feature__cap Выгодные заказы каждый день
							p.feature__text База заказов на сервисе tamtem.ru регулярно обновляется. Мы работаем с заказчиками по всей России.
					a(@click.prevent="beASupplier('stat_postavschikom_now');" href="/registration").supplier-features__button.supplier__button Стать поставщиком прямо сейчас
	section.supplier-tarifs
		div.container
			.row
				.col
					div.supplier-tarifs__cap 
						b 1
						span ₽
					h2.supplier__title.supplier-tarifs__title Получи первый заказ на сервисе всего за 1 ₽
					p.supplier-tarifs__text Со второго выигранного заказа стоимость контактных данных заказчика составляет #[br] 0,3% от средней суммы сделки
					p.supplier-tarifs__note Заказчик уже ждет ваше предложение
					div.supplier-tarifs__btn
						a(@click.prevent="showBids('view_zakazy_1_rub');" href="/bids") Посмотреть заказы
	section.supplier-reviews
		div.container
			.row
				.col
					h2.supplier__title.supplier-reviews__title СМИ о нас
					div(id="slider").supplier-reviews__wrapper
						ul(id="reviews").slider-bar.supplier-reviews__list
							li.supplier-reviews__box(v-for="item in reviews")
								a(:href="item.src").supplier-reviews__item
									p.supplier-reviews__text {{item.text}}
									p.supplier-reviews__link
										span Читать
										i
											iconLongArrow
										span.supplier-reviews__resname {{item.name}}
</template>
<script>
import iconLongArrow from "../Icons/iconLongArrow.vue";
export default {
	components: {
    iconLongArrow,
  },
	data() {
		return {
			reviews: [
        {
          text:
            "О нашем стартапе пишут в новостном издании и рассказывают о работе сервиса для заказчиков по поиску поставщиков",
          src:
            "https://wall.wayxar.ru/startups/kak_effektivnee_rabotat_s_poiskom_zakazchikov_i_postavshchikov",
          name: "wall.wayxar.ru"
        },
        {
          text:
            "Наш технический директор рассказывает как правильно подобрать программистов. Экспертное мнение для журнала e-xecutive.ru",
          src:
            "https://www.e-xecutive.ru/career/hr-management/1991542-kak-ne-pereplatit-programmistu",
          name: "e-xecutive.ru"
        },
        {
          text:
            "Артём Ковалев, генеральный директор ООО «Акстек» (интернет-сервис agent.tamtem.ru), рассказал BS, каким должен быть социальный бизнес, почему для малого бизнеса важны рекомендации",
          src:
            "https://businesstory.ru/my-soedinjaem-biznes-i-obshhestvo-istorija-platformy-tamtem-ru/",
          name: "businesstory.ru"
        }
      ],
		}
	},
	methods:{
		beASupplier(target){			
			this.googleReachGoal(target);
			this.yandexReachGoal(target);

			this.$root.$emit('showForm','registrationForm');
		},
		showBids(target){
			this.googleReachGoal(target);
			this.yandexReachGoal(target);

			this.$router.push({name: 'bids.list'});
		},
		showfaq(){
			this.googleReachGoal('podrobnee');
			this.yandexReachGoal('podrobnee');
			
			window.location = '/faq#answer-failing-order';
		}

	}
}
</script>
<style lang="scss">
@import "~styleguide";
@import "~mixins";
.supplier {
	font-size: 14px;
  line-height: 21px;
  font-weight: 400;
	&__title {
		text-align: center;
    font-size: 26px;
    font-weight: 500;
    @media (max-width: 530px) {
      font-size: 20px;
      line-height: 30px;
    }
		
	}
	&__button {
    @include big-rounded-btn;
    @include btnHov($color-hover-button);
    max-width: 220px;
		min-height: 50px;
    text-transform: none;
    margin: 0 auto;
    font-size: 16px;
    font-weight: 600;
    padding: 0 20px;
		color: #ffffff;
    @media (max-width: 768px) {
      font-size: 14px;
			min-height: 40px;
    }
  }
}
.supplier-firstscreen {
	background: url(/images/supplier/supp-bg1.png) no-repeat, #ffffff;
	background-position: left 0 bottom 100px, top left;
	background-size: cover;
	padding-top: 121px;
	@media(max-width: 600px) {
		padding-top: 95px;
	}
	@media(max-width: 425px) {
		padding-top: 60px;
	}
	&__title {
		font-size: 38px;
		text-align: center;
		font-weight: 500;
		margin-bottom: 50px;
		@media(max-width: 768px) {
			font-size: 34px;
		}
		@media(max-width: 600px) {
			margin-bottom: 40px;
		}
		@media(max-width: 425px) {
			font-size: 24px;
			margin-bottom: 24px;
		}
	}
	&__attention {
		display: block;
		text-align: center;
		color: $color-base-accent;
		font-size: 26px;
		margin-bottom: 58px;
		font-weight: 500;
		line-height: 36px;
		@media(max-width: 768px) {
			font-size: 22px;
			line-height: 1.5;
		}
		@media(max-width: 600px) {
			margin-bottom: 45px;
		}
		@media(max-width: 425px) {
			font-size: 16px;
		}
	}
}
.supplier-featurecard {
	padding: 30px 47px;
	background: #ffffff;
	border-radius: 25px;
	box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
	margin: 0 auto;
	margin-top: 61px;
	width: 100%;
	max-width: 762px;
	&__item {
		display: flex;
		align-items: flex-start;
		@media(max-width: 550px) {
			flex-direction: column;
		}
		&:not(:last-child) {
			margin-bottom: 45px;
			@media(max-width: 600px) {
				margin-bottom: 35px;
			}
		}
		strong {
			font-size: 26px;
			font-weight: 500;
			width: 40%;
			@media(max-width: 768px) {
				font-size: 22px;
			}
			@media(max-width: 550px) {
				width: 100%;
				margin-bottom: 10px;
			}
		}
		p {
			width: 60%;
			@media(max-width: 550px) {
				width: 100%;
			}
		}
	}
}
.supplier-steps {
	padding-top: 130px;
	@media(max-width: 768px) {
		padding-top: 90px;
	}
	&__title {
		margin-bottom: 70px;
		@media(max-width: 600px) {
			margin-bottom: 50px;
		}
		@media(max-width: 530px) {
			margin-bottom: 24px;
		}
	}
	&__list {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr;
		gap: 40px;
		margin-bottom: 64px;
		@media(max-width: 768px) {
			grid-template-columns: 1fr;
			margin-bottom: 52px;
		}
		@media(max-width: 425px) {
			grid-template-columns: 1fr;
			margin-bottom: 50px;
		}
		li {
			list-style: none;
		}
	}
	&__pic {
		height: 230px;
		img {
			width: 100%;
			height: 100%;
			object-fit: contain;
		}
	}
	&__cap {
		display: block;
		text-align: center;
		font-weight: 500;
		font-size: 18px;
		margin-top: 38px;
		@media(max-width: 768px) {
			font-size: 16px;
		}
		@media(max-width: 600px) {
			margin-top: 20px;
		}
	}
	&__txt {
		text-align: center;
		margin-top: 35px;
		@media(max-width: 768px) {
			padding: 0 10%;
		}
		@media(max-width: 600px) {
			margin-bottom: 20px;
		}
		a {
			color: $color-base-accent;
			text-decoration: none;
		}
		span {
			color: $color-base-accent;
		}
	}
}
.supplier-features {
	padding-top: 130px;
	padding-bottom: 220px;
	@media(max-width: 1100px) {
		padding-bottom: 180px;
	}
	@media(max-width: 768px) {
		padding-top: 90px;
		padding-bottom: 120px;
	}
	@media(max-width: 600px) {

		padding-bottom: 50px;
	}
	&__button {
		max-width: 370px;
	}
	&__title {
		margin-bottom: 70px;
		@media(max-width: 600px) {
			margin-bottom: 50px;
		}
		@media(max-width: 530px) {
			margin-bottom: 24px;
		}
	}
	.feature {
		display: flex;
		@media(max-width: 550px) {
			flex-direction: column;
		}
		&:not(:last-child) {
			margin-bottom: 76px;
			@media(max-width: 1100px) {
				margin-bottom: 60px;
			}
		}
		&__pic {
			width: 45%;
			@media(max-width: 550px) {
				width: 70%;
				margin: 0 auto;
				margin-bottom: 20px;
			}
			img {
				width: 100%;
				height: auto;
			}
		}
		&__content {
			width: 55%;
			display: flex;
			flex-direction: column;
			justify-content: center;
			@media(max-width: 550px) {
				width: 80%;
				margin: 0 auto;
				align-items: center;
			}
			@media(max-width: 400px) {
				width: 98%;
			}
		}
		&__cap {
			padding-left: 50px;
			font-size: 18px;
			font-weight: 500;
			position: relative;
			min-height: 46px;
			display: flex;
			align-items: center;
			margin-bottom: 30px;
			&::before {
				@include w-h(36px);
        content: "";
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='11' viewBox='0 0 12.997 9.231'%3E%3Cg id='check-square' transform='translate(1.414 1.414)'%3E%3Cpath fill='none' stroke='%2331c26e' stroke-linecap='round' stroke-linejoin='round' stroke-miterlimit='10' stroke-width='2px' d='M-1.525 3.557l3.07 3.26L5.63 2.895 8.645 0' transform='translate(1.525)'/%3E%3C/g%3E%3C/svg%3E%0A")
            no-repeat,
          #d4f5de;
        background-position: center;
        border-radius: 50%;
        position: absolute;
        top: 50%;
				transform: translateY(-50%);
        left: 0;
      }
		}
		&__text {
			@media(max-width: 550px) {
				text-align: center;
			}
		}
		&__link {
			display: flex;
			align-self: flex-start;
			margin-top: 26px;
			color: $color-base-accent;
			font-weight: 500;
			text-decoration: none;
			border-bottom: 1px solid currentColor;
			@media(max-width: 550px) {
				align-self: center;
			}
			&:hover {
				color: $color-hover-button;
			}
		}
		&--right {
			.feature__pic {
				order: 1;
			}
			.feature__content {
				order: 2;
				padding-left: 52px;
				padding-right: 12%;
				@media(max-width: 768px) {
					padding-left: 40px;
					padding-right: 4%;
				}
				@media(max-width: 550px) {
					padding: 0;
				}
			}
		}
		&--left {
			.feature__pic {
				order: 2;
				@media(max-width: 550px) {
					padding: 0;
					order: 1;
				}
			}
			.feature__content {
				order: 1;
				padding-right: 48px;
				padding-left: 12%;
				@media(max-width: 768px) {
					padding-left: 4%;
					padding-right: 36px;
				}
				@media(max-width: 550px) {
					padding: 0;
					order: 2;
				}
			}
		}
	}
}
.supplier-tarifs {
	$color-cold-soft: #ecf2fe;
	background-color: $color-cold-soft;
  padding-bottom: 50px;
  background: url(/images/home/home-bg-prices.png) no-repeat;
  background-position: center;
	background-size: cover;
  &__cap {
    color: $color-base-accent;
    text-align: center;
    b {
      font-size: 191px;
      line-height: 233px;
      font-weight: 500;
      @media (min-width: 768px) {
        font-size: 149px;
        line-height: 181px;
      }
    }
    span {
      font-size: 64px;
      font-weight: 500;
      line-height: 78px;
      @media (min-width: 768px) {
      }
    }
  }
  &__title {
    margin-bottom: 22px;
    font-weight: 600;
  }
  &__text {
    text-align: center;
    line-height: 21px;
    margin-bottom: 34px;
    padding: 0 10px;
  }
	&__note {
		font-weight: 500;
		font-size: 18px;
		text-align: center;
		margin-bottom: 20px;
	}
	&__btn {
		a {
			color: $color-base-accent;
			font-size: 18px;
			font-weight: 500;
			text-decoration: none;
			border-bottom: 2px solid currentColor;
			padding-bottom: 4px;
			&:hover {
				text-decoration: none;
				color: $color-hover-button;
				border-color: currentColor;
			}
		}
		display: flex;
		justify-content: center;
		
	}
}
.supplier-reviews {
	background: #ffffff;
  padding-top: 130px;
	padding-bottom: 115px;
  @media (max-width: 768px) {
    padding-top: 90px;
  }
  &__title {
    margin-bottom: 50px;
  }
  &__wrapper {
    overflow: hidden;
    position: relative;
  }
  &__list {
    list-style: none;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 30px;
    transition: 750ms ease;
    position: relative;
    left: 0;
    @media (max-width: 1000px) {
      grid-template-columns: 1fr;
    }
  }
  &__box {
    padding: 5px;
  }
  &__item {
    border-radius: 10px;
    border: 1px solid $color-border-gray;
    background-color: #ffffff;
    min-height: 245px;
    padding: 42px 36px;
    display: flex;
    flex-direction: column;
    height: 100%;
    color: #000;
    text-decoration: none;
    transition: 0.2s;
    &:hover {
      box-shadow: 0px 0px 14px 0 rgba(69, 91, 99, 0.2);
			text-decoration: none;
      .supplier-reviews__link i {
        transform: translateX(15px);
      }
    }
  }
  &__text {
    margin-bottom: 20px;
  }
  &__link {
    margin-top: auto;
    color: $color-base-accent;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    span {
      margin-right: 12px;
    }
    i {
      display: inline-block;
      transition: 0.4s;
    }
  }
  &__resname {
    color: $color-hamster-cold;
    margin-left: auto;
    font-weight: 400;
  }
  &__controls {
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    @media (min-width: 769px) {
      display: none;
    }
    li {
      padding: 10px;
      button {
        outline: none;
      }
      & /deep/ svg {
        stroke: $color-base-accent;
        stroke-width: 2px;
      }
    }
  }
}
</style>