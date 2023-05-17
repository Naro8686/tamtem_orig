<template lang="pug">
	section.faq
		section.blade-questions
			.container
				h2.blade-questions__title FAQ
				section.answers
					h3.answers__title Общие понятия
					ul.answers__list
						li.answers__item.answer
							div.answer__head 
								p.answer__name Что такое сервис #[a(href="https://tamtem.ru") tamtem.ru?]
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p tamtem.ru - это удобный сервис для поставщиков и закупщиков товаров оптом, который работает по системе краудсорсинга.
						li.answers__item.answer
							div.answer__head
								p.answer__name Что такое краудсорсинг?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Краудсо́рсинг — мобилизация ресурсов людей посредством информационных технологий.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Почему краудсорсинг, и чем он поможет мне?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Эффективность краудсорсинга в несколько раз превышает эффективность даже самых удачных рекламных кампаний, потому что позволяет в короткие сроки охватить максимальное количество людей и компаний.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Что такое agent.tamtem.ru?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Каждый может выступить в роли агента, рекомендовать поставщиков и заказчиков друг другу и зарабатывать на этом.
								p То есть мы в чистом виде используем принцип краудсорсинга, соединяя на своей площадке желание одного (точнее, двух – Поставщика и Заказчика) и возможности других (Агентов), решая вопрос организации оптовых закупок в максимально короткие сроки и давая возможность заработать всем участникам рынка.
								p #[a(href="https://agent.tamtem.ru/") agent.tamtem.ru] - это сервис для физических лиц, которые могут привлекать компании для работы в сервисе tamtem.ru и получать прибыль от активности данных компаний. Все расходы на отчисления берет на себя компания ООО”Акстек”. Участники сервиса tamtem.ru не несут дополнительных расходов.
								p Важно! Сервис #[a(href="https://agent.tamtem.ru/") agent.tamtem.ru] не берет деньги с пользователей, для агентов использование сервиса бесплатно.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как работает tamtem.ru?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Заказчик размещает заказ, Поставщики оставляют свои предложения по заказу, из которых Заказчик выбирает наиболее подходящее ему.
								p Работа сервиса оптовых заказов tamtem.ru построена так, что заказ получает только компания, которую выбрал Заказчик. Оплата контакта происходит после выбора Поставщика в качестве победителя.
								p Сумма оплаты за контакт равна 0.10% от средней суммы сделки.
						//- li.answers__item.answer
						//- 	div.answer__head 
						//- 		p.answer__name Что делать если нужна помощь?
						//- 		span.answer__icon
						//- 			i.answer__icon-closed
						//- 				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
						//- 			i.answer__icon-opened
						//- 				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
						//- 	div.answer__text
						//- 		p Вы можете связаться со службой поддержки сервиса tamtem.ru по телефону 8-800-700-14-76.
						//- 		p Написать в чат
						//- 		p Изучить информацию в нашем блоге.
						//- 		p Мы всегда поможем вам!
				section.answers
					h3.answers__title Пользовательский интерфейс
					ul.answers__list
						li.answers__item.answer
							div.answer__head
								p.answer__name Как войти или зарегистрироваться?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								ul
									li В правом верхнем углу сайта  #[a(href="https://tamtem.ru/") https://tamtem.ru/] нажмите на «Войти | Регистрация». Откроется окно авторизации.
									li Если у вас нет учетной записи на tamtem.ru, нажмите кнопку “Регистрация” и пройдите два шага регистрации нового пользователя, заполнив соответствующие поля.
									li Введите ИНН компании и нажмите значок поиска справа от поля, если организация еще не зарегистрирована на сервисе, то продолжите заполнять поля по порядку. #[br] Важно! Если компания была ранее добавлена на сервис tamtem.ru, а вы являетесь ее владельцем, вам необходимо написать в службу технической поддержки и заявить права на данную компанию.
									li Завершите регистрацию, выбрав категории, которые подходят под деятельность вашей компании.
								p
									i Пример
								p
									i Компания занимается оптовой продажей овощей. Выбираем категорию “Продукты питания”, подкатегорию “Овощи и фрукты”.
								p По данным категориям будет формироваться страница с заказами для вашей компании и рассылаться уведомления. Настроить уведомления или отключить их можно в Личном кабинете.
								p Теперь проверьте почту и, перейдя по ссылке в письме, подтвердите свою регистрацию на tamtem.ru и начните работу с сервисом.
								p 
									i Если вы перешли в сервис tamtem.ru по рекомендации (реферальной ссылке) на конкретный заказ?
								p По рекомендации (реферальной ссылке) вы переходите на конкретный заказ, где в поле “ИНН” уже будет введен ИНН вашей компании.
								p Проверьте правильность введенного ИНН, если он введен правильно и вы являетесь владельцем этой компании, заполните оставшиеся поля в форме регистрации.
								p Если ИНН введен неправильно, закройте окно регистрации (вы попадете на главную страницу сервиса или страницу заказа) и начните регистрацию компании заново (смотрите пункт #[i “Как войти или зарегистрироваться?”]).
				section.answers
					h3.answers__title Личный кабинет
					ul.answers__list
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как редактировать Профиль?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Откройте Личный кабинет компании. Перейдите в раздел Профиль. Нажмите кнопку “Редактировать”.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как настроить уведомления о заказах?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Чтобы настроить категории, по которым вы хотите получать уведомления о поступлении заказа или отключить их совсем, зайдите в Личный кабинет и перейдите во вкладку “Уведомления”.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Какую информацию можно узнать в Личном кабинете компании?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p
									i Мои заказы
								p В данном разделе Личного кабинета размещены заказы, которые вы создали.
								p Вы можете узнать статус каждого заказа, открыть каждый из заказов и посмотреть их расширенные версию, а также выбрать Победителя, если это не было сделано ранее.
								p
									i Мои предложения
								p В данном разделе Личного кабинеты будут размещены все заказы, по которым вы отправили свои предложения как Поставщик. Вы можете узнать статус предложения, а также отменить предложение.
								p Важно! 
								p Если ваше предложение было выбрано Заказчиком, отменить предложение невозможно.
								p Подробности смотрите в Правилах пользования сервисом.
								p
									i Баланс
								p В данном разделе содержится информация по вашему виртуальному кошельку в сервисе tamtem.ru, история платежей и вся подробная информация по операциям.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Какие статусы могут быть у предложения?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p У вашего предложения может быть 4 статуса:
								ul
									li “На модерации” (ваше предложение находится на модерации);
									li “Ожидание” (ваше предложение прошло модерацию, ожидание выбора заказчика);
									li “Принято” (вас выбрали как поставщика по данному заказу. При повторном нажатии на заказ вы увидите контакты заказчика и сможете с ним связаться, если выполнены условия оплаты);
									li “Не активно” (ваше предложение отклонено модератором , отредактируйте предложение в соответствии с Правилами пользования сервисом и отправьте заново).
				section.answers
					h3.answers__title Работа с заказами
					ul.answers__list
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как создать заказ?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								ul
									li Для создания заказа нажмите кнопку “Создать заказ”.
									li Далее заполните поля в каждом из 4-х шагов.
								p Важно! Для удобства наших пользователей возможно сформировать заказ с помощью персонального менеджера. Для этого нужно нажать кнопку “Создать заказ”, справа вверху будет кнопка “Поможем с созданием заказа” заполните поля, и менеджер свяжется с вами в удобное для вас время.
								ul
									li После того, как вы нажмете кнопку “Разместить заказ”, заказ на покупку появится на сайте и уйдет на модерацию. До завершения процедуры модерации заказ находится во вкладке “На модерации”.
									li Как только заказ пройдет модерацию, вам на почту придет уведомление о публикации заказа, а на сервисе он переместится во вкладку “Активные”.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Что делать если заказ отклонен модерацией?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Если заказ был отклонен,  значит он не соответствует правилам сервиса. Отредактируйте объявление и отправьте повторно на модерацию или свяжитесь со службой поддержки.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как выбрать Победителя?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p В Личном кабинете в разделе “Мои заказы”, нажмите на интересующий вас заказ, и вам откроется расширенная форма заказа, где будут все предложения Поставщиков по данному заказу. #[br] Изучите предложения  и отметьте подходящее, нажав кнопку “Принять предложение”.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Можно ли отредактировать заказ?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Нет, заказ отредактировать нельзя, чтобы избежать ситуаций изменения условий заказа Заказчиком после принятия предложения.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Можно ли указывать контактные данные в тексте заказа или прикрепленных файлах?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p В тексте объявления и прикрепленных к нему файлах запрещено указывать контактные данные.
				section.answers
					h3.answers__title Работа с предложениями
					ul.answers__list
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как найти заказ на интересующую вас тему?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Найти заказ можно двумя способами:
								ul
									li через поисковую строку на сайте;
									li настроить уведомления на получение заказов в Личном кабинете в разделе “Уведомления”.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как отправить предложение?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								ul
									li Перейдите в интересующий вас заказ по ссылке.
									li Слева от каждого раздела отметьте те условия, которые вы готовы выполнить, а в поле “Предложения” внесите цену в числовом выражении и отметьте галочкой, включена ли доставка в стоимость.
									li В поле “Комментарий” напишите ваши предложения по тем пунктам, которые вы не сможете выполнить. Возможно, Заказчику подойдут данные условия.
									li После нажатия кнопки “Отправить” предложение уходит на модерацию. После отправки предложения вы сможете увидеть сумму оплаты за контактные данные Заказчика
						li.answers__item.answer
							div.answer__head 
								p.answer__name За что я должен платить?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Вы платите только за контакты Заказчика, который выбрал вас на роль поставщика. Вы не платите за отправку предложения. 
						li.answers__item.answer
							div.answer__head 
								p.answer__name Сколько я должен заплатить?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p 0.10% от средней суммы по заказу.
								p
									i Пример . Заказ на 50 тонн картофеля с ценой 7-8 р за кг, средняя сумма сделки будет равна 375 000 рублей. Цена за контакт будет равна 563 рублям.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Когда я должен платить?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Оплата производится, если Заказчик выберет ваше предложение среди других. Вы можете пополнить кошелек заранее или в течение 72 часов после выбора вас на роль Поставщика.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как понять, что меня выбрали на роль Поставщика?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p Вам придет уведомление на e-mail и оповещение на сервисе tamtem.ru.
						li.answers__item.answer
							div.answer__head 
								p.answer__name Как пополнить кошелек и оплатить контакт?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p После авторизации на сайте войдите в Личный кабинет и выберите  вкладку “Баланс”.
								p У вас есть два варианта оплаты:
								ul
									li безналичный;
									li карта.
								p Введите в поле “Сумма” введите необходимую сумму и выберите вид оплаты.
								ul
									li При оплате банковским переводом деньги поступают на счет в зависимости от банка через какое-то время после оплаты. Более точные сроки можно узнать у представителя вашего банка.
									li При оплате картой  происходит переадресация на платежную систему #[a(href="https://payture.com/" target="_blank") Payture] и кошелек пополняется моментально на сайте.
						li(id="answer-failing-order").answers__item.answer
							div.answer__head 
								p.answer__name Что делать, если сделка не состоялась по вине заказчика?
								span.answer__icon
									i.answer__icon-closed
										feather(type="plus")
									i.answer__icon-opened
										feather(type="minus")
							div.answer__text
								p В случае выбора вас победителем в заказе и оплаты контактов, сделка не совершилась по вине заказчика (введены не верные данные, не выходит на контакт и т.д.) вы можете вернуть потраченные средства за контакт. Для этого требуется зайти в личный кабинет, перейти в Мои предложения, выбрать нужный заказ и нажать кнопку "Сделка не состоялась" . В появившемся окне описать причину, по которой сделка не состоялась.
								p Важно!
								p В случае если причиной отказа от сделки является поставщик (не верно указаны цены, данные, условия и т.д.) средства возврату не подлежат. Будьте внимательны при отправке предложения, более подробную информацию читайте в "правилах использования сервиса".
								p После чего менеджеры рассмотрят ваш запрос на возврат средств, если подтвердится факт описанный в вашем комментарии, и действительно заказчик является причиной срыва сделки, то деньги вернутся на ваш счет в полном объеме за данный контакт.
				//- ******************************
				section.answers-helpcard
					div.answers-helpcard__pic
						img(src="/images/faq/answ-help.jpg")
					div.answers-helpcard__content.help-info
						h4.help-info__title Что делать,#[br] если нужна помощь?
						div.help-info__instructions
							p.help-info__text Вы можете связаться со службой поддержки сервиса agent.tamtem.ru по телефону +7 930 720 23 00
							p.help-info__text Изучить информацию в нашем блоге
						ul.help-info__pills
							li.help-info__pill.help-info__phone
								a(href="tel:+79307202300") +7 930 720 23 00
							//- li.help-info__pill.help-info__chat
							//- 	a(href="javascript:void(0);") чат
							li.help-info__pill.help-info__blog
								a(href="http://blog.tamtem.ru/") блог
						div.help-info__cap
							p.help-info__text.help-info__text--center Мы всегда поможем вам!
						ul.help-info__politics
							li
								a(href="/files/agreement.pdf") Пользовательское соглашение
							li
								a(href="/files/politic.doc") Политика конфиденциальности
							li
								a(href="/files/terms_of_use.pdf") Правила использования
				section.advices
					h3.advices__title Полезные советы
					ul.advices__list
						li.advices__item
							a(href="http://blog.tamtem.ru/news/companynews/vozmozhnosti_dlya_agentov" target="_blank").advices__item-link
								p.advices__item-pic
									img(src="/images/faq/1-1.png")
								p.advices__item-text
									| Больше людей-больше возможностей
						li.advices__item
							a(href="http://blog.tamtem.ru/news/companynews/oplata_agentam" target="_blank").advices__item-link
								p.advices__item-pic
									img(src="/images/faq/2-1.png")
								p.advices__item-text
									| Почему мы готовы платить агентам?
						li.advices__item
							a(href="http://blog.tamtem.ru/news/companynews/kak-agenty-nachat-rabotat" target="_blank").advices__item-link
								p.advices__item-pic
									img(src="/images/faq/3-1.png")
								p.advices__item-text
									| Как агенту начать работать с tamtem.ru
</template>
<script>
export default {
	data() {
		return {
			currentItem: ''
		}
	},
	created(){
		if (this.$route.hash){
			const id = this.$route.hash.slice(1);
			this.$nextTick(()=>{
				const item = document.getElementById(id);
				if (item){
					window.scrollTo(0,item.offsetTop);
					this.toggleAnswer(item)
				}
			})
		}
	},
	methods: {
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
		}
	},
	mounted() {
		this.getAllItems()
	}
}
</script>
<style lang="scss" scoped>
@import "~styleguide";
@mixin contentbox {
	border: 1px solid $color-border-gray;
	background-color: $color-base-gray-light;
	border-radius: 10px;
	padding: 25px 20px;
}
ul li{
	list-style: none;
}
.faq {
	// height: 100%;
	font-family:$font-family; 
}
.blade-questions {
	&__title {
		font-size: 45px;
		font-weight: 600;
		margin: 80px 0;
		@media(max-width: 425px) {
			font-size: 30px;
			margin: 45px 0;
		}
	}
	.answers {
		@include contentbox;
		margin-bottom: 74px;
		padding: 0px;
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
			line-height: 32px;
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
	.answers-helpcard {
		display: flex;
		margin-bottom: 75px;
		align-items: stretch;
		max-height: 671px;
		border-radius: 10px;
		overflow: hidden;
		@media(max-width: 930px) {
			max-height: 700px;
		}
		&__pic {
			width: 65%;
			overflow: hidden;
			position: relative;
			img {
				width: auto;
				height: 100%;
				position: absolute;
				top: 0;
				left: 0;
			}
			@media(max-width: 1110px) {
				width: 60%;
				img {
					transform: translateX(-3%);
				}
			}
			@media(max-width: 850px) {
				display: none;
			}
		}
		&__content {
			width: 35%;
			background-color: $color-base-accent;
			display: flex;
			flex-direction: column;
			justify-content: flex-start;
			@media(max-width: 1110px) {
				width: 40%;
			}
			@media(max-width: 850px) {
				width: 100%;
			}
		}
		.help-info {
			color: #fff;
			padding: 4% 1.2% 2%;
			@media(max-width: 768px) {
				padding: 4% 31px 2%;
			}
			@media(max-width: 425px) {
				padding: 4% 17px 2%;
			}
			&__title {
				font-size: 26px;
				line-height: 38px;
				font-weight: 500;
				text-align: center;
				margin-bottom: 40px;
				margin-bottom: 6%;
				@media(max-width: 850px) {
					margin-bottom: 4%;
				}
			}
			&__text {
				font-size: 18px;
				line-height: 32px;
				margin-bottom: 15px;
				@media(max-width: 850px) {
					text-align: center;
					margin-bottom: 5px;
				}
				@media(max-width: 768px) {
					font-size: 16px;
				}
				@media(max-width: 425px) {
					font-size: 14px;
				}
				&--center {
					text-align: center;
				}
			}
			&__cap {
				margin-top: 14%;
				@media(max-width: 850px) {
					margin-top: 3%;
				}
			}
			&__pills {
				display: flex;
				// justify-content: space-between;
				// justify-content: flex-start;
				justify-content: center;
				margin-top: 20px;
				flex-wrap: wrap;
				margin-top: 5%;

				@media(max-width: 1110px) {
					a {
						font-size: 14px;
						min-width: 75px;
						padding: 13px 10px;
					}
				}
				@media(max-width: 850px) {
					justify-content: center;
					margin-top: 2%;
					li:not(:last-child) {
						margin-right: 10px;
					}
				}
				li {
					flex-shrink: 0;
					margin: 3px 0;
				}
				li:not(:last-child) {
					margin-right: 10px;
				}
				a {
					min-width: 84px;
					padding: 13px;
					display: flex;
					justify-content: center;
					align-items: center;
					background-color: #fff;
					color: $color-base-accent;
					text-decoration: none;
					font-size: 18px;
					border-radius: 35px;
				}
			}
			&__politics {
				margin-top: auto;
				a {
					color: #fff;
					font-size: 12px;
					font-weight: 500;
					line-height: 24px;
				}
				@media(max-width: 850px) {
					display: flex;
					justify-content: center;
					li {
						margin: 3px 0;
					}
					li:not(:last-child) {
						margin-right: 10px;
					}
				}
				@media(max-width: 425px) {
					flex-direction: column;
					align-items: center;
					li {
						margin: 5px 0;
					}
					li:not(:last-child) {
						margin-right: 0px;
					}
				}
			}
		}
	}
	.advices {
		@include contentbox;
		padding: 60px;
		@media(max-width: 768px) {
			padding: 40px 31px;
		}
		@media(max-width: 425px) {
			padding: 40px 17px;
		}
		&__title {
			font-size: 26px;
			font-weight: 500;
			color: $color-base-accent;
			margin-bottom: 50px;
		}
		&__list {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			gap: 15px;
			@media(max-width: 850px) {
				grid-template-columns: 1fr 1fr;
			}
			@media(max-width: 500px) {
				grid-template-columns: 1fr;
			}
		}
		&__item {
			background: #fff;
			overflow: hidden;
			&-link {
				border-radius: 6px;
				overflow: hidden;
				height: 100%;
				display: flex;
				flex-direction: column;
				text-decoration: none;
			}
			&-pic {
				display: flex;
				img {
					width: 100%;
					height: auto;
					@media(max-width: 500px) {
						object-fit: contain;
					}
				}
			}
			&-text {
				background: #fff;
				padding: 11px;
				color: #000;
				border: 1px solid #ececec;
				border-top: none;
				border-bottom-left-radius: 6px;
				border-bottom-right-radius: 6px;
				font-size: 18px;
				line-height: 24px;
				// height: 100%;
				@media(max-width: 768px) {
					font-size: 16px;
				}
				@media(max-width: 425px) {
					font-size: 14px;
				}
			}
		}
	}
}

</style>