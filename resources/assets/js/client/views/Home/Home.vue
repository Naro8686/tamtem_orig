<template lang="pug">
section.homepage
	section.homepage-firstscreen
		div.container
			div.homepage-firstscreen__inner
				div.pagetype-switch.homepage__pageswitch
					a(href="/").pagetype-switch__btn.is-active Для покупателей
					a(href="/postavschic").pagetype-switch__btn Для поставщиков
			div.homepage-firstscreen__inner
				div.homepage-firstscreen__box
					h1.homepage-firstscreen__title Биржа оптовых #[br] продаж tamtem.ru
					p.homepage-firstscreen__desc безопасная платформа
					p.homepage-firstscreen__note тысячи заказов и предложений по всей России
					template(v-if='this.$root.isAuth')
						a(href="/newbid" @click.prevent="goToUrlWithReachGoal('nszakaz', '/newbid')").homepage-firstscreen__button Начать сейчас
					template(v-else)
						a(href="/?itm=signup" @click.prevent="goToUrlWithReachGoal('nszakaz', '/?itm=signup')").homepage-firstscreen__button Начать сейчас

	section.shortabout
		div.container
			ul.shortabout__list
				li.shortabout__item
					img(src="/images/home/shortabout-1.png").shortabout__pic
					p.shortabout__cap крупный и малый опт
				li.shortabout__item
					img(src="/images/home/shortabout-2.png").shortabout__pic
					p.shortabout__cap в Москве и регионах
				li.shortabout__item
					img(src="/images/home/shortabout-3.png").shortabout__pic
					p.shortabout__cap все категории товаров
	section.homepage-gradient-block
		section.get-started
			div.container
				h2.homepage__title.get-started__title.homepage__title--decored С ЧЕГО НАЧАТЬ
				ul.get-started__list
					li.get-started__item
						div.get-started__pic
							img(src="/images/home/home-order.png" alt="Создайте заказ")
						p.get-started__number 1.
						p.get-started__txt #[span Бесплатно] разместить Ваш заказ с подробным описанием всех условий
					li.get-started__item
						div.get-started__pic
							img(src="/images/home/home-choose.png" alt="Дождитесь предложений")
						p.get-started__number 2.
						p.get-started__txt Выбрать поставщика из тех, кто уже готов работать с вами, #[span на тех условиях], которые Вы прописали
					li.get-started__item
						div.get-started__pic
							img(src="/images/home/home-deal.png" alt="Выберите поставщика")
						p.get-started__number 3.
						p.get-started__txt #[span Быстрое оформление] #[br]и отсутствие навязчивых звонков
					li.get-started__item
						div.get-started__pic
							img(src="/images/home/home-wait.png" alt="Заключите сделку")
						p.get-started__number 4.
						p.get-started__txt В короткие сроки получить #[span актуальные предложения] по Вашему заказу
				//- a(@click.prevent="createOrder('sozdat_zakaz')" href="/newbid").get-started__button.homepage__button Создать заказ
		section.advantages
			div.container
				h3.advantages__title.homepage__title.homepage__title--decored ПРЕИМУЩЕСТВА
				p.advantages__desc купить оптом от производителя проще, чем когда либо прежде
				ul.advantages__list
					li.advantages__item
						div.advantages__pic
							img(src="/images/home/advantage-1.png" alt="advantage time")
						h4.advantages__name Сократите время на поиск поставщиков
						p.advantages__text Без утомительных обзвонов и писем. Создавайте заказы, получайте на них ответы, или просматривайте уже готовые предложения.
					li.advantages__item
						div.advantages__pic
							img(src="/images/home/advantage-2.png" alt="advantage time")
						h4.advantages__name Бесплатно
						p.advantages__text Сервис tamtem.ru полностью бесплатен для покупателей. Вы можете создавать неограниченное количество заказов по любым категориям товаров или услуг.
					li.advantages__item
						div.advantages__pic
							img(src="/images/home/advantage-3.png" alt="advantage time")
						h4.advantages__name Высокий уровень конфидециальности
						p.advantages__text Мы ценим безопаснь прежде всего. Ваши контакты увидит только тот поставщик, которого Вы сами выберите.
					li.advantages__item
						div.advantages__pic
							img(src="/images/home/advantage-4.png" alt="advantage time")
						h4.advantages__name Просто и быстро
						p.advantages__text Простая и удобная в использовании платформа. Находите новых поставщиков в несколько кликов. Обсуждайте в чате детали сделки и получайте уведомления, чтобы не пропустить актуальные ответы на Ваши заказы.
					li.advantages__item
						div.advantages__pic
							img(src="/images/home/advantage-5.png" alt="advantage time")
						h4.advantages__name Поддержка
						p.advantages__text Вы всегда можете связаться со службой поддержки сервиса tamtem.ru. Наши специалисты помогут Вам по любым вопросам, связанным с работой сервиса или взаимодействием с заказчиком.
					li.advantages__item
						div.advantages__pic
							img(src="/images/home/advantage-6.png" alt="advantage time")
						h4.advantages__name Удобство
						p.advantages__text Вы получите уведомление, если подходящий заказ появится в сервисе.
	section.homepage-bids
		div.container
			h2.homepage__title.homepage__title--decored.homepage-bids__title Предложения поставщиков
			p.homepage-bids__desc Вы так же можете находить нужных поставщиков просматривая их предложения на доске объявлений
			ul.homepage-bids__list
				li.homepage-bids__item(v-for='bid in bids.items')
					bidcard(
						:bid='bid'
					)
	section.find-new-cl
		div.container
			div.find-new-cl__inner
				div.find-new-cl__content
					h2.find-new-cl__title Хотите найти новых поставщиков?
					p.find-new-cl__desc создайте заказ
					p.find-new-cl__note и выберите лучшие условия
					template(v-if='this.$root.isAuth')
						a(href="/newbid" @click.prevent="goToUrlWithReachGoal('nszakazniz', '/newbid')").homepage-firstscreen__button Начать сейчас
					template(v-else)
						a(href="/?itm=signup" @click.prevent="goToUrlWithReachGoal('nszakazniz', '/?itm=signup')").homepage-firstscreen__button Начать сейчас
				div.find-new-cl__illustration
					img(src="/images/home/find-new-vendors-pic.png")
	//- section.homepage-goals
		div.container
			.row
				.col
					h2.homepage__title.homepage-goals__title Почему вы здесь?
					.homepage-goals__box
						ul.homepage-goals__list.homepage-goals__list--left
							li.homepage-goals__item
								p Вы ищите поставщика, потому что цены на товар у вашего постоянного поставщика все время растут?
							li.homepage-goals__item
								p Вам нужно быстро сменить поставщика или нужен дополнительный объем?
							li.homepage-goals__item
								p Вам уже несколько раз присылали некачественный товар?
						ul.homepage-goals__list.homepage-goals__list--right
							li.homepage-goals__item
								p А может, вы занимаетесь гос. закупками и вам нужна помощь в поиске поставщиков для исполнения тендера?
							li.homepage-goals__item
								p У вас есть четкие требования к товару, которые не может выполнить ваш поставщик?
							li.homepage-goals__item
								p «Горят сроки» на производстве и нет сырья? График поставок с компаниями согласован и вам грозят штрафы?
	//- section.homepage-mainbutton
		.container
			.row
				.col
					h2.homepage-mainbutton__title С tamtem.ru вы решите эти проблемы
					a(@click.prevent="createOrder('sozdai_zakaz')" href="/newbid").homepage-mainbutton__button.homepage__button Создай заказ

	//- section.homepage-tarifs
		div.container
			.row
				.col
					div.homepage-tarifs__cap
						b 0
						span ₽
					h2.homepage__title.homepage-tarifs__title Подходит для вашего бизнеса
					p.homepage-tarifs__text Заказчикам бесплатно #[br] Поставщикам оплата от 200 рублей, в зависимости от средней суммы заказа
					a.homepage__button.homepage-tarifs__btn(href="/price" @click.prevent="lookAtPriceList('prosmotret_ceny')" ) Посмотреть цены

	section.homepage-reviews
		div.container
			h2.homepage__title.homepage-reviews__title СМИ о нас
			div(id="slider" v-stepslider="{next: '.slider-next', prev: '.slider-prev'}").homepage-reviews__wrapper
				ul(id="reviews").slider-bar.homepage-reviews__list
					li.homepage-reviews__box(v-for="item in reviews")
						a(:href="item.src").homepage-reviews__item
							p.homepage-reviews__text {{item.text}}
							p.homepage-reviews__link
								span Читать
								i
									iconLongArrow
								span.homepage-reviews__resname {{item.name}}
				ul.slider-controls.homepage-reviews__controls
					li
						button.slider-prev
							feather(type="chevron-left")
					li
						button.slider-next
							feather(type="chevron-right")
	section.homepage-feedbacks
		div.container
			h2.homepage-feedbacks__title Что говорят клиенты о нас
			ul.homepage-feedbacks__list
				li.homepage-feedbacks__item
					.homepage-feedbacks__card.feedback-card
						p.feedback-card__name Степан Орлов
						div.feedback-card__stars
							rating(:rate="5")
						div.feedback-card__text
							p Отзывы писать не умею, но по просьбе менеджера напишу, сильно понравилось как ребята сработали. Я выращиваю картофель. Свое КФХ. Зашел на сайт, нажал на заказы, смотрю, что требуется картошка, ну я и нажал, просмотрел условия, все устраивает. Отправил отклик, смутило сначала, что нужно оплачивать контакт, но менеджер объяснила, что существует гарантия возврата, если сделка сорвалась по вине заказчика. Оплатил, получил контакты-работаем. За небольшую сумму получил постоянного заказчика с хорошими объемами, которого бы сам никогда не нашел. Спасибо менеджеру Елене за терпение

				li.homepage-feedbacks__item
					.homepage-feedbacks__card.feedback-card
						p.feedback-card__name Кравцова Ирина Сергеевна
						div.feedback-card__stars
							rating(:rate="4")
						div.feedback-card__text
							p Я являюсь поставщиком в торговые сети. У меня есть производители, у которых я регулярно закупаюсь. Когда производство у одного из них встало, я не знала куда бежать, сроки горят, график поставок с сетью согласован. Я понимала, что попадаю на штрафы. Обзвонила своих поставщиков- никто не смог дать дополнительный объем. Как любой современный человек сделала запрос в интернете- вышла на сайт tamtem.ru
							p Зашла на сайт, удивилась, что нет объявлений о продаже. Мне предложили оставить заказ, я прописала требования, закрыла сайт и продолжила искать поставщиков дальше. Очень удивилась, когда через пару часов на почту пришло письмо от сервиса о том, что на мой заказ есть предложения. Перешла на сайт. Выбрала поставщика, он полностью подошел под мои условия, с ним, кстати, мы до сих пор сотрудничаем. По итогу: работой сервиса довольна, подбор поставщиков на уровне
				li.homepage-feedbacks__item
					.homepage-feedbacks__card.feedback-card
						p.feedback-card__name Петр Серебряков
						div.feedback-card__stars
							rating(:rate="4")
						div.feedback-card__text
							p Для фирмы нужны были подарки для контрагентов. Большие объемы и требования к полиграфии по ТЗ. Времени на поиск тех, кто сделает ручки, календари и блокноты и прочую мелочь, у меня не было, друг посоветовал разместить заказ на сайте tamtem.ru Сайт понятный, менеджеры отзывчивые, заказ разместил и через 3 дня уже созванивался с менеджером типографии, которая взяла мой заказ. Через неделю все было у меня. Быстро и очень удобно
	section.homepage-faq
		div.container
			h2.homepage-faq__title Часто задаваемые вопросы
			section.answers
					ul.answers__list
						li.answers__item.answer
							div.answer__head
								p.answer__name Как работает tamtem.ru?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p tamtem.ru - это удобный сервис для поставщиков и закупщиков товаров оптом, который работает по системе краудсорсинга.
						li.answers__item.answer
							div.answer__head
								p.answer__name Как создать заказ?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Краудсо́рсинг — мобилизация ресурсов людей посредством информационных технологий.
						li.answers__item.answer
							div.answer__head
								p.answer__name Как выбрать победителя?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Краудсо́рсинг — мобилизация ресурсов людей посредством информационных технологий.
						li.answers__item.answer
							div.answer__head
								p.answer__name Можно ли отредактировать заказ?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Краудсо́рсинг — мобилизация ресурсов людей посредством информационных технологий.
			div.homepage-faq__questions
				h3.homepage-faq__questions-title Остались вопросы?
				p.homepage-faq__questions-here #[a(href="/faq" @click.prevent="goToUrlWithReachGoal('zdesvopros', '/faq')") Здесь] Вы найдете больше информации.
				p.homepage-faq__questions-desc Наш сервис делает работу простой и безопасной. Экономьте время и средства на поиске заказов.
