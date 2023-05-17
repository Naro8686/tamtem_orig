<template lang="pug">
section.bid-detail(v-if='data.id')
	.bids-search__intro.bids-intro
		.container
			.bids-intro__inner
				h1.bids-intro__title Биржа оптовых #[br] продаж tamtem.ru
				div.bids-intro__picture
					img(src="/images/bidlist/intro-pic.png")
		.container
			.bids-intro__filters
				filtersItem
	article.bid.bid--buy
		header.bid__header.bid-header
			.container
				.row
					.col.bid-header__box
						.bid-header__goback
							Goback(:cls="$route.name.replace('.','-')")
						.bid-header__title
							h1 Заказ:&nbsp; #[br] {{data.name}}
						.bid-header__budget
							| {{priceFormat(data.budget_from)}} - {{priceFormat(data.budget_to)}} &nbsp;₽ за {{data.unit_for_unit}}{{data.budget_with_nds==1 ? ', с НДС' : ''}}
						.bid-header__period
							| Период закупки: {{data.date_of_event}}
						.bid-header__responses(title="Откликов")
							person
							span {{data.responsesCount}}
						.bid-header__favorite.favorite
							Favorite(:bid='data')
						.bid-header__viewed(title="Просмотров")
							feather(type='eye')
							span {{data.count_views}}
						.bid-header__accept.accept(v-if="!isNotMy && hasMembers && !finished")
							a.accept__btn(@click="setWinner()") Принять предложение
						.bid-header__vendors.vendors(v-if="!isNotMy && isNotDesktop && !finished")
							ul.vendors__list
								li.vendors__item(v-for="member,i in data.members")
									a.vendors__btn(:class="{'vendors__btn--active' : activeMemberId==member.id}" @click="setActiveMember(member.id)") Поставщик №{{i+1}}
		section.bid__body.bid-body.bid-body--vendor(v-if="isNotMy == true")
			.bid-body__backing
				.container
					.row
						.col.bid-body__box.bid-body__box--vendor(:class="{'bid-body__box--contact' : bidState == 3 && isNotDesktop}")
							section.bid-body__content
								p.bid-body__instruction(v-if="bidState == 2") Выберите условия, которые вы готовы выполнить
								.bid-body__conditions.conditions
									ul.conditions__list
										li.conditions__item(
											v-for="answer in sortSequence" 
											:class="{'pointsblank__item' : (bidState==3 || bidState==4) }"
										)
											div.conditions__field(v-if="bidState == 2")
												input(
													type="checkbox" 
													v-model="data.questions[answer]" 
													:id="answer" 
													:name="answer"
												)
												label(:for="answer")
											div(
												v-else-if="bidState!=1" 
												:class="{'pointsblank__mark--not' : data.questions[answer]==0,'pointsblank__mark--yes' : data.questions[answer]==1}"
											).pointsblank__mark
											div.conditions__content(
												:style="{ width: data.answers[answer].name=='dqh_specification' ? '100%': '' }"
											)
												label(:for="answer").conditions__cap {{data.answers[answer].header}}
												p.conditions__text(v-if="data.answers[answer].name=='dqh_specification'")
													template
														div(v-html="data.answers[answer].value")
														a.uploaded-file__wrapper(v-if="data.files && data.files.length>0" :href="data.files[0].path")
															.uploaded-file__icon
																icon-file-load(variant="loaded")
															span.uploaded-file__name {{data.files[0].name}}
												p.conditions__text(v-else) {{data.answers[answer].value}} {{ data.answers[answer].name=='dqh_volume' ? data.unit_for_all : '' }}
										li.conditions__item(
											v-if="bidState!=1" 
											:class="{'pointsblank__item' : (bidState==3 || bidState==4) }"
										)
											div.pointsblank__mark.pointsblank__mark--yes(
												v-if="bidState==3 || bidState==4"
											)
											div.conditions__content.errorable(
												:class="{'conditions__content--nocheck' : bidState==2}"
											)
												p.conditions__cap Предложение
												div.conditions__offer.offer(:class="{'errors':errors.has('offer')}")
													label.offer__label
														input(type="text" :readonly="bidState>2"	data-vv-as='предложение' data-vv-name="offer" v-validate="'required|price'" v-model="data.price_offer" id="offer-budget" name="offer-budget").offer__input
														div.offer__icon(v-show="data.is_shipping_included == true")
															deliverycar
													span.errors(v-if="errors.has('offer')")
														|{{errors.first('offer')}}
													div.offer-delivery
														div.offer-delivery__field
															input(
																:disabled="bidState>2"
																type="checkbox" 
																id="delivery-oncost" 
																name="delivery-oncost"
																v-model.number="data.is_shipping_included"
																)
															label(for="delivery-oncost")
														label(for="delivery-oncost").offer-delivery__cap Доставка включена в стоимость
								div.bid-body__comment.comment(v-if='bidState!=1' :class="{'errors': errors.has('cmnt')}")
									template(v-if="bidState==2")
										template(v-if="disagreeAnswersLength>0")
											textarea.comment__field(
												v-validate="'required'" 
												data-vv-as="комментарий" 
												data-vv-name="cmnt" 
												v-model="data.notice" 
												placeholder="Если есть какие-то условия сделки, которые вы не можете выполнить, и у вас есть свои предложения, напишите об этом здесь."
											)
											span.errors.cmnt(v-if="errors.has('cmnt')") {{errors.first('cmnt')}}
										textarea.comment__field(
											v-else 
											v-model="data.notice" 
											placeholder="Если есть какие-то условия сделки, которые вы не можете выполнить, и у вас есть свои предложения, напишите об этом здесь."
										)
									template(v-else)
										div.comment__field {{data.notice}}
										div.bid-body__upload.filelist
											div.filelist-file__wrapper(v-for="item,i in data.files_my_response" :key="i")
												div.filelist-file__file(:style="addBackground(item.path)")
								div.bid-body__upload.upload(v-if="bidState >= 2 && bidState < 4")
									input(type="file" id="img-upload" name="photo" accept="image/*" multiple="true" @change="uploadFiles($event.target.files)")
									label(for="img-upload")
										i.upload__icon
											picupload
										span.upload__text Прикрепить фото товара (не больше 3)
								div.bid-body__upload.filelist
									div.filelist-file__wrapper(v-for="item,i in files" :key="i")
										div.filelist-file__file(:style="addImage(item)")
										button.filelist-file__remove-button(@click.prevent="removeElement(i)") X
							
							//- BIDCARD
							section.bid-body__card.bidcard(
								v-show="bidState != 3" 
								:class="{'bidcard--transparent' : bidState == 4 && isNotDesktop || !isAuth, 'bidcard--sticky' : bidState==2}"
							)
								div.bidcard__enter(v-if="!isAuth")
									p Оставьте заявку на выполнение заказа, даже если вы не можете выполнить некоторые пункты требований заказчика. #[br] Помните, что всегда можно договориться
									//-p Впервые на сайте?								
									div.bidcard-garant
										span.bidcard-garant__icon
											icon-information(fill="#2fc06e")
										span.bidcad-garant__txt Гарантия сделки
										p.bidcard-garant__bubble Мы гарантируем возврат денежных средств,  если сделка не состоялась. #[a(@click.prevent="sendToFAQ()" href="/faq#answer-failing-order") Подробнее]
									a(href="/signup" @click.prevent="registrationClick()").to-reg Зарегистрироваться
									span Есть аккаунт?
									a(href="/login" @click.prevent="signinClick()").to-enter Войти
									//-span Войдите, чтобы продолжить сотрудничество
								div.bidcard__box(
									v-if="isAuth" 
									:class="{'bidcard--greyed' : (bidState == 2 || bidState == 4), 'bidcard--spacecream' :(bidState==4 && data.trading_status=='waiting_payment' && data.winnerIdReal==myId)}"
								)
									template(v-if="bidState==1")
										img(src="/images/pic.png" v-if="!isNotDesktop").bidcard__pic
										button.bidcard__btn(@click="tryAddResponse()") Отклик
										p.bidcard__cap *Откликнуться можно, если  #[br] 
											router-link(:to="{name:'lk.company.create'}") создать компанию
									//- ВОТ ОН, ПЕРЕДЕЛЫВАЕМ ЕГО РЕБЯТ
									template(v-if="bidState==2")
										//- span.bidcard__box-name Цены
										a(href="/price").bidcard__box-name Цены
										div.average-box
											p.average-sum__title Средняя сумма заказа
											p.average-box__price {{priceFormat(data.budget_all)}} ₽
										button.bidcard__btn(id="floatingButton" :class="{'bidcard__btn--grey': disagreeAnswersLength==8}" @click="openConfirmModal") Отправить
										div.bidcard-garant
											span.bidcard-garant__icon
												icon-information(fill="#2fc06e")
											span.bidcad-garant__txt Гарантия сделки
											p.bidcard-garant__bubble Мы гарантируем возврат денежных средств,  если сделка не состоялась. #[a(@click.prevent="sendToFAQ()" href="/faq#answer-failing-order") Подробнее]
										div.bidcard-garant-text Мы гарантируем возврат денежных средств,  если сделка не состоялась. #[a(@click.prevent="sendToFAQ()" href="/faq#answer-failing-order") Подробнее]
									
									//- в четвертом состоянии мы можем отозвать отклик
									template(v-if="bidState==4")
										//- состояние правой карточки когда я ПОБЕДИТЕЕЕЕЕЕЕЛЬ!
										template(v-if="data.trading_status=='waiting_payment' && data.winnerIdReal==myId")
											a(href="/price").bidcard__box-name Цены
											p.bidcard__box-title Вы победитель
											div.bidcard__box-info 
												p Вас выбрали на роль поставщика
												p Только вы получаете #[br] контактные данные после оплаты.
											div.average-sum
												p.average-sum__title Средняя сумма заказа
												div.average-sum__field
													div.average-sum__inputbox
														p.average-sum__input {{data.budget_all}}
													div.average-sum__symbol ₽
											div.average-sum
												p.average-sum__title Условие оплаты #[br] контактных данных
												div.average-sum__field
													div.average-sum__inputbox
														p.average-sum__input {{data.commission}}
													div.average-sum__symbol ₽
											div.bidcard-garant
												span.bidcard-garant__icon
													icon-information(fill="#fff")
												span.bidcard-garant__txt Гарантия сделки
												p.bidcard-garant__bubble Мы гарантируем возврат денежных средств,  если сделка не состоялась. #[a(@click.prevent="sendToFAQ()" href="/faq#answer-failing-order") Подробнее]
											div.bidcard-garant-text Мы гарантируем возврат денежных средств,  если сделка не состоялась. #[a(@click.prevent="sendToFAQ()" href="/faq#answer-failing-order") Подробнее]		
											div.bidcard-garant--extra
												span.bidcard-garant--extra__icon 
													icon-cardpayment
												span.bidcard-garant--extra__txt Безналичная оплата
											div.bidcard-garant--extra
												span.bidcard-garant--extra__icon 
													icon-dogovor
												a(target="__blank" href="/files/agreement.pdf").bidcard-garant--extra__txt--underline Договор оферты
											button(@click="getPay()").bidcard__btn Оплатить контакты
										template(v-else)
											a(href="/price").bidcard__box-name Цены
											div.average-sum
												p.average-sum__title Средняя сумма заказа
												div.average-sum__field
													div.average-sum__inputbox
														p.average-sum__input {{data.budget_all}}
													div.average-sum__symbol ₽
											div.average-sum
												p.average-sum__title Условие оплаты #[br] контактных данных
												div.average-sum__field
													div.average-sum__inputbox
														p.average-sum__input {{data.commission}}
													div.average-sum__symbol ₽
											p.bidcard__desc Поставщик оплачивает контактные данные только в том случае, если закупщик выбрал поставщика и его коммерческое предложение
											template(v-if="data")
												button(v-b-modal="'modalRetreat'").bidcard__btn.btn-retreat Отменить предложение
											div.bidcard-garant
												span.bidcard-garant__icon
													icon-information(fill="#2fc06e")
												span.bidcad-garant__txt Гарантия сделки
												p.bidcard-garant__bubble Мы гарантируем возврат денежных средств,  если сделка не состоялась. #[a(@click.prevent="sendToFAQ()" href="/faq#answer-failing-order") Подробнее]
											div.bidcard-garant-text Мы гарантируем возврат денежных средств,  если сделка не состоялась. #[a(@click.prevent="sendToFAQ()" href="/faq#answer-failing-order") Подробнее]
							usercontacts(
								:canCreateDialog="true" 
								:userdata="data.userdata" 
								v-if="bidState==3"
							)
		section.bid__body.bid-body.bid-body--owner(v-if="!isNotMy")
			.bid-body__backing
				.container
					.row
						.col.bid-body__box.bid-body__box--owner
							p.bid-body__instruction(v-if="isNotDesktop && finished") Вы выбрали поставщика
							p.bid-body__instruction(v-if="isNotDesktop && hasMembers && !finished") Выберите только одного поставщика
							p.bid-body__instruction(v-if="isNotDesktop && !hasMembers") На данный момент нет ни одного отклика
							//- section.bid-body__distance.distance(v-if="isNotDesktop")
								div.distance__box-graph
									span.distance__value 100 км
									feather(type='map-pin').distance__pin.distance__pin--left
									feather(type='map-pin').distance__pin.distance__pin--right
								div.distance__box-labels
									span г. Брянск
									span Брянская обл.
							bid-choose-panel-mobile(
								v-if="isNotDesktop" 
								:winner="winner" 
								:files="data.files"
								:hasMembers="hasMembers" 
								:activeMember="activeMember" 
								:answers="data.answers"
								:unit_for_all="data.unit_for_all")
							bid-choose-panel.bid-body__choosepanel(
								v-if="!isNotDesktop" 
								:unit_for_all="data.unit_for_all" 
								:activeMemberId="activeMemberId" 
								:members="data.members" 
								:finished="finished" 
								:winner_id="data.winner_id" 
								:questions="data.answers"
								:files="data.files" 
								@setActive="setActiveMember")
		footer.bid__footer.bid-footer
			.container
				.row
					.col.bid-footer__box
						.bid-footer__copy.bid-copylink
							.divider
							a(href="javascript:void(0);" @click="linkCopy()").bid-copylink__link
								span.bid-copylink__icon
									icon-link
								span.bid-copylink__txt Копировать ссылку на заказ
						.bid-footer__tags.tags
							p.bid-category {{data.categories[0].name}}
						.bid-footer__row
							//- .bid-footer__date(v-if="data.date_published") Добавлена {{data.date_published.date|dateTransform}}
							.bid-footer__favorite.favorite
								Favorite(:bid='data')
						.bid-footer__expiration(v-if="data.date_published") Добавлена {{data.date_published.date|dateTransform}}
						//- .bid-footer__expiration(v-if="data.deadline_deal") Срок сбора предложения до {{data.deadline_deal|dateTransform}}
		section.bid__map.bid-map
			.container
				.row
					.col
						.bid-map__control
							p(
								href='javascript:void(0)'
								class='bid-map__show'
							)
								feather(type='map-pin')
								b {{data.cities[0].region_name}}
		div(v-if="isLinkCopied").copy-link-message.container
			p.copy-link-message__pic
				icon-tick(color="#ffffff")
			p.copy-link-message__text Ссылка скопирована.
			button(@click="isLinkCopied = false").copy-link-message__close Закрыть
	b-modal(
		ref="modalRetreat"
		id="modalRetreat"
		modal-class="custom-modal"
	)
		retreat(:responseId="bidData.responseId" @close="$refs.modalRetreat.hide();" @leave="$emit('leaveDeal')")
		div(slot='modal-footer')
	b-modal(
		ref="modalImage"
		id="modalImage"
		hide-footer
		hide-header
	)
		img(:src="viewingImage")
	b-modal(
		ref="modalConfirm"
		id="modalConfirm"
		modal-class="custom-modal"
		body-class="wider-body"
	)
		modal-confirm(@closeConfirm="closeConfirmModal" :comission="data.commission" @sendRequest="prepareDatatoSend" :username="profile.profile.name")
		div(slot='modal-footer')