</template>

<script>
import bidcard from "../GeneralComponents/components/BidCardShortSell";
import cities from "../GeneralComponents/components/cities.vue";
import rating from "./components/rating.vue";
import iconLongArrow from "../Icons/iconLongArrow.vue";
import { Validator } from "vee-validate";
import categoriesListDrops from "../NewDeal/components/categoriesListDrops.vue";
import { mapGetters, mapActions } from "vuex";
export default {
  components: {
    bidcard,
    iconLongArrow,
    categoriesListDrops,
    cities,
    rating
  },
  directives: {
    stepslider: {
      inserted: function(el, binding) {
        let count = 1;
        let bar = el.querySelector(".slider-bar");
        let items = bar.querySelectorAll("li");
        let number = items.length;
        const prev = el.querySelector(binding.value.prev);
        const next = el.querySelector(binding.value.next);
        let step = 34;
        let unit = "%";
        let screenWidth = window.innerWidth;
        let sliderWidth = el.offsetWidth;
        let stopper = number;
        if (screenWidth > 768) {
          stopper = number - 2;
        }
        if (screenWidth <= 768) {
          step = 49;
          unit = "%";
          stopper = number - 1;
        }
        if (screenWidth <= 640) {
		  step = 100;
		  unit = "%"
          stopper = number;
        }
        window.addEventListener("resize", () => {
          // при каждом ресайзе надо обновлять целую кучу переменных.
          screenWidth = window.innerWidth;
          bar.style.left = "0";
          count = 1;
          if (screenWidth > 768) {
            sliderWidth = el.offsetWidth;
            step = 34;
            unit = "%";
            stopper = number - 2;
          }
          if (screenWidth <= 768) {
            step = 49;
            unit = "%";
            stopper = number - 1;
          }
          if (screenWidth <= 640) {
			step = 100;
			unit = "%";
            stopper = number;
          }
        });
        function nextSlide() {
          if (count < stopper) {
            bar.style.left = `-${count * step}${unit}`;
            count++;
          } else if (count == stopper) {
            bar.style.left = "0";
            count = 1;
          }
        }
        function prevSlide() {
          if (count > 1) {
            count = count - 2;
            bar.style.left = `-${count * step}${unit}`;
            count++;
          } else if (count == 1) {
            count = stopper - 1;
            bar.style.left = `-${count * step}${unit}`;
            count++;
          }
        }
        function swipedetect(callback) {
          let swipedir = "none";
          let startX;
          let startY;
          let distX;
          let distY;
          let threshold = 100;
          let restraint = 100;
          let allowedTime = 300;
          let elapsedTime;
          let startTime;
          let ismousedown = false;
          let handleswipe = callback || function(swipedir) {};
          el.addEventListener("touchstart", function(e) {
            let obj = e.changedTouches[0];
            swipedir = "none";
            distX = 0;
            startX = obj.pageX;
            startY = obj.pageY;
            startTime = new Date().getTime();
          });
          el.addEventListener("touchend", function(e) {
            let obj = e.changedTouches[0];
            distX = obj.pageX - startX;
            distY = obj.pageY - startY;
            elapsedTime = new Date().getTime() - startTime;
            if (elapsedTime <= allowedTime) {
              if (
                Math.abs(distX) >= threshold &&
                Math.abs(distY) <= restraint
              ) {
                swipedir = distX < 0 ? "left" : "right";
              }
            }
            handleswipe(swipedir);
          });
        }
        swipedetect(swipedir => {
          if (swipedir == "left") {
            nextSlide();
          }
          if (swipedir == "right") {
            prevSlide();
          }
        });
        next.addEventListener("click", () => {
          nextSlide();
        });
        prev.addEventListener("click", () => {
          prevSlide();
        });
      }
    }
  },
  data() {
    return {
      currentItem: '',
			bids: [],
      currentStep: 1,
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
      finderData: {
        name: null,
        city: null,
        category: null
      }
    };
  },
  computed: {
		...mapGetters("createBid", ["getStep"]),

  },
  methods: {
    ...mapActions("createBid", ["saveStep"]),
    /**
     * Redirect user to PriceList page and track goals in metric.
     * @param target Goal name for google/yandex metrik
     */
    lookAtPriceList(target) {
      this.yandexReachGoal(target);
      this.googleReachGoal(target);
      window.location = "/price";
    },
    /**
     * Redirect user to bid create page and track goals in metric.
     * @param target Goal name for google/yandex metrik
     */
    createOrder(target) {
      this.yandexReachGoal(target);
      this.googleReachGoal(target);
      this.$router.push({ name: "new.bid" });
    },
    searchClick() {
      this.yandexReachGoal("i_postavschik");
      this.googleReachGoal("i_postavschik");
      this.$router.push({name: 'supplier'});
    },
    startingCreateOrder() {
      this.yandexReachGoal("dalee_glavnaya");
      this.googleReachGoal("dalee_glavnaya");
      let temp = Object.assign({}, this.getStep("step_0"));
      Object.assign(temp, this.finderData);
      this.saveStep({ step: "step_0", data: temp });

      this.$router.push({ name: "new.bid" });
    },
    setCity(cityObject) {
      this.finderData.city = cityObject;
    },
    setCategory(selectedCategory) {
      this.$refs.modalFinderCategories.hide();
      this.finderData.category = selectedCategory;
    },
    nextStep() {
      if (this.currentStep < 4) {
        this.currentStep++;
        return;
      }
      if (this.currentStep == 4) {
        this.currentStep = 1;
      }
    },
    setCategory(selectedCategory) {
      this.$refs.modalFinderCategories.hide();
      this.finderData.category = selectedCategory;
    },
    getData() {
      let url =
        "/api/v1/filter/deals?page=1&per_page=12&type_deal=sell&date_published=desc";
      axios
        .get(url)
        .then(resp => {
          this.bids = resp.data.data;
        })
        .catch(error => {
          this.printErrorOnConsole(error);
        });
    },
    // методы из компонента faq
    getAllItems() {
			let items = document.querySelectorAll('.answer__head');
			let toggle = this.toggleAnswer
			for(let i=0;i<items.length;i++) {
				items[i].addEventListener("click", function(){
					toggle(items[i].parentNode);
				})
			}
		},
		toggleAnswer(el) {
			if(this.currentItem && this.currentItem !== el) {
				this.currentItem.classList.remove('opened')
				this.currentItem = el
			}
			el.classList.toggle('opened');
			this.currentItem = el
		},
  },
	created() {
		this.getData();
	},
	mounted() {
		this.getAllItems();
	},
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
$color-cold-soft: #ecf2fe;
$color-darken: #2C3444;
$color-prim: #3393FF;
.container {
	max-width: 1294px;
	padding: 0 60px;
  // Костыль для нейтрализации бага в vuetify-custom с определением максимальной ширины контейнера.
  @media (max-width: 1130px) {
    max-width: 100vw;
	}
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
.homepage {
	font-size: 14px;
	line-height: 21px;
	font-weight: 400;

	&__title {
		text-align: center;
		font-style: normal;
		font-weight: 600;
		font-size: 54px;
		line-height: 70px;
		color: $color-darken;

		@media screen and (max-width: 992px) {
			font-size: 32px;
			line-height: 41px;
		}

		@media screen and (max-width: 640px) {
			font-weight: bold;
			font-size: 22px;
			line-height: 30px;
			font-weight: bold;
		}

		&--decored {
			display: flex;
			flex-direction: column;
			align-items: center;

			&::after {
				content: "";
				margin-top: 26px;
				width: 93px;
				height: 3px;
				background: transparent;
				border-left: 80px solid $color-base-accent;
				border-right: 8px solid $color-base-accent;
				box-sizing: inherit;
				@media screen and (max-width: 640px) {
                    margin-top: 10px;
                }
			}
		}
	}

	&__button {
		@include big-rounded-btn;
		@include btnHov($color-hover-button);
		@include w-h(220px, 50px);
		text-transform: none;
		margin: 0 auto;
		font-size: 16px;
		font-weight: 600;
		padding: 0;

		@media (max-width: 768px) {
			font-size: 14px;
			@include w-h(180px, 40px);
		}

		@media (max-width: 425px) {
			@include w-h(205px, 40px);
		}
	}

	&__pageswitch {
		// margin-top: 43px;
		margin-bottom: 68px;
	}
}
.pagetype-switch {
	background-color: #ffffff;
	border: 1px solid $color-base-accent;
	border-radius: 100px;
	display: flex;
	@media screen and (max-width: 425px) {
		width: 100%;
	}
	&__btn {
		min-width: 241px;
		min-height: 60px;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #fff;
		color: $color-base-accent;
		border-radius: 100px;
		transition: color 0.4s, background-color 0.4s;
		position: relative;
		&.is-active {
			color: #fff;
			background-color: $color-base-accent;
			// z-index: 2;
		}
		&:hover {
			text-decoration: none;
		}
		@media screen and (max-width: 992px) {
			min-height: 43px;
			min-width: 170px;
			font-weight: 600;
			font-size: 15px;
			line-height: 18px;
		}
		@media screen and (max-width: 425px) {
			min-height: 43px;
			min-width: 50%;
			font-weight: 600;
			font-size: 13px;
			line-height: 16px;
		}
	}
}
.shortabout {
	padding: 40px 0;
	background: #fff;
	@media screen and (max-width: 640px) {
		background: url(/images/home/home-bg-1.png) no-repeat;
		background-size: cover;
	}
	@media screen and (max-width: 640px) {

	}
	&__list {
		width: 100%;
		list-style: none;
		display: flex;
		justify-content: space-around;
		@media screen and (max-width: 640px) {
			display: grid;
			grid-template-columns: 1fr 1fr;
			row-gap: 35px;
		}
	}
	&__item {
		display: flex;
		flex-direction: column;
		align-items: center;
		&:last-child {
			@media screen and (max-width: 640px) {
				grid-column: 1 / -1;
				justify-self: center;
			}
		}
	}
	&__pic {
		margin-bottom: 27px;
	}
	&__cap {
		font-weight: 500;
		font-size: 18px;
		line-height: 22px;
		color: $color-darken;
		text-align: center;
		@media screen and (max-width: 768px) {
			font-size: 15px;
			line-height: 18px;
		}
	}
}
.homepage-firstscreen {
	background-color: #ecf2fe;
	padding-top: calc(136px + 43px);
	min-height: 100vh;
	background:
		url(/images/home/home-bg-2.png) no-repeat,
		url(/images/home/home-bg-pic.png) no-repeat,
		url(/images/home/home-bg-1.png) no-repeat,
		#ffffff;
	background-position:
		bottom -150px right,
		bottom 25% right 10%,
		top left,
		top left;
	background-size: auto, 40%, cover, cover;

	@media screen and (max-width: 1440px) {
        background-position:
            bottom -170px right -100px,
            bottom 25% right 0%,
            top left,
            top left;
        background-size: auto, 46%, cover, cover;
    }

    @media screen and (max-width: 1200px) {
        background-position:
            bottom -170px right -200px,
            bottom 26% right 0%,
            top left,
            top left;
        background-size: auto, 48%, cover, cover;
    }

	@media screen and (max-width: 992px) {
		background-position:
			bottom -170px right -300px,
			bottom 26% right 0%,
			top left,
			top left;
		background-size: auto, 50%, cover, cover;
	}

	@media screen and (max-width: 768px) {
		padding-top: calc(68px + 43px);
		min-height: 500px;
		background:
			url(/images/home/home-bg-2-768.png) no-repeat,
			url(/images/home/home-bg-pic.png) no-repeat,
			url(/images/home/home-bg-1.png) no-repeat,
			#ffffff;
		background-position:
			bottom -50px right 0px,
			bottom 26% right 0%,
			top left,
			top left;
		background-size: auto, 50%, cover, cover;
	}

	@media screen and (max-width: 640px) {
		background: url(/images/home/home-bg-1.png) no-repeat;
		background-size: cover;
	}

	&__inner {
		display: flex;
	}

	&__box {
		width: 100%;
		padding-bottom: 150px;

		@media screen and (max-width: 640px) {
			padding-bottom: 50px;
		}

		@media screen and (max-width: 425px) {
			padding-bottom: 30px;
		}
	}

	&__title {
		font-weight: 800;
		font-size: 64px;
		line-height: 70px;
		color: $color-darken;

		@media screen and (max-width: 992px) {
			font-weight: 800;
			font-size: 46px;
			line-height: 48px;
		}

		@media screen and (max-width: 425px) {
			font-weight: 800;
			font-size: 30px;
			line-height: 36px;
		}
	}

	&__desc {
		font-weight: 300;
		font-size: 40px;
		line-height: 49px;
		margin-top: 40px;

		@media screen and (max-width: 992px) {
			font-weight: 300;
			font-size: 26px;
			line-height: 32px;
		}

		@media screen and (max-width: 425px) {
			font-weight: 300;
			font-size: 22px;
			line-height: 27px;
		}
	}

	&__note {
		font-style: normal;
		font-weight: normal;
		font-size: 18px;
		line-height: 22px;
		margin-top: 30px;

		@media screen and (max-width: 992px) {
			font-size: 14px;
			line-height: 17px;
		}

		@media screen and (max-width: 425px) {
			font-weight: normal;
			font-size: 14px;
			line-height: 19px;
		}
	}

	&__button {
		font-style: normal;
		font-weight: bold;
		font-size: 15px;
		line-height: 18px;
		text-transform: uppercase;
		color: #fff;
		background-color: $color-prim;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 350px;
		height: 66px;
		background: radial-gradient(112.69% 1759.3% at 72.51% 122.39%, #5CA9FF 0%, rgba(92, 169, 255, 0) 100%), #3393FF;
		border-radius: 100px;
		margin-top: 52px;

		&:hover {
			text-decoration: none;
		}

		@media screen and (max-width: 992px) {
			font-weight: bold;
			font-size: 15px;
			line-height: 18px;
			width: 245px;
			height: 46px;
		}

		@media screen and (max-width: 425px) {}
	}
}

.finder-ctg {
  display: flex;
  align-items: center;
  padding: 0 15px;
  min-height: 43px;
  border-radius: 35px;
  box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
  background-color: #ffffff;
  &__label {
    color: #bebebe;
    font-size: 12px;
  }
  &__toggleicon {
    border: none;
    background: none;
    margin-left: auto;
    display: flex;
    align-items: center;
  }
}
.homepage-goals {
  padding-top: 15px;
  padding-bottom: 54px;
  @media (max-width: 768px) {
    padding-top: 40px;
  }
  @media (max-width: 425px) {
    padding-top: 10px;
  }
  &__title {
    margin-bottom: 60px;
    @media (max-width: 768px) {
      margin-bottom: 55px;
    }
    @media (max-width: 425px) {
      margin-bottom: 32px;
    }
  }
  &__box {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    @media (max-width: 600px) {
      grid-template-columns: 1fr;
      gap: 40px;
    }
  }
  &__item {
    list-style: none;
    &:not(:last-child) {
      margin-bottom: 40px;
    }
    p {
      padding-left: 45px;
      position: relative;
      &::before {
        content: "";
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12.997' height='9.231' viewBox='0 0 12.997 9.231'%3E%3Cg id='check-square' transform='translate(1.414 1.414)'%3E%3Cpath fill='none' stroke='%2331c26e' stroke-linecap='round' stroke-linejoin='round' stroke-miterlimit='10' stroke-width='2px' d='M-1.525 3.557l3.07 3.26L5.63 2.895 8.645 0' transform='translate(1.525)'/%3E%3C/g%3E%3C/svg%3E%0A")
            no-repeat,
          #d4f5de;
        background-position: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        position: absolute;
        top: 0;
        left: 0;
      }
    }
  }
}
.homepage-mainbutton {
  background: url(/images/home/home-bg-mainbutton.png) no-repeat;
  background-size: cover;
  background-position: top center;
  padding-top: 90px;
  padding-bottom: 50px;
  @media (max-width: 768px) {
    background-image: url(/images/home/home-bg-mainbutton-768.png);
    padding-top: 64px;
    padding-bottom: 40px;
  }
  @media (max-width: 320px) {
    background-image: url(/images/home/home-bg-mainbutton-320.png);
    padding-top: 60px;
  }
  &__title {
    margin-bottom: 42px;
    text-align: center;
    font-size: 26px;
    @media (max-width: 768px) {
      font-size: 22px;
      margin-bottom: 30px;
    }
    @media (max-width: 425px) {
      font-size: 20px;
      margin-bottom: 35px;
    }
  }
  &__button {
  }
}
.homepage-gradient-block {
	background: url(/images/home/complain-bg.png), #fff;
	background-repeat: no-repeat;
	background-position: bottom left, top left;
	background-size: 100% 70%;
}
.get-started {
	padding-top: 130px;
	padding-bottom: 100px;
	@media(max-width: 768px) {
		padding-top: 90px;
		padding-bottom: 90px;
	}
	&__title {
		margin-bottom: 70px;
		@media(max-width: 530px) {
			margin-bottom: 24px;
		}
	}
	&__list {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 40px;
		margin-bottom: 64px;
		@media(max-width: 768px) {
			grid-template-columns: 1fr 1fr;
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
	&__item {
		display: grid;
		grid-template-columns: 1fr 1fr;
		grid-template-areas:
			"pic num"
			"pic txt";
		column-gap: 10px;
		@media screen and (max-width: 992px){
			row-gap: 10px;
			grid-template-columns: 1fr;
			grid-template-areas: "pic" "num" "txt";
		}
		@media screen and (max-width: 425px) {
			grid-template-columns: 1fr 1fr 1fr;
			grid-template-areas:
				"pic pic num"
				"txt txt txt";
		}
	}
	&__pic {
		grid-area: pic;
		@media screen and (max-width: 992px){
			width: 100%;
		}
		img {
			width: 100%;
			height: auto;
		}
	}

	&__number {
		grid-area: num;
		align-self: end;
		font-weight: 600;
		font-size: 54px;
		// line-height: 28px;
		color: $color-base-accent;
		margin-bottom: 33px;

		@media screen and(max-width: 992px) {
			font-weight: 600;
			font-size: 34px;
			line-height: 28px;
		}
	}
	&__txt {
		grid-area: txt;
		a {
			color: $color-base-accent;
			text-decoration: none;
		}
		span {
			color: $color-base-accent;
		}
	}
}
.advantages {
	padding-top: 100px;
	padding-bottom: 171px;
	@media(max-width: 768px) {
		padding-top: 90px;
		padding-bottom: 134px;
	}
	&__title {
		margin-bottom: 33px;
	}
	&__desc {
		font-weight: normal;
		font-size: 24px;
		line-height: 30px;
		margin-bottom: 90px;
		color: #2C3444;
		text-align: center;
		@media (max-width: 640px) {
            font-size: 14px;
            line-height: 20px;
            margin-bottom: 60px;
        }
	}
	&__list {
		list-style: none;
		display: grid;
		grid-template-columns: 1fr 1fr 1fr;
		column-gap: 45px;
		row-gap: 45px;
		@media screen and (max-width: 992px) {
			grid-template-columns: 1fr 1fr;
		}
		@media screen and (max-width: 640px) {
			grid-template-columns: 1fr;
		}
	}
	&__item {
		@media screen and (max-width: 640px) {
			display: flex;
			flex-direction: column;
			align-items: center;
		}
	}
	&__pic {
		width: 65px;
		height: 65px;
		margin-bottom: 30px;
		img {
			width: 100%;
			height: auto;
		}
	}
	&__name {
		font-style: normal;
		font-weight: 500;
		font-size: 19px;
		line-height: 24px;
		letter-spacing: -0.02em;
		margin-bottom: 15px;
		text-align: center;
	}
	&__text {
		font-weight: normal;
		font-size: 16px;
		line-height: 24px;
		/* or 150% */
		letter-spacing: -0.02em;
		color: #11141A;
		@media screen and (max-width: 640px) {
			text-align: center;
		}
	}
}
.homepage-bids {
	padding-top: 210px;
	padding-bottom: 140px;
	@media screen and (max-width: 768px) {
		padding-top: 157px;
		padding-bottom: 100px;
	}
	@media screen and (max-width: 640px) {
		padding-top: 107px;
	}
	&__title {
		margin-bottom: 33px;
	}
	&__desc {
		font-weight: normal;
		font-size: 24px;
		line-height: 30px;
		margin-bottom: 90px;
		color: #2C3444;
		text-align: center;
		@media (max-width: 640px) {
            font-size: 14px;
            line-height: 20px;
            margin-bottom: 60px;
        }
	}
	&__list {
		list-style: none;
		display: grid;
		grid-template-columns: 1fr 1fr;
		column-gap: 15px;
		row-gap: 20px;
		@media screen and (max-width: 768px) {
			grid-template-columns: 1fr;
		}
	}
	&__item {
		&:nth-child(n + 7) {
			display: none;
		}
		@media (max-width: 768px) {
			&:nth-child(n + 4) {
				display: none;
			}
		}
	}
}

.find-new-cl {
	padding-top: 160px;
	padding-bottom: 130px;
	background: url(/images/home/newclient-bg.jpg) no-repeat;

	@media screen and (max-width: 992px) {
		padding: 86px 0;
	}
	@media screen and (max-width: 768px) {

	}
	@media screen and (max-width: 640px) {

	}
	&__inner {
		display: flex;
		position: relative;
	}
	&__content {
		width: 70%;
		position: relative;
		@media screen and (max-width: 768px) {
			width: 85%;
			z-index: 2;
		}
	}
	&__illustration {
		width: 30%;
		display: flex;
		align-items: center;
		justify-content: center;
		img {
			width: 90%;
			height: auto;
		}
		@media screen and (max-width: 768px) {
			position: absolute;
			top: 50%;
			right: 5%;
			transform: translateY(-50%);
			width: 40%;
			img {
				width: 100%;
			}
		}
		@media screen and (max-width: 640px) {
			display: none;
		}
	}
	&__title {
		font-weight: 800;
		font-size: 64px;
		line-height: 70px;
		color: $color-darken;
		@media screen and (max-width: 992px) {
			font-weight: 800;
			font-size: 46px;
			line-height: 48px;
		}
		@media screen and (max-width: 640px) {
			font-weight: 800;
			font-size: 30px;
			line-height: 38px;
		}
	}
	&__desc {
		font-weight: 300;
		font-size: 40px;
		line-height: 49px;
		margin-top: 40px;
		@media screen and (max-width: 992px) {
			font-weight: 300;
			font-size: 26px;
			line-height: 32px;
		}
		@media screen and (max-width: 640px) {
			font-size: 17px;
			line-height: 21px;
		}
	}
	&__note {
		font-style: normal;
		font-weight: normal;
		font-size: 18px;
		line-height: 22px;
		margin-top: 30px;
		@media screen and (max-width: 992px) {
			font-size: 14px;
			line-height: 17px;
		}
		@media screen and (max-width: 640px) {
			font-size: 14px;
			line-height: 19px;
		}
	}

	&__button {
		font-style: normal;
		font-weight: bold;
		font-size: 15px;
		line-height: 18px;
		text-transform: uppercase;
		color: #fff;
		background-color: $color-prim;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 350px;
		height: 66px;
		background: radial-gradient(112.69% 1759.3% at 72.51% 122.39%, #5CA9FF 0%, rgba(92, 169, 255, 0) 100%), #3393FF;
		border-radius: 100px;
		margin-top: 52px;
		&:hover {
			text-decoration: none;
		}
		@media screen and (max-width: 992px) {
			font-weight: bold;
			font-size: 15px;
			line-height: 18px;
			height: 46px;
			width: 245px;
		}
		@media screen and (max-width: 640px) {

		}
	}
}
.homepage-faq {
	padding-top: 100px;
	padding-bottom: 150px;
	@media(max-width: 768px) {
		padding-top: 60px;
		padding-bottom: 60px;
	}
	&__title {
		font-style: normal;
		font-weight: 500;
		font-size: 34px;
		line-height: 41px;
		color: #2C3444;
		text-align: center;
		margin-bottom: 67px;
		@media (max-width: 640px) {
            font-style: normal;
            font-weight: bold;
            font-size: 22px;
            line-height: 28px;
            text-align: center;
        }
	}
	.answers {
		@include contentbox;
		margin-bottom: 58px;
		padding: 0px;
		overflow: hidden;
		&__title {
			font-size: 26px;
			font-weight: 500;
			color: $color-base-accent;
			margin: 60px 60px 60px 60px;
			@media(max-width: 768px) {
				margin: 41px 31px;
			}
			@media(max-width: 425px) {
				font-size: 20px;
				margin: 36px 17px;
			}
		}
		&__item {
			cursor: pointer;
			list-style: none;
		}
	}
	.answer {
		a {
			color: #000;
			text-decoration: underline;
		}
		&__head {
			padding: 0 60px;
			display: flex;
			// justify-content: space-between;
			position: relative;
			@media(max-width: 768px) {
				padding: 0 31px;
			}
			@media(max-width: 425px) {
				padding: 0 17px;
			}
		}
		&__name {
			font-size: 18px;
			line-height: 22px;
			font-weight: 500;
			padding: 45px 0;
			width: 100%;
			border-top: 1px solid $color-border-gray;
			padding-right: 25px;
			@media(max-width: 768px) {
				font-size: 17px;
				padding: 32px 0;
				padding-right: 25px;
			}
			@media(max-width: 425px) {
				font-size: 15px;
				padding: 28px 0;
				padding-right: 25px;
			}
		}
		&__icon {
			position: absolute;
			width: 24px;
			height: 24px;
			display: flex;
			right: 60px;
			top: calc(50% - 12px);
			@media(max-width: 768px) {
				right: 31px;
			}
			@media(max-width: 425px) {
				right: 17px;
			}
			& /deep/ svg {
				stroke: $color-base-accent;
				stroke-width: 3px;
				@media(max-width: 425px) {
					stroke-width: 2px;
				}
			}
			&-opened {
				display: none;
			}
		}
		&__text {
			color: #fff;
			background-color: $color-base-accent;
			font-size: 18px;
			line-height: 28px;
			@media(max-width: 768px) {
				font-size: 17px;
			}
			@media(max-width: 425px) {
				font-size: 15px;
			}
			a {
				color: #fff;
				text-decoration: underline;
			}
			p, ul, ol {
				width: 60%;
				@media(max-width: 768px) {
					width: 90%;
				}
				@media(max-width: 425px) {
					width: 100%;
					padding-right: 10px;
				}
			}
			p:not(:last-child) {
				margin-bottom: 15px;
			}
			ul {
				margin-bottom: 15px;
				margin-left: 20px;
				li {
					list-style-type: disc;
				}
			}
			ol {
				margin-bottom: 15px;
				margin-left: 20px;
			}
			// unactive
			height: 0;
			overflow: hidden;
			padding: 0;
			transition: 0.3s;
		}
		&.opened {
			a {
				color: #fff;
			}
			.answer__head {
				background: $color-base-accent;
				color: #fff;
			}
			.answer__name {
				border: none;
			}
			.answer__icon-closed {
				display: none;
			}
			.answer__icon-opened {
				display: block;
				& /deep/ svg {
					stroke: #fff;
				}
			}
			.answer__text {
				height: auto;
				padding: 45px 60px;
				@media(max-width: 768px) {
					padding: 34px 31px;
				}
				@media(max-width: 425px) {
					padding: 32px 17px;
				}
			}
		}
	}
	&__questions {
		&-title {
			font-weight: 500;
			font-size: 24px;
			line-height: 29px;
			color: #2C3444;
			text-align: center;
			margin-bottom: 11px;
		}
		&-here {
			font-weight: normal;
			font-size: 16px;
			line-height: 20px;
			text-align: center;
			margin-bottom: 51px;
			a {
				color: #0079B2;
				text-decoration: underline;
			}
		}
		&-desc {
			font-weight: normal;
			font-size: 12px;
			line-height: 15px;
			text-align: center;
			color: #11141A;
		}
	}
}
.homepage-tarifs {
  background-color: $color-cold-soft;
  padding-bottom: 50px;
  background: url(/images/home/home-bg-prices.png) no-repeat;
  background-position: center;
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
    margin-bottom: 36px;
    padding: 0 10px;
  }
}

.homepage-reviews {
  background: #ffffff;
  padding-top: 130px;
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
    @media (max-width: 768px) {
      display: flex;
      gap: 0;
    }
  }
  &__box {
    padding: 5px;
    @media (max-width: 768px) {
      // чтобы рассчитать ширину элемента в слайдере на этом разрешении - берем половину экрана, вычитаем отступ между элементами и один внутренний отступ контейнера
      width: calc(50vw - 1% - 30px);
      flex-shrink: 0;
      &:not(:last-child) {
        // margin-right: 14px;
        margin-right: 1%;
      }
	}
	// а здесь слайд занимает весь контейнер, поэтому здесь из полной ширины вычитаются паддинги контейнера
    @media (max-width: 640px) {
		width: calc(100vw - 30px);
		margin: 0;
		&:not(:last-child) {
			margin-right: 0;
      	}
	}
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
      .homepage-reviews__link i {
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
    flex-wrap: wrap;
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
.homepage-feedbacks {
  // background: url(/images/home/home-bg-1.png) no-repeat;
  // background-size: cover;
  padding: 130px 0 60px;
  @media (max-width: 768px) {
    padding-top: 90px;
  }
  &__title {
	font-style: normal;
	font-weight: 500;
	font-size: 34px;
	line-height: 41px;
	color: #2C3444;
	margin-bottom: 70px;
	text-align: center;
	@media (max-width: 640px) {
		font-style: normal;
		font-weight: bold;
		font-size: 22px;
		line-height: 28px;
		text-align: center;
	}
  }
  &__towrite {
    margin-bottom: 50px;
  }
  &__list {
    list-style: none;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 30px;
    @media (max-width: 768px) {
      grid-template-columns: 1fr;
    }
  }
}
.towrite {
  display: flex;
  justify-content: center;
  &__pic {
    border-radius: 20px;
    width: 72px;
    height: 72px;
    background-color: #fff;
    margin-right: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  &__info {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  &__cap {
    font-size: 18px;
    font-weight: 600;
  }
  &__stat {
    font-weight: 500;
  }
  &__link {
    font-weight: 500;
    color: $color-base-accent;
    text-decoration: underline;
  }
}
.feedback-card {
  &__name {
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 8px;
  }
  &__stars {
    display: flex;
    list-style: none;
    margin-bottom: 23px;
  }
  &__text {
    @media (max-width: 768px) {
      font-size: 12px;
    }
    p {
      &:not(:last-child) {
        margin-bottom: 26px;
      }
    }
  }
}

.input-wrapper {
  position: relative;
  &--background-white {
    background-color: white;
    input {
      background-color: transparent;
      z-index: 1;
      position: relative;
    }
    .input-wrapper__action {
      z-index: 0;
    }
  }
  &__input {
    @include hamster-field;
    font-size: 14px;
    line-height: 19px;
    width: 100%;
    padding-right: 26px;
    &::placeholder {
      color: $color-order-input-placeholder;
    }
  }
  &__action {
    position: absolute;
    height: 20px;
    top: 11px;
    right: 10px;
    display: flex;
    align-items: center;
    button {
      outline: none;
    }
  }
  &.errors {
    .errors-list {
      margin: 5px 0;
      padding: 0px 10px;
      font: $font-regular;
      font-style: normal;
      font-size: 10px;
      color: $danger;
      display: block;
      position: absolute;
      top: 100%;
      left: 0;
    }
    input,
    textarea {
      border: 1px solid $danger;
    }
  }
}
</style>