</template>

<script>
import { Validator } from "vee-validate";
import viewedbids from "../../../mixins/viewedbids.mixin";
import Goback from "../../GeneralComponents/components/Goback";
import Retreat from "../../GeneralComponents/components/retreatBid";
import Favorite from "../../GeneralComponents/components/Favorite";
import BidChoosePanel from "../components/BidChoosePanel";
import BidChoosePanelMobile from "../components/bidChoosePanelMobile";
import usercontacts from "../components/usercontacts";
import modalConfirm from "../components/modalConfirm"
import iconFileLoad from "../../Icons/iconFileLoad";
import iconLink from "../../Icons/iconLink";
import iconTick from "../../Icons/iconTick";
import iconInformation from "../../Icons/iconInformation";
import iconCardpayment	 from "../../Icons/iconCardpayment";
import iconDogovor	 from "../../Icons/iconDogovor";
import filtersItem from "../../GeneralComponents/components/filtersitem";
import breadcrumbs from "../../GeneralComponents/components/Breadcrumbs";
import { mapActions, mapState } from "vuex";

export default {
	mixins: [viewedbids],
	components: {
		Goback,
		iconFileLoad,
		iconLink,
		iconTick,
		iconInformation,
		iconCardpayment,
		iconDogovor,
		Favorite,
		BidChoosePanel,
		BidChoosePanelMobile,
		usercontacts,
		Retreat,
		modalConfirm,
		filtersItem,
		breadcrumbs
	},
	props: {
		bidData: {
			type: Object,
			required: true
		}
	},
	filters: {
		dateTransform(rawData) {
			const data = rawData.split(" ")[0];
			if (data) {
				const [year, month, day] = data.split("-");
				return `${day}.${month}.${year}`;
			} else {
				const [year, month, day] = rawData.split("-");
				return `${day}.${month}.${year}`;
			}
		}
	},
	data() {
		return {
			viewDetailImage: false,
			viewingImage: null,
			maxUploadedFilesCount: 3,
			files:[],
			paymentSlug: "payment_once_deal_buy_get_contacts",
			data: {},
			windowWidth: window.innerWidth,
			map: null,
			service: {},
			startingBidState: 2,
			activeMemberId: null,
			activeMember: null,
			isLinkCopied: false,
			// length of all items with checkboxes
			checkboxesArrayLength: 8,
			sortSequence: [
				"dqh_specification",
				"dqh_type_deal",
				"dqh_volume",
				"dqh_min_party",
				"dqh_logistics",
				"dqh_doc_confirm_quality",
				"dqh_payment_method",
				"dqh_payment_term"
			]
		};
	},
	created() {
		Validator.extend("price", {
			getMessage: field =>
				`Поле ${field} должно быть числом и может содержать 2 знака после запятой`,
			validate: value => {
				return /^\d*[,.]?\d{0,2}$/.test(value);
			}
		});
		Object.filter = (obj, predicate) =>
			Object.keys(obj)
				.filter(key => predicate(obj[key]))
				.reduce((res, key) => ((res[key] = obj[key]), res), {});
	},
	mounted() {
		this.data = this.bidData;
		this.activeMemberId =
			this.data.winner_id ||
			(this.bidData.members.length > 0 ? this.bidData.members[0].id : null);
		this.activeMember =
			this.data.members.filter(item => {
				return item.id === this.activeMemberId;
			})[0] || null;

		window.addEventListener("resize", () => {
			this.getwindowWidth();
		});
		this.setNoticifations();
	},
	computed: {
		...mapState(['profile']),
		myId(){
			return this.profile ? this.profile.company.id : null
		},
		disagreeAnswersLength() {
			return Object.keys(
				Object.filter(this.data.questions, item => item !== true)
			).length;
		},
		winner() {
			return this.data.members.filter(item => {
				return item.id == this.data.winner_id;
			});
		},
		finished() {
			return !!this.data.winner_id;
		},
		hasMembers() {
			return this.data.members.length > 0;
		},
		hasCompany() {
			return this.$root.profile && !!this.$root.profile.company;
		},
		answerBefore() {
			return this.data.isResponsedBefore;
		},
		bidState() {
			// 4 - откликнулись, ждём
			// 3 - выиграли
			// 2 - откликаемся (появляются чекбоксы и кнопка отправить с ценой)
			// 1 - не авторизованы, нет компании, только открыли (нет чекбоксов, есть кнопка откликнуться)
			let status = 1;
			if (this.startingBidState == 2 && this.hasCompany) {
				status = 2;
				// return status;
			}
			if (
				(this.hasCompany && this.answerBefore) ||
				this.startingBidState == 4
			) {
				status = 4;
				// return status;
			}
			if (this.data.owner) {
				status = 3;
				// return status;
			}
			return status;
		},
		isAuth() {
			return this.$root.isAuth;
		},
		isNotDesktop() {
			return this.windowWidth <= 768;
		},
		isNotMy() {
			return (
				!this.data.owner ||
				(this.$root.profile &&
					this.$root.profile.profile.id !== this.data.owner.id)
			);
		},
		isContactVisible() {
			return !!this.data.owner || !!this.data.organization;
		}
	},
	methods: {
		sendToFAQ(){		
		this.yandexReachGoal("garantiya_sdelki_na_zakaze");
		this.googleReachGoal("garantiya_sdelki_na_zakaze");
		window.location=`/faq#answer-failing-order`;
		},
		addBackground(file){
			return `background: url(${file})`
		},
		removeElement(i){
			this.files.splice(i,1);
		},
		showIt(image){
			this.viewingImage = URL.createObjectURL(image);
			this.$refs.modalImage.show();
		},
		addImage(file){		
			return `background: url(${URL.createObjectURL(file)})`
		},
		uploadFiles(fileList){
			// console.log(fileList)
			this.files.splice(0,this.files.length);

			const maxCount = Math.min(fileList.length,this.maxUploadedFilesCount);
			
			for (let i=0; i<maxCount;i++){
				this.files.push(fileList[i]);
			}	
		},
		getPay() {
			const pay = this.paymentSlug;
			const notificationsData = {
				id: this.data.responseId,
				command: "is_readed_owner_response"
			}
			const data = { deal_id: this.data.id };
			// console.log(data);
			let url = `/api/v1/paymentservice/subscriptions/${pay}/payment`;
			axios
				.post(url, data)
				.then(resp => {
					if (resp.data.result) {
						this.$store.dispatch("setSnackbar", {
							color: "success",
							text: "Успешно оплачено",
							toggle: true
						});

						this.yandexReachGoal("pay_button_on_zakaz_if_balance_norm");
						this.googleReachGoal("pay_button_on_zakaz_if_balance_norm");

						window.Api.loadProfile();
							this.updateNotifications(notificationsData);
				// релоад
				this.$emit("reloadDeal");
					} else {
				// в кошелёк
						 let props = {
							summ: this.data.commission,
							slug: this.paymentSlug,
							dealId: this.data.id
							};
						this.$router.push({ name: "lk.wallet", params: { response: props } });
					}
				})
				.catch(error => {
					this.printErrorOnConsole(error);
					this.$store.dispatch("setSnackbar", {
						color: "error",
						text: "Произошла ошибка, попробуйте позднее",
						toggle: true
					});
				});
		},
		signinClick() {
			this.yandexReachGoal("vhod_glavnaya");
			this.googleReachGoal("vhod_glavnaya");
			this.$root.$emit("showForm", "authorizationForm");
		},
		registrationClick() {
			this.$root.$emit("showForm", "registrationForm");
		},
		linkCopy() {
			let url  = `${location.origin}${location.pathname}`;
			this.copyToClipboard(url);
			this.isLinkCopied = true;
			setTimeout(() => {
				this.isLinkCopied = false;
			}, 2500);
		},
		...mapActions(["updateNotifications"]),
		setName(val) {
			return `val_${val}`;
		},
		setNoticifations() {
			for (let i = 0; i < this.data.members.length; i++) {
				if (this.data.members[i].is_readed_owner_deal == 0) {
					const payload = {
						id: this.data.members[i].id,
						command: "is_readed_owner_deal"
					};
					this.updateNotifications(payload);
				}
			}
		},
		setWinner() {
			const winnerId = this.activeMember.id;
			axios
				.post(`/api/v1/deals/${this.data.id}/winner`, { winner_id: winnerId })
				.then(resp => {
					if (resp.data.result) {
						this.$store.dispatch("setSnackbar", {
							color: "success",
							text: `Вы успешно выбрали поставщика`,
							toggle: true
						});

						this.$emit("reloadDeal");
					} else {
						this.$store.dispatch("setSnackbar", {
							color: "error",
							text: `${resp.data.error}`,
							toggle: true
						});
					}
				})
				.catch(err => {
					this.$store.dispatch("setSnackbar", {
						color: "error",
						text: `Произошла ошибка, попробуйте позднее`,
						toggle: true
					});
				})
				.then(() => {});
		},
		setActiveMember(id) {
			this.activeMemberId = id;
			this.activeMember = this.data.members.filter(item => {
				return item.id === id;
			})[0];
		},
		tryAddResponse() {
			if (this.hasCompany) {
				this.startingBidState = 2;
			} else {
				this.$store.dispatch("setSnackbar", {
					color: "error",
					text: `Чтобы откликнуться нужна компания`,
					toggle: true
				});
			}
		},
		openConfirmModal() {
			// полагаю, валидацию надо как-то перераспределить между старым методом validateBeforeSubmit и этим. Но это не точно.
			this.validateBeforeSubmit();
		},
		closeConfirmModal() {
			// вероятно, при закрытии модалки надо делать что-то еще.
			this.$refs['modalConfirm'].hide();
		},
		validateBeforeSubmit() {
			if (this.disagreeAnswersLength==this.checkboxesArrayLength){
				this.yandexReachGoal('send_otlik_null_chekbox');
				this.googleReachGoal('send_otlik_null_chekbox');
			}
			if (this.disagreeAnswersLength > 4) {
				this.$store.dispatch("setSnackbar", {
					color: "error",
					text:
						"Для отправки предложения необходимо выполнить минимум 4 условия сделки",
					toggle: true
				});
			}else{
				this.$validator.validateAll().then(result => {
					if (result) {
						this.err = {};
						// вызвать сборку

						this.yandexReachGoal('send_button_if_modal_otklic_open');
						this.googleReachGoal('send_button_if_modal_otklic_open');

						this.$refs['modalConfirm'].show();
					} else {
						let errorFieldNameToScroll = this.errors.items[0].field;
						let containerWithError = document
							.querySelector(`[data-vv-name='${errorFieldNameToScroll}']`)
							.closest(".errorable");
						containerWithError
							? containerWithError.scrollIntoView()
							: document
									.querySelector(`[data-vv-name='${errorFieldNameToScroll}']`)
									.scrollIntoView();
					}
				});
			}    
		},
		prepareDatatoSend() {
			this.closeConfirmModal();
			let data = new FormData();
			let arr = {
				price_offer: this.data.price_offer,
				notice: this.data.notice,
				is_shipping_included: this.data.is_shipping_included,
				questions: this.data.questions,
				images: this.files
			};
			for (const key in arr) {
				switch (key) {
					case "questions":
						for (const item in arr[key]) {
							let arraykey = `questions[${item}]`;
							data.set(arraykey, Number(arr[key][item]));
						}
						break;
					case "images":
						for (const item in arr[key]){
							let arraykey = `images[${item}]`;
							data.set(arraykey, arr[key][item]);
						}
						break; 	
					case "price_offer":
						data.set(key, arr[key].replace(",", "."));
						break;
					case "is_shipping_included":
						data.set(key, Number(arr[key]));
						break;
					default:
						data.set(key, arr[key]);
						break;
				}
			}
			// let fd = data
			// for(let key of fd.keys()) {
			// 	console.log(`${key}: ${fd.getAll(key)}`);
			// }
			 this.sendData(data);
		},
		sendData(data) {
			this.$store.dispatch("setLoading", true);
			const url = `/api/v1/deals/${this.data.id}/take`;
			
			axios
				.post(url, data, {
					headers: { "Content-Type": "multipart/form-data" }
				})
				.then(response => {
					if (response.data.result) {
						this.$store.dispatch("setSnackbar", {
							color: "success",
							text: "Вы успешно откликнулись",
							toggle: true
						});

						this.yandexReachGoal("send_button_in_modal_window_if_succses");
						this.googleReachGoal("send_button_in_modal_window_if_succses");

						this.startingBidState = 4;

						this.$emit("updateResponseId", response.data.data.response_id);
					} else {
						this.$store.dispatch("setSnackbar", {
							color: "error",
							text: `${response.data.error}`,
							toggle: true
						});
					}
				})
				.catch(err => {
					this.$store.dispatch("setSnackbar", {
						color: "error",
						text: `Произошла ошибка, попробуйте позднее`,
						toggle: true
					});
					this.printErrorOnConsole(err);
				})
				.then(() => {
					this.$store.dispatch("setLoading", false);
				});
		},
		getwindowWidth() {
			this.windowWidth = window.innerWidth;
		},
		getpaymentInfo() {
			let url = "/api/v1/paymentservice/payment_once_deal_buy_get_contacts";
			axios
				.get(url)
				.then(resp => {
					if (resp.data.result) {
						this.service = resp.data.data;
					}
				})
				.catch(error => {
					this.printErrorOnConsole(error);
				});
		},
		responseComplete() {
			this.getDeal();
		}
	},
	watch: {
		"$route.query"() {
			this.data = {};
			this.getDeal();
		},
		isAuth: {
			handler(newVal) {
				if (!!newVal) {
					this.$emit("reloadDeal");
				}
			},
			deep: true
		}
	}
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
.filelist{
	display: flex;
	&-file{	
		&__wrapper{
			position: relative;
			width: 92px;
			height: 92px;
			border: 1px solid $color-base-accent;
			border-radius: $border-radius-small;
			margin: 0px 18px;
			&:first-child{
				margin-left: 0;
			}
			&:last-child{
				margin-right: 0;
			}
		}
		&__file{
			width: 100%;
			height: 100%;
			background-position: center !important;
			background-repeat: no-repeat !important;
			background-size: 100% !important;
		}
		&__remove-button{
			position: absolute;
			right: -10px;
			top: -10px;
			background:#fff;
			font-weight: 600;
			color:	$color-text-gray;
			box-shadow: $box-shadow-standart;
			padding: 0 6px;
			border-radius: 50%;
		}
	}
}
.uploaded-file {
	&__wrapper {
		display: flex;
		align-items: center;
		padding: 15px 0;
	}
	&__icon {
		margin-right: 20px;
	}
	&__name {
		font: $font-regular;
		font-size: $fontsize-smaller;
		color: #000;
	}
	&__size {
		font: $font-regular;
		font-size: $fontsize-smaller;
		color: $color-text-gray;
	}
}

.errors {
	display: block;
	margin-top: -1rem;
	font-size: 12px;
	color: $danger;
	margin-bottom: 0.2rem;
	&.cmnt {
		margin-top: 0;
	}
	input,
	textarea {
		border-color: #ff564b !important;
	}
}

.bid-detail {
	position: relative;
	font-family: $font-family;
	font-size: 14px;
	font-weight: 400;

	& /deep/ i.feather {
		vertical-align: middle;
		width: 2.5rem;
		height: 2.5rem;
		&--map-pin {
			width: 1.6rem;
			height: 1.6rem;
		}
	}
	.map#map {
		height: 25rem;
	}
}
.bid {
	&__body {
		margin-top: 30px;
		@media (max-width: 768px) {
			margin: 0;
		}
	}
	&__footer {
		margin-top: 20px;
	}
}
.favorite {
	display: flex;
	align-items: center;
	& /deep/ .btn-favorite {
		color: $color-base-light;
		&.active {
			svg {
				fill: $color-base-light;
			}
		}
	}
}
.bid-header {
	background-color: $color-base-accent;
	color: $color-base-light;
	padding-top: 38px;
	padding-bottom: 30px;
	&__box {
		display: grid;
		grid-template-columns: 46px 1fr 1fr 1fr auto auto;
		grid-column-gap: 10px;
		grid-row-gap: 10px;
		grid-template-areas:
			"goback title title responses eye star"
			". budget budget accept accept accept"
			". period period accept accept accept";
		@media (max-width: 768px) {
			grid-template-columns: 46px 1fr 1fr 1fr auto auto auto;
			grid-template-areas:
				"goback title title title . responses eye"
				". budget budget budget accept accept accept"
				". period period period accept accept accept"
				". vendors vendors vendors vendors vendors vendors";
		}
		@media (max-width: 420px) {
			grid-row-gap: 0;
			grid-template-columns: repeat(6, 1fr);
			grid-template-rows: 25px auto;
			grid-template-areas:
				"goback . . . eye responses"
				"title title title title title title"
				"budget budget budget budget budget budget"
				"period period period period period period"
				"accept accept accept accept accept accept"
				"vendors vendors vendors vendors vendors vendors";
		}
	}
	&__goback {
		position: relative;
		grid-area: goback;
		& /deep/ .goback {
			@include media-breakpoint-down(xxl) {
				position: relative;
				left: 0;
				float: left;
				margin-right: 1.5rem;
			}
			opacity: 1;
			a {
				border: 2px solid $color-base-light;
				color: $color-base-light;
				height: 30px;
				width: 36px;
				:hover,
				:focus {
					color: $color-base-light;
				}
				@media (max-width: 420px) {
					height: 25px;
					width: 31px;
				}
			}
		}
		&::after {
			content: "";
			display: block;
			clear: both;
		}
	}
	&__title {
		grid-area: title;
		h1 {
			margin: 0;
			font: $font-medium;
			font-size: 32px;
			line-height: 27px;
			// word-break: break-all;
			-moz-hyphens: auto;
			-webkit-hyphens: auto;
			-ms-hyphens: auto;
			hyphens: auto;
			@media (max-width: 768px) {
				font-size: 26px;
			}
			@media (max-width: 420px) {
				// font-size: 20px;
				font-size: 18px;
				text-align: center;
				margin: 5px 0;
			}
		}
		br {
			@media (min-width: 421px) {
				display: none;
			}
		}
	}
	&__budget {
		grid-area: budget;
		font-size: 17px;
		font-weight: 600;
		@media (max-width: 420px) {
			justify-self: center;
			font-size: 16px;
			margin-top: 15px;
		}
	}
	&__period {
		grid-area: period;
		font-size: 14px;
		font-weight: 500;
		@media (max-width: 420px) {
			justify-self: center;
		}
	}
	&__responses {
		grid-area: responses;
		justify-self: end;
		display: flex;
		align-items: center;
		padding-left: 12px;
		span {
			margin-left: 7px;
		}
		@media (max-width: 420px) {
			align-self: center;
			justify-self: end;
			font-size: 12px;
			& /deep/ svg {
				width: 19px;
				height: 17px;
			}
		}
	}
	&__viewed {
		grid-area: eye;
		justify-self: end;
		display: flex;
		align-items: center;
		padding-left: 12px;
		span {
			margin-left: 7px;
		}
		@media (max-width: 420px) {
			align-self: center;
			justify-self: end;
			font-size: 12px;
			& /deep/ i.feather {
				width: 26px;
				height: 21px;
			}
		}
	}
	&__favorite {
		grid-area: star;
		padding-left: 12px;
		@media (max-width: 768px) {
			display: none;
		}
	}
	&__accept {
		grid-area: accept;
		align-self: center;
		justify-self: end;
		@media (max-width: 420px) {
			justify-self: center;
			margin-top: 20px;
		}
	}
	&__vendors {
		grid-area: vendors;
		margin-top: 25px;
	}
	.accept {
		&__btn {
			display: flex;
			justify-content: center;
			align-items: center;
			max-width: 226px;
			padding: 11px 20px;
			font: $font-semibold;
			font-size: 14px;
			color: $color-base-accent;
			background-color: $color-base-light;
			border-radius: 32px;
			@media (max-width: 420px) {
				font-size: 12px;
			}
			&:hover {
				color: $color-pale-green;
			}
		}
	}
	.vendors {
		&__list {
			display: flex;
			list-style: none;
			flex-wrap: wrap;
			overflow-x: scroll;
			scrollbar-width: none;
			@media (max-width: 420px) {
				flex-wrap: nowrap;
			}
		}
		.vendors__list::-webkit-scrollbar {
			height: 0px;
		}
		&__item {
			margin-right: 10px;
			margin-bottom: 10px;
			@media (max-width: 420px) {
				margin-right: 3px;
				flex-shrink: 0;
			}
		}
		&__btn {
			width: 96px;
			display: flex;
			align-items: center;
			justify-content: center;
			color: $color-base-light;
			font-size: 10px;
			border-radius: 15px;
			border: 1px solid $color-base-light;
			padding: 4px 0;

			&:hover {
				color: $color-base-light;
			}
			&--active {
				background-color: $color-base-light;
				color: $color-base-dark;
				border-color: $color-base-light;
				&:hover {
					color: $color-base-accent;
				}
			}
		}
	}
}
.bid-body {
	@media (max-width: 768px) {
		background-color: $color-base-accent;
	}
	&__backing {
		@media (max-width: 768px) {
			background-color: $color-base-light;
			border-radius: 32px;
			padding: 20px 0 40px;
		}
	}
	&__box {
		display: grid;
		&--vendor {
			grid-template-columns: repeat(6, 1fr);
			grid-template-areas: "conts conts conts . card card";
			@media (max-width: 768px) {
				grid-template-areas:
					"conts conts conts conts conts conts"
					"card card card card card card";
			}
		}
		&--contact {
			grid-template-areas:
				"contact contact contact contact contact contact"
				"conts conts conts conts conts conts";
		}
		&--owner {
			grid-template-columns: repeat(6, 1fr);
			.bid-body__instruction {
				grid-column: 1 / 7;
				@media (max-width: 768px) {
					text-align: left;
					margin-left: 20px;
				}
				@media (max-width: 420px) {
					text-align: center;
					margin: 0;
					margin-bottom: 25px;
				}
			}
		}
	}
	&__content {
		grid-area: conts;
		width: 100%;
	}
	&__instruction {
		display: block;
		font-size: 18px;
		font-weight: 500;
		color: #000000;
		margin-left: 60px;
		margin-bottom: 25px;
		@media (max-width: 768px) {
			text-align: center;
			margin: 0;
			margin-bottom: 25px;
		}
	}
	&__comment {
		margin-top: 20px;
	}
	&__upload {
		margin-top: 20px;
	}
	&__card {
		width: 309px;
		grid-area: card;
		justify-self: end;
		align-self: start;
	}
	/deep/ &__contact {
		grid-area: card;
		justify-self: end;
		align-self: start;
		width: 309px;
		display: flex;
		flex-direction: column;
		align-items: center;
		@media (max-width: 768px) {
			margin-bottom: 70px;
			grid-area: contact;
			width: 100%;
			display: grid;
		}
	}
	&__distance {
		max-width: 100%;
		grid-column: 1 / 5;
		margin-left: 20px;
		@media (max-width: 420px) {
			grid-column: 1 / 7;
			margin: 0 20px;
		}
	}
	&__pointsblank {
		grid-column: 1 / 7;
		margin-top: 15px;
	}
	&__choosepanel {
		grid-column: 1 / 7;
	}
	.conditions {
		&__list {
			list-style: none;
		}
		&__item {
			display: flex;
			&:not(:last-child) {
				margin-bottom: 28px;
			}
			@media (max-width: 768px) {
				padding: 10px 20px 15px;
				border: 1px solid #ececec;
				border-radius: 10px;
				box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
			}
			@media (max-width: 420px) {
				padding: 10px 10px 15px 20px;
			}
		}
		&__field {
			margin-right: 42px;
			padding-top: 2px;
			@media (max-width: 768px) {
				margin-right: 20px;
			}
			@media (max-width: 420px) {
				margin-right: 10px;
			}
			label {
				position: relative;
				width: 18px;
				height: 18px;
			}
			label::before {
				content: "";
				position: absolute;
				top: 3px;
				left: 0;
				width: 18px;
				height: 18px;
				border-radius: 2px;
				border: 1px solid $color-checkbox-border;
				cursor: pointer;
			}
			input:checked + label::before {
				content: "";
				background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4LjgiIGhlaWdodD0iNi41NTgiIHZpZXdCb3g9IjAgMCA4LjggNi41NTgiPiAgICA8ZGVmcz4gICAgICAgIDxzdHlsZT4gICAgICAgICAgICAuY2xzLTF7ZmlsbDpub25lO3N0cm9rZTojZmZmO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoxMDtzdHJva2Utd2lkdGg6MnB4fSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iY2hlY2stc3F1YXJlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTS0xLjUyNSAyLjM3OGwyLjA1MyAyLjE4IDIuNzMxLTIuNjIyTDUuMjc1IDAiIGNsYXNzPSJjbHMtMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS41MjUpIi8+ICAgIDwvZz48L3N2Zz4=),
					$color-base-accent;
				background-repeat: no-repeat, no-repeat;
				background-size: 54%, contain;
				background-position: center;
				border-color: $color-base-accent;
			}
			input {
				display: none;
			}
			&--not {
				label::before {
					content: "—";
					border-color: $color-base-attention;
					background-color: $color-base-attention;
					display: flex;
					justify-content: center;
					align-items: center;
					color: $color-base-light;
					font-size: 10px;
					font-weight: 700;
				}
			}
		}
		&__content {
			color: #000000;
			&--nocheck {
				margin-left: 55px;
				@media (max-width: 768px) {
					margin-left: 38px;
				}
				@media (max-width: 420px) {
					margin-left: 23px;
				}
			}
			@media (max-width: 420px) {
				font-size: 12px;
			}
		}
		&__cap {
			font-weight: 500;
			font-size: 16px;
			margin-bottom: 2px;
			cursor: pointer;
		}
		&__text {
			font-size: 14px;
		}
		&__offer {
			margin-top: 12px;
		}
	}
	.offer {
		&__label {
			position: relative;
			&::after {
				content: " ₽";
				color: #000000;
				font-size: 17px;
				margin-left: 5px;
			}
		}
		&__icon {
			position: absolute;
			top: 7px;
			right: 30px;
			@media (min-width: 450px) {
				right: 45px;
			}
		}
		&__input {
			border: 1px solid $color-border-gray;
			border-radius: $border-radius-small;
			height: 36px;
			padding: 0 10px;
			padding-right: 40px;
			width: 86%;
			margin-bottom: 10px;
			color: #000000;
			@media (max-width: 420px) {
				width: 200px;
			}
		}
		&-delivery {
			display: flex;
			&__field {
				margin-right: 15px;
				label {
					position: relative;
					width: 15px;
					height: 15px;
				}
				label::before {
					content: "";
					position: absolute;
					top: 4px;
					left: 0;
					width: 15px;
					height: 15px;
					border-radius: 50%;
					border: 1px solid $color-base-accent;
					cursor: pointer;
				}
				input:checked + label::before {
					content: "";
					background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4LjgiIGhlaWdodD0iNi41NTgiIHZpZXdCb3g9IjAgMCA4LjggNi41NTgiPiAgICA8ZGVmcz4gICAgICAgIDxzdHlsZT4gICAgICAgICAgICAuY2xzLTF7ZmlsbDpub25lO3N0cm9rZTojZmZmO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoxMDtzdHJva2Utd2lkdGg6MnB4fSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iY2hlY2stc3F1YXJlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTS0xLjUyNSAyLjM3OGwyLjA1MyAyLjE4IDIuNzMxLTIuNjIyTDUuMjc1IDAiIGNsYXNzPSJjbHMtMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS41MjUpIi8+ICAgIDwvZz48L3N2Zz4=),
						$color-base-accent;
					background-repeat: no-repeat, no-repeat;
					background-size: 54%, contain;
					background-position: center;
				}
				input {
					display: none;
				}
			}
			&__cap {
				font-size: 12px;
				color: #000000;
				display: flex;
				align-items: center;
				margin: 0;
				cursor: pointer;
			}
		}
	}
	.comment {
		&__field {
			word-wrap: break-word;
			border: 1px solid $color-border-gray;
			width: 100%;
			min-height: 126px;
			padding: 10px 10px 20px 60px;
			border-radius: $border-radius-small;
			color: $color-text-gray;
			resize: none;
			@media (max-width: 420px) {
				padding: 10px 10px 20px 42px;
				min-height: 62px;
				font-size: 11px;
			}
		}
	}
	.upload {
		input {
			display: none;
		}
		label {
			cursor: pointer;
		}
		&__text {
			@media (max-width: 420px) {
				display: none;
			}
		}
		&__icon {
			border: 2px dotted $color-border-gray;
			border-radius: 50%;
			padding: 9px;
			margin-right: 20px;
			position: relative;
			font-style: normal;
			&::before {
				content: "+";
				font-size: 20px;
				position: absolute;
				right: 0;
				bottom: -7px;
				color: $color-base-accent;
			}
		}
		@media (max-width: 420px) {
			text-align: center;
		}
	}
	.bidcard {
		display: flex;
		flex-direction: column;
		align-items: center;
		@media (max-width: 768px) {
			width: 100%;
			justify-self: center;
			margin-bottom: 100px;
			margin-top: 20px;
		}
		&__enter {
			text-align: center;
			color: #000;
			background-color: #fff8e6;
			border: 1px solid $color-border-gray;
			border-radius: 10px;
			padding: 38px 20px 56px;
			display: flex;
			flex-direction: column;
			align-items: center;
			p {
				text-align: center;
				margin-bottom: 35px;
			}
			.to-reg {
				@include flex-centerizer;
				@include w-h(198px, 43px);
				background: $color-pale-green;
				color: $color-base-accent;
				border-radius: 32px;
				margin-bottom: 40px;
				text-decoration: none;
				font-weight: 500;
			}
			span {
				display: inline-block;
				color: $color-text-gray;
				text-align: center;
				margin-bottom: 4px;
			}
			.to-enter{
				color: $color-base-accent;
				text-decoration: none;
				font-weight: 500;
			}
		}
		&__box {
			position: relative;
			display: flex;
			flex-direction: column;
			align-items: center;
			@media (max-width: 768px) {
				width: 100%;
			}
		}
		&__title {
			font-size: 17px;
			color: $color-base-dark;
			align-self: center;
			margin-bottom: 20px;
			text-align: center;
			@media (max-width: 768px) {
				grid-area: title;
				margin-bottom: 0;
				align-self: start;
				text-align: left;
			}
		}
		&__desc {
			font-size: 14px;
			line-height: 19px;
			color: $black;
			margin-top: 10px;
			margin-bottom: 20px;
			text-align: center;
			@media (max-width: 768px) {
				grid-area: desc;
				margin: 0;
				align-self: start;
				text-align: left;
			}
			@media (max-width: 420px) {
				font-size: 12px;
				margin-bottom: 10px;
			}
		}
		&__price {
			font: $font-semibold;
			font-size: 14px;
			background-color: $color-pale-green;
			color: $color-base-dark;
			border-radius: $border-radius-standart;
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			min-height: 32px;
			margin-bottom: 15px;
			@media (max-width: 768px) {
				grid-area: price;
				align-self: start;
				margin: 0;
			}
		}
		&__btn {
			background-color: $color-base-accent;
			color: $color-base-light;
			border-radius: 32px;
			font-size: 14px;
			min-width: 164px;
			min-height: 43px;
			margin: 20px 0;
			display: flex;
			justify-content: center;
			align-items: center;
			&--grey {
				color: #000000;
				background-color: #ececec;
				font-weight: 500;
			}
			@media (max-width: 768px) {
				margin: 0;
				position: absolute;
				bottom: -95px;
				width: 188px;
				left: 50%;
				margin-left: -94px;
			}
		}
		&__cap {
			font-size: 11px;
			line-height: 14px;
			color: $black;
			text-align: center;
			@media (max-width: 768px) {
				position: absolute;
				bottom: -128px;
				width: 200px;
				left: 50%;
				margin-left: -100px;
			}
		}
		&__card-title {
			align-self: flex-end;
			font-size: 10px;
			color: $black;
			margin-bottom: 15px;
			@media (max-width: 768px) {
				font-size: 13px;
				color: $color-base-dark;
				margin: 0;
				grid-area: tarif;
				justify-self: end;
				align-self: start;
			}
		}
		&--sticky{
			position: sticky;
			top: 10px;
		}
		&--transparent {
			border: none;
			box-shadow: none;
			padding: 0;
		}
		&--greyed {
			background-color: #f6f6f6;
			border: solid 1px #ececec;
			padding: 45px 21px 55px 22px;
			border-radius: 10px;
			position: relative;
			width: 100%;
			@media (max-width: 768px) {
				padding: 20px 20px;
				background-color: transparent;
			}
			.bidcard__box-name {
				position: absolute;
				top: 10px;
				right: 10px;
				font-size: 10px;
				color: inherit;
				text-decoration: none;
			}
			.bidcard__pic {
				margin-bottom: 48px;
				border: 1px solid #ececec;
				background: #fff;
				border-radius: 6px;
				height: 60px;
				width: 80%;
				@media (max-width: 768px) {
					display: none;
				}
				img {
					transform: translate(-31px, -48px);
				}
			}
			.bidcard__title {
				color: $color-text-gray;
				font-size: 14px;
				font-weight: 400;
				text-align: center;
				margin-bottom: 10px;
				margin-top: 15px;
			}
			.bidcard__desc {
				color: $color-text-gray;
			}
			.bidcard__cap {
				font-size: 14px;
				line-height: 19px;
				color: $color-text-gray;
				margin-bottom: 12px;
				margin-top: 66px;
			}
			.bidcard__btn {
				width: 90%;
				@media (max-width: 768px) {
					width: auto;
					padding: 0 15px;
				}
			}
		}
		&--spacecream{
			background-color: #fff8e6;
			.bidcard{
				&__box{
					&-title{
						font: $font-medium;
						font-size: 18px;
						margin-bottom: 14px;
						color: #000;
					}
					&-info{
						text-align: center;
						font: $font-regular;
						font-size: $fontsize-base;
						color: $color-text-gray;
						margin-bottom: 40px;
					}
				}
				&__btn{
					background-color: $color-payment-bidcard;
				}
				&-garant{
					width: 160px;
					margin-top: 10px;
					margin-bottom:15px;
					&__icon{
						background-color: $color-payment-bidcard;
					}
					&__txt{
						color: #000;
						font-weight: 500;
					}
					
					&--extra{
						display:flex;
						margin-bottom:5px;
						width: 160px;
						&__icon{
							margin-right: 11px;
							width: 20px;
							height: 20px;
							display: flex;
							justify-content: center;
							align-items: center;
						}
						&__txt{
							font: $font-regular;
							font-size: $fontsize-smaller;
							color: $color-text-gray;
							&--underline{
							font: $font-regular;
							font-size: $fontsize-smaller;
							color: $color-text-gray;
								text-decoration: underline;
							}
						}
					}
				}
			}
			
		}
	}
	.average-box {
		min-width: 208px;
		width: 90%;
		border-radius: 4px;
		border: solid 1px #ececec;
		background-color: #ffffff;
		padding: 10px 13px;
		margin-bottom: 29px;
		margin-top: 10px;
		&__title {
			color: $color-text-gray;
			margin-bottom: 10px;
			text-align: center;
		}
		&__price {
			text-align: center;
		}
	}
	.average-sum {
		width: 90%;
		&__title {
			color: $color-text-gray;
			font-size: 14px;
			text-align: center;
			margin-bottom: 10px;
		}
		&__field {
			position: relative;
			display: flex;
			width: 100%;
			margin-bottom: 15px;
			span.errors-list {
				position: absolute;
				top: calc(100% + 5px);
				left: 0;
				width: 100%;
				font-size: 10px;
				color: $danger;
				padding: 0 10px;
			}
		}
		&__inputbox {
			width: 100%;
		}
		&__input {
			@include hamster-field;
			width: 100%;
		}
		&__symbol {
			@include hamster-field;
			flex-shrink: 0;
			margin-left: 9px;
			width: 41px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 4px;
			font-size: 14px;
		}
	}
	.btn-retreat {
		color: #000000;
		border: 1px solid $danger;
		background-color: #ffffff;
		&:hover {
			background-color: #fdebeb;
			color: $danger;
		}
	}
	.bidcard-garant {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 156px;
		position: relative;
		padding: 10px 0;
		&__icon {
			width: 20px;
			height: 20px;
			display: flex;
			justify-content: center;
			align-items: center;
			color: $color-base-accent;
			background-color: #fff;
			border-radius: 50%;
			margin-right: 11px;
		}
		&:hover {
			@media (min-width: 501px) {
				.bidcard-garant__bubble {
					height: auto;
					padding: 20px 14px;
					visibility: visible;
					overflow: visible;
				}
			}
		}
		&__bubble {
			position: absolute;
			background: #fff;
			padding: 0px 14px;
			border-radius: 16px;
			box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
			max-width: 276px;
			height: 0;
			overflow: hidden;
			visibility: hidden;
			transition: 0.3s;
			top: 100%;
			left: -127px;
			@media (max-width: 500px) {
				display: none;
			}
			a {
				color: $color-base-accent;
				text-decoration: underline;
			}
			&::before {
				content: "▲";
				position: absolute;
				display: inline-block;
				top: -16px;
				left: calc(50% - 10px);
				transform: scaleX(1.6);
				color: #fff;
				text-shadow: 0px -1px 1px #ececec;
				font-size: 20px;
			}
		}
	}
	.bidcard-garant-text {
		@media (min-width: 501px) {
			display: none;
		}
		a {
			color: $color-base-accent;
			text-decoration: underline;
		}
	}
	.distance {
		&__box-graph {
			display: flex;
			justify-content: center;
			align-items: center;
			font-size: 12px;
			padding: 10px 0;
			margin: 0 10px;
			border-bottom: 1px dashed $color-base-accent;
			position: relative;
		}
		&__pin {
			position: absolute;
			color: $color-base-accent;
			bottom: 0;
			&--left {
				left: 0;
				margin-left: -11px;
			}
			&--right {
				right: 0;
				margin-right: -11px;
			}
		}
		& /deep/ i.feather {
			&--map-pin {
				width: 22px;
				height: 22px;
			}
		}
		&__box-labels {
			margin-top: 10px;
			display: flex;
			justify-content: space-between;
			span {
				font-size: 12px;
			}
		}
	}
	.pointsblank {
		&__comment {
			margin-top: 20px;
		}
		&__list {
			list-style: none;
			font-size: 14px;
		}
		&__item {
			display: grid;
			grid-template-columns: 30px 1fr 1fr;
			grid-template-areas: "mark content cap";
			&:not(:last-child) {
				margin-bottom: 16px;
			}
			@media (max-width: 768px) {
				padding: 10px 20px 15px;
				border: 1px solid #ececec;
				border-radius: 10px;
				box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
			}
			@media (max-width: 420px) {
				padding: 10px 10px 15px 20px;
				grid-template-columns: 30px 1fr;
				grid-template-areas:
					"mark content"
					"cap cap";
			}
		}
		&__content {
			grid-area: content;
		}
		&__cap {
			color: $color-base-dark;
			margin-bottom: 6px;
		}
		&__text {
			color: $color-text-dark;
		}
		&__mark {
			grid-area: mark;
			width: 18px;
			height: 18px;
			border-radius: 2px;
			border: 1px solid $black;
			color: $color-base-light;
			display: flex;
			justify-content: center;
			align-items: center;
			margin-top: 3px;
			&::before {
				content: "";
				width: 100%;
				height: 100%;
				background-repeat: no-repeat;
				background-size: 54%;
				background-position: center;
			}
			&--yes {
				background-color: $color-base-accent;
				border-color: $color-base-accent;
				&::before {
					background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4LjgiIGhlaWdodD0iNi41NTgiIHZpZXdCb3g9IjAgMCA4LjggNi41NTgiPiAgICA8ZGVmcz4gICAgICAgIDxzdHlsZT4gICAgICAgICAgICAuY2xzLTF7ZmlsbDpub25lO3N0cm9rZTojZmZmO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoxMDtzdHJva2Utd2lkdGg6MnB4fSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iY2hlY2stc3F1YXJlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTS0xLjUyNSAyLjM3OGwyLjA1MyAyLjE4IDIuNzMxLTIuNjIyTDUuMjc1IDAiIGNsYXNzPSJjbHMtMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS41MjUpIi8+ICAgIDwvZz48L3N2Zz4=);
				}
			}
			&--not {
				border-color: $color-base-attention;
				background-color: $color-base-attention;
				&::before {
					content: "—";
					display: flex;
					justify-content: center;
					align-items: center;
					font-size: 10px;
					font-weight: 700;
				}
			}
		}
		&__delivery-cap {
			grid-area: cap;
			align-self: center;
			justify-self: center;
			background-color: $color-pale-green;
			color: $color-base-dark;
			border-radius: 32px;
			padding: 4px 20px;
			font-size: 12px;
			text-align: center;
		}
	}
	.points-offer {
		flex-grow: 1;
		display: grid;
		grid-template-columns: 1fr 1fr;
		grid-template-areas:
			"title cap"
			"value cap";
		@media (max-width: 420px) {
			grid-template-areas:
				"title title"
				"value value"
				"cap cap";
		}
		&__cap {
			display: flex;
			grid-area: title;
		}
		&__icon {
			margin-left: 10px;
		}
		&__value {
			grid-area: value;
			@media (max-width: 420px) {
				margin-bottom: 10px;
			}
		}
	}
}
.bid-footer {
	@media (max-width: 768px) {
		background-color: $color-base-accent;
		margin-top: 0;
		padding-top: 20px;
		padding-bottom: 20px;
		color: $color-base-light;
	}
	&__box {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
		grid-row-gap: 10px;
		grid-template-areas:
			"copy copy copy copy copy copy"
			"expiration expiration expiration tags tags tags"
			"date date . . . .";
		@media (max-width: 768px) {
			grid-template-columns: repeat(7, 1fr);
			grid-column-gap: 10px;
			grid-template-areas:
				"expiration expiration expiration copy copy copy copy"
				"date date date tags tags tags tags";
		}
		@media (max-width: 420px) {
			grid-template-columns: 1fr 1fr;
			grid-row-gap: 15px;
			grid-template-areas:
				"expiration expiration"
				"date date"
				"copy copy"
				"tags tags";
		}
	}
	&__copy {
		grid-area: copy;
		@media (max-width: 768px) {
			justify-self: end;
		}
		@media (max-width: 420px) {
			justify-self: start;
		}
	}
	&__tags {
		grid-area: tags;
		justify-self: end;
		@media (max-width: 420px) {
			justify-self: center;
		}
	}
	&__row {
		display: flex;
		align-items: center;
		grid-area: date;
		align-self: center;
	}
	&__date {
		grid-area: date;
		align-self: center;
	}
	&__expiration {
		grid-area: expiration;
		align-self: center;
	}
	&__favorite {
		margin-left: 20px;
		@media (min-width: 769px) {
			display: none;
		}
	}
	.tags {
		display: flex;
		p {
			&:not(:last-child) {
				margin-right: 20px;
				@media (max-width: 420px) {
					margin-right: 10px;
				}
			}
		}
		.bid-category {
			border: 1px solid $color-base-accent;
			border-radius: 32px;
			color: $color-text-dark;
			font: $font-regular;
			font-size: 14px;
			padding: 6px 18px;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-wrap: wrap;
			text-align: center;
			@media (max-width: 768px) {
				color: $color-base-dark;
				border-color: $color-base-light;
				background-color: $color-base-light;
			}
			@media (max-width: 420px) {
				font-size: 11px;
				padding: 5px 6px;
			}
		}
	}
}
.bid-map {
	margin-top: 20px;
	&__show {
		text-decoration: none;
		&:hover,
		&:focus {
			text-decoration: none;
		}
	}
	&__control {
		position: relative;
		padding-bottom: 20px;
		p {
			color: $color-text-dark;
			display: flex;
			align-items: center;
			i {
				color: $color-base-accent;
			}
			b {
				font-size: 14px;
				line-height: 19px;
				font-weight: 500;
				padding: 0 15px;
			}
		}
	}
}
.bid-copylink {
	margin: 15px 0;
	display: grid;
	gap: 10px;
	grid-template-columns: 1fr auto;
	.divider {
		height: 1px;
		background-color: #ececec;
		align-self: end;
		@media (max-width: 768px) {
			display: none;
		}
	}
	&__link {
		text-decoration: underline;
		color: $color-base-accent;
		font-weight: 500;
		align-self: end;
		@media (max-width: 768px) {
			color: #fff;
		}
	}
	&__txt {
		@media (max-width: 768px) {
			text-decoration: underline;
		}
	}
	&__icon {
		margin-right: 3px;
		@media (max-width: 768px) {
			& /deep/ path {
				fill: #ffffff;
			}
		}
	}
}
.copy-link-message {
	position: fixed;
	left: 0;
	right: 0;
	bottom: 3%;
	width: 100%;
	font-size: 12px;
	background-color: #d4f5de;
	border: 1px solid $color-base-accent;
	border-radius: 10px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 5px 30px;
	min-height: 43px;
	&__pic {
		@include w-h(21px);
		@include flex-centerizer;
		background-color: $color-base-accent;
		border-radius: 50%;
		flex-shrink: 0;
		margin-right: 15px;
		& /deep/ svg {
			width: 50%;
			height: auto;
		}
	}
	&__close {
		border: none;
		background: none;
		color: $color-base-accent;
		cursor: pointer;
	}
}
.bids-intro {
	background: url(/images/bidlist/intro-bg.png) no-repeat;
	background-size: cover;
	padding-top: 155px;
	padding-bottom: 80px;

	&__inner {
		display: flex;
		align-items: center;
		margin-bottom: 30px;
		position: relative;
		// padding-top: 50px;
	}

	&__title {
		font-style: normal;
		font-weight: 800;
		font-size: 54px;
		line-height: 60px;
		margin-right: 88px;
		flex-shrink: 0;
		position: relative;
		@media screen and (max-width:1100px) {
			margin-right: 60px;
		}
		@media screen and (max-width: 1024px) {
			font-size: 46px;
			line-height: 56px;
		}
		@media (max-width: 640px) {
			font-size: 22px;
			line-height: 28px;
			margin-right: 10px;
		}
		@media (max-width: 425px) {
			z-index: 2;
		}
		@media (max-width: 320px) {
			font-size: 20px;
		}
	}
	&__picture {
		width: 25%;
		// flex-shrink: 2;
		img {
			width: 100%;
			height: auto;
		}
		@media screen and (max-width: 1024px) {
			// display: none;
		}
		@media (max-width: 640px) {
			margin-left: auto;
		}
		@media (max-width: 425px) {
			width: 30%;
			position: absolute;
			right: 0;
			top: 50%;
			transform: translateY(-50%);
		}
		@media (max-width: 320px) {
			width: 25%;
		}
	}
	&__filters {
		width: 100%;
		max-width: 1196px;
	}
}
</style>