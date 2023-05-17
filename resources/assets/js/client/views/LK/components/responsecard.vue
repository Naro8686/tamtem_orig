<template lang="pug">
	article(
		:class="[cardStatus, {'responsecard--folded' : folded}, {'responsecard--payed-unfold' : isOpenPayed}]"
	).responsecard
		b-modal.modal-wrap(
		:id="`modalResponseReport${response.responseId}`"
		:ref="`modalResponseReport${response.responseId}`"
		modal-class="modal-response"
		body-class="response-modal-body"
	)
			modal-deny(:id='response.dealId')
			div(slot='modal-footer')
		h4.clickable(
			@click="folded =! folded"
		).responsecard__number Заявка № {{response.dealId}}
		p.responsecard__name.clickable(@click="openResponse()") Заказ: {{response.dealName}}
		p.responsecard__budget 
			| Бюджет 
			span.responsecard__budget-cost
					budget(style="display:inline-block;" type="buy" :from="response.budget_from" :to="response.budget_to")
			span.responsecard__name(v-if="response.budget_with_nds==1") #[br] Цена указана с учётом НДС
		p(
			v-if="smallDevice == true"
		).responsecard__period-head
			| Период сделки: {{response.dealPeriod}}
		div(
			:class="[{'responsecard__offer--payed' : isOpenPayed}, {'responsecard__offer--delivered' : isDelivered}]"
		).responsecard__offer
			span.responsecard__offer-cap Предложение
			p.responsecard__offer-cost {{response.priceOffer}} ₽
				i.responsecard__offer-delivery(v-show="response.is_shipping_included == true")
					deliverycar
		//- дополнительный блок оффера для футера оплаченной заявки в мобильных разрешениях
		div(
			v-if="isOpenPayed && smallDevice"
		).responsecard__offer.responsecard__offer--ext
			| Предложение
			span.responsecard__offer-cost {{response.priceOffer}} ₽
		div(
			class="responsecard__status"
			:class="{'responsecard__status--hidden' : isOpenPayed && !smallDevice}"
		)
			span(v-if="response.status == 'pending'").responsecard__status-cap Ожидание
			span(v-if="response.status == 'chosen'").responsecard__status-cap Завершено #[br] Ожидает оплаты
			span(v-if="response.status =='closed' || response.status =='payed'").responsecard__status-cap Завершено
		div(
			v-if="response.status == 'payed' && response.user && response.user.company"
			v-show="folded == false"
		).responsecard__customer Заказчик: {{response.user.company}}
		div.responsecard__action
			button.responsecard__action-btn.responsecard__action-btn--leave.responsecard__leave(
				v-if="response.status == 'pending' && response.status != 'closed'"
				v-b-modal="`modalRetreat${response.responseId}`")
				span.responsecard__leave-cap Отмена
				//i.responsecard__leave-icon
					svg(xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 612.043 612.043")
						path(d="M397.503 306.011L593.08 110.434c25.27-25.269 25.27-66.213 0-91.482-25.269-25.269-66.213-25.269-91.481 0L306.022 214.551 110.445 18.974c-25.269-25.269-66.213-25.269-91.482 0s-25.269 66.213 0 91.482L214.54 306.033 18.963 501.61c-25.269 25.269-25.269 66.213 0 91.481 25.269 25.27 66.213 25.27 91.482 0l195.577-195.576 195.577 195.576c25.269 25.27 66.213 25.27 91.481 0 25.27-25.269 25.27-66.213 0-91.481L397.503 306.011z")
			button.responsecard__action-btn.responsecard__action-btn--pay(
				@click="payforContacts()"
				v-if="response.status == 'chosen' && response.status != 'closed'"
				)
					| Оплатить
			button.responsecard__action-btn.responsecard__action-btn--write(v-if="response.status == 'payed' && response.status != 'closed'" @click="messageWrite()")
				| Написать
		div.responsecard__chevron(@click="openResponse()")
			feather(type="chevron-up")
		section(v-show="folded != true").responsecard__body
			p(
				v-if="smallDevice != true"
			).responsecard__period
				| Период сделки: {{response.dealPeriod}}
			div(
				v-if="response.status == 'payed' && response.user"
			).responsecard__body-contact.contact
				div.contact__person
					img(:src="response.user.userPhoto").contact__person-pic
					ul.contact__person-info
						li.contact__person-item {{response.user.userName}}
						li.contact__person-item {{this.phoneFormat(response.user.userPhone)}}
						li.contact__person-item {{response.user.userMail}}
				//- ul.contact__requisites
					li.contact__requisites-item ИНН {{response}}
					li.contact__requisites-item КПП  456365783
					li.contact__requisites-item Адрес: Барнаул, Вознесения, 105, 3 этаж офис №305
			div(v-if="response.status == 'chosen'").responsecard__chosen-msg.chosen-msg
				b.chosen-msg__icon !
				p.chosen-msg__text
					| Вас выбрали на роль поставщика, для получения контактов пополните кошелёк 
					template(v-if="response.summ") на сумму {{response.summ}} ₽
					| #[br]
					template(v-if="response.deadlinePayment") Оплатить нужно в течение 72 часов
			div.responsecard__spec
				h5.responsecard__spec-title Спецификация
				p.responsecard__spec-text(v-html="response.answers.dqh_specification.value")
			div.responsecard__file(v-if="response.files && response.files.length>0")
				clip
				.responsecard__file--file
					a(:href="response.files[0].path").responsecard__file--file-name {{response.files[0].name}}
					//- p.responsecard__file--file-size 10.6 МБ
			div.responsecard__report(v-if="response.isPayed && response.status=='payed'")
				button(type="button" v-b-modal="`modalResponseReport${response.responseId}`").responsecard__report-btn Сделка не состоялась?
			div.responsecard__conditions.conditions(
				v-slidescroll="{'buttonLeft':'conditions__control--left','buttonRight':'conditions__control--right', 'scrollElem':'conditions__list'}"
			)
				.conditions__control.conditions__control--left(v-if="!smallDevice")
					svg(xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 46" width="20" height="20")
						path(fill="#27d066" d="M11.003 40.094c-1.338 1.352-1.338 3.541 0 4.893 1.336 1.35 3.506 1.352 4.844 0l19.15-19.539c1.338-1.352 1.338-3.543 0-4.895L15.847 1.014c-1.338-1.352-3.506-1.352-4.844 0s-1.338 3.541-.002 4.893L26.706 23 11.003 40.094z").conditions__arrow
				ul.conditions__list
					li.conditions__item(v-for="answer in sortSequence")
						p.conditions__name {{response.answers[answer].header}}
						p.conditions__value {{response.answers[answer].value}} 
				.conditions__control.conditions__control--right(v-if="!smallDevice")
					svg(xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 46" width="20" height="20")
						path(fill="#27d066" d="M11.003 40.094c-1.338 1.352-1.338 3.541 0 4.893 1.336 1.35 3.506 1.352 4.844 0l19.15-19.539c1.338-1.352 1.338-3.543 0-4.895L15.847 1.014c-1.338-1.352-3.506-1.352-4.844 0s-1.338 3.541-.002 4.893L26.706 23 11.003 40.094z").conditions__arrow
			b-modal(
				:ref="`modalRetreat${response.responseId}`"
				:id="`modalRetreat${response.responseId}`"
				modal-class="custom-modal"
			)
				retreat(:responseId="response.responseId" @close="`${$refs[`modalRetreat${response.responseId}`].hide()}`" @leave=" `${$refs[`modalRetreat${response.responseId}`].hide()}`; $emit('leaveDeal',response.responseId)" text="ААААААААААААААААА")
				div(slot='modal-footer')
</template>
<script>
import budget from "../../GeneralComponents/components/Budget";
import modalDeny from "../components/modalDeny.vue";
import { mapActions } from "vuex";
import Retreat from "../../GeneralComponents/components/retreatBid";
import clip from "../../Icons/iconClip.vue";
export default {
  components: {
    budget,
    clip,
    modalDeny,
    Retreat
  },
  props: {
    response: {
      type: Object,
      required: true
    }
  },
  directives: {
    slidescroll: {
      inserted(el, bindings) {
        const container = el;
        const scrollLeft = container.querySelector(
          `.${bindings.value.buttonLeft}`
        );
        const scrollRight = container.querySelector(
          `.${bindings.value.buttonRight}`
        );
        const scrollContainer = container.querySelector(
          `.${bindings.value.scrollElem}`
        );
        if (scrollLeft) {
          scrollLeft.addEventListener("click", () => {
            scroll("left");
          });
        }
        if (scrollRight) {
          scrollRight.addEventListener("click", () => {
            scroll("right");
          });
        }
        function scroll(direction) {
          switch (direction) {
            case "left":
              scrollContainer.scrollLeft -= 120;
              break;
            case "right":
              scrollContainer.scrollLeft += 120;
              break;
            default:
              break;
          }
        }
      }
    }
  },
  data() {
    return {
      folded: true,
      notificationsData: null,
      sortSequence: [
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
    this.notificationsData = {
      id: this.response.responseId,
      command: "is_readed_owner_response"
    };
  },
  computed: {
    cardStatus() {
      return `responsecard--${this.response.status}`;
    },
    smallDevice() {
      return window.innerWidth <= 590;
    },
    isOpenPayed() {
      return this.response.status == "payed" && this.folded != true;
    },
    isDelivered() {
      return this.response.is_shipping_included == true;
    }
  },
  methods: {
    ...mapActions(["updateNotifications"]),
    payforContacts() {
      let url = `/api/v1/paymentservice/subscriptions/${this.response.paymentslug}/payment`;
      axios
        .post(url, { deal_id: this.response.dealId })
        .then(resp => {
          if (resp.data.result) {
            this.$store.dispatch("setSnackbar", {
              color: "success",
              text: "Успешно оплачено",
              toggle: true
            });

            this.yandexReachGoal("pay_button_in_lk_if_balance_norm");
            this.googleReachGoal("pay_button_in_lk_if_balance_norm");

            window.Api.loadProfile();

            if (this.response.isReadedByMe == 0) {
              this.updateNotifications(this.notificationsData);
            }

            this.$emit("reloadResponses");
          } else {
            this.gotoPayment();
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
    messageWrite() {
      axios
        .post("/api/v1/dialogs/new", { deal_id: this.response.dealId })
        .then(resp => {
          if (resp.data.result) {
            if (this.response.isReadedByMe == 0) {
              this.updateNotifications(this.notificationsData);
            }
            this.$router.push({
              name: "lk.dialogs.private",
              params: { id: resp.data.data.id }
            });
          }
        })
        .catch(error => {
          this.printErrorOnConsole(error);
        });
    },
    openResponse() {
      if (
        this.folded &&
        this.response.isReadedByMe == 0 &&
        this.response.isPayed
      ) {
        this.updateNotifications(this.notificationsData);
      }
      this.folded = !this.folded;
    },
    gotoPayment() {
      if (true) {
        let props = {
          summ: this.response.summ,
          slug: this.response.paymentslug,
          dealId: this.response.dealId
        };
        this.$router.push({ name: "lk.wallet", params: { response: props } });
      } else {
      }
    }
  }
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";

$color-status-pending: #53a4d7;
$color-status-pending-light: #bbdcf2;
$color-status-payed: $color-green-elements;
$color-status-payed-light: #d4f5de;
$color-status-chosen: $color-voply-card-header;
$color-status-chosen-light: #fff8e6;
$color-status-closed: #ed5448;
$color-status-closed-light: #fdebeb;

.clickable {
  cursor: pointer;
}
.responsecard {
  font-family: $font-family;
  color: $black;
  box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
  border-radius: $border-radius-standart;
  padding-left: 32px;
  padding-right: 24px;
  padding-top: 24px;
  padding-bottom: 24px;
  @include media-breakpoint-down(sm) {
    padding: 24px 20px;
  }
  @media (max-width: 640px) {
  }
  &__number {
    color: $color-base-dark;
    font: $font-semibold;
    font-size: 17px;
    grid-area: number;
    padding-right: 10px;
    @media (max-width: 590px) {
      font-size: 16px;
    }
  }
  &__name {
    color: $color-base-dark;
    font: $font-medium;
    font-size: 14px;
    grid-area: name;
    @media (max-width: 590px) {
      font-size: 12px;
    }
  }
  &__budget {
    color: $color-base-dark;
    font: $font-medium;
    font-size: 14px;
    &-cost {
      font: $font-semibold;
      font-size: 17px;
      @media (max-width: 590px) {
        font-size: 16px;
      }
    }
    @media (max-width: 590px) {
      font-size: 12px;
    }
    grid-area: budget;
    @include media-breakpoint-down(md) {
      margin-top: 10px;
    }
    @media (max-width: 590px) {
      margin-top: 15px;
    }
  }
  &__period-head {
    color: $black;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-border-gray;
    font: $font-regular;
    font-size: 12px;
    line-height: 19px;
    margin-top: 14px;
    grid-area: headperiod;
    margin-bottom: 10px;
  }
  &__offer,
  &__status,
  &__action {
    color: $color-base-dark;
    font: $font-regular;
    font-size: 12px;
    line-height: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 32px;
    width: 136px;
    // height: 40px;
    overflow: hidden;
    @media (max-width: 590px) {
      height: 34px;
      font-size: 10px;
      line-height: 13px;
    }
    // align-self: start;
  }
  &__customer {
    color: $color-base-dark;
    font: $font-semibold;
    font-size: 17px;
    grid-area: customer;
    @media (max-width: 640px) {
      align-self: end;
    }
  }
  &__offer {
    grid-area: offer;
    justify-self: start;
    align-self: center;
    @media (max-width: 610px) {
      width: 100%;
      font-size: 10px;
    }
    &-cost {
      margin-top: 3px;
      display: flex;
      align-items: center;
    }
    &-delivery {
      // margin-top: 3px;
      margin-left: 3px;
      & /deep/ svg {
        @media (max-width: 610px) {
          height: 12px;
          width: auto;
        }
        path {
          fill: #223b94;
        }
      }
    }

    &--payed {
      background: none;
      display: block;
      width: auto;
      height: auto;
      font-size: 14px;
      justify-self: start;
      .responsecard__offer-cost {
        font: $font-semibold;
        font-size: 17px;
        margin-left: 5px;
      }
      @media (max-width: 590px) {
        margin-top: 10px;
        font-size: 12px;
      }
    }
    &--ext {
      grid-area: extoffer;
    }
  }
  &__status {
    &-cap {
      text-align: center;
    }
    grid-area: status;
    justify-self: start;
    height: 40px;
    align-self: center;
    @media (max-width: 610px) {
      width: 100%;
      font-size: 10px;
    }
  }
  &__action {
    width: 102px;
    height: 40px;
    align-self: center;
    &-btn {
      @media (max-width: 590px) {
        font-size: 12px;
      }
      font-size: 14px;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 32px;
      &--leave {
        background-color: $color-base-light;
        border: 1px solid $color-status-pending;
        color: $color-status-pending;
        font-weight: 500;
        &:hover {
          background-color: #53a4d7;
          color: #fff;
        }
      }
      &--write {
        color: $color-base-light;
        background-color: $color-status-payed;
      }
      &--pay {
        color: $color-base-light;
        background-color: $color-status-chosen;
      }
    }
    .responsecard__leave {
      &-icon {
        width: 20px;
        height: 20px;
        padding: 5px;
        border: 1px solid $color-status-pending;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left: 5px;
        svg {
          fill: $color-status-pending;
          stroke: $color-status-pending;
        }
      }
    }
    // GRID
    grid-area: action;
    @media (max-width: 610px) {
      align-self: start;
      justify-self: end;
    }
  }

  &__body {
    overflow: hidden;
    @media (max-width: 590px) {
      margin-bottom: 20px;
    }
    grid-area: body;
    margin-top: 15px;
    display: grid;
    grid-column-gap: 10px;
    grid-template-columns: auto 1fr 1fr 136px 136px 102px 24px;
    grid-template-areas:
      "period period period content content content ."
      "period period period content content content ."
      "spec spec spec content content content ."
      "file file file . . . ."
      ". . . report report report ."
      "cond cond cond cond cond cond .";
    @media (max-width: 710px) {
      grid-template-areas:
        "period period period period period period ."
        "content content content content content content ."
        "spec spec spec spec spec spec ."
        "file file file file file file ."
        "report report report report report report ."
        "cond cond cond cond cond cond .";
    }
    @media (max-width: 590px) {
      grid-template-columns: repeat(6, 1fr);
    }
  }
  &__period {
    font: $font-regular;
    font-size: 14px;
    color: $black;
    grid-area: period;
    @media (max-width: 640px) {
      margin-bottom: 15px;
    }
  }
  &__body-contact {
    grid-area: content;
  }
  &__chosen-msg {
    grid-area: content;
  }
  &__spec {
    grid-area: spec;
    margin-top: 35px;
    &-title {
      font: $font-regular;
      font-size: 14px;
      color: $color-base-dark;
      margin-bottom: 10px;
    }
    &-text {
      text-align: left;
      color: $black;
      font: $font-regular;
      font-size: 14px;
      line-height: 19px;
      word-break: break-all;
      hyphens: auto;
      @media (max-width: 590px) {
        font-size: 12px;
      }
    }
  }
  &__file {
    grid-area: file;
    display: flex;
    margin-top: 15px;
    &--file {
      margin-left: 20px;
      &-name {
        font: $font-regular;
        font-size: 12px;
        color: #000;
      }
      &-size {
        font: $font-regular;
        font-size: 12px;
        color: $color-text-gray;
      }
    }
  }
  &__report {
    grid-area: report;
    justify-self: end;
    padding-top: 15px;
    &-btn {
      border: 1px solid #ed5448;
      color: #ed5448;
      border-radius: 20px;
      background-color: #fff;
      width: 210px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      @media (max-width: 425px) {
        font-size: 12px;
        width: 200px;
        height: 38px;
      }
    }
  }
  &__conditions {
    grid-area: cond;
    margin-top: 40px;
  }
  &__chevron {
    cursor: pointer;
    grid-area: chevron;
    align-self: center;
    transition: transform 0.4s;
    & /deep/ .feather__content {
      stroke-width: 2px;
      stroke: #707070;
    }
    @media (max-width: 611px) {
      display: none;
    }
  }
  &--folded {
    .responsecard__chevron {
      transform: rotate(180deg);
    }
  }
  // /////////////////////////////////////////////////////////////////////////////
  // /////////////////////////////////////////////////////////////////////////////
  // GRID
  display: grid;
  grid-template-columns: auto 1fr 1fr 136px 136px 102px 24px;
  grid-column-gap: 10px;
  grid-template-areas:
    "number name name offer status action chevron"
    "budget budget budget offer status action chevron"
    "body body body body body body body";
  @include media-breakpoint-down(md) {
    grid-template-areas:
      "number number number offer status action chevron"
      "name name name offer status action chevron"
      "budget budget budget offer status action chevron"
      "body body body body body body body";
  }
  @media (max-width: 640px) {
    grid-template-areas:
      "number number number offer status action chevron"
      "name name name offer status action chevron"
      "budget budget budget offer status action chevron"
      "body body body body body body body";
  }
  @media (max-width: 590px) {
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
    grid-template-areas:
      "number number number number number number"
      "name name name name name name"
      "budget budget budget budget budget budget"
      "headperiod headperiod headperiod headperiod headperiod headperiod"
      ". . . . action action"
      "body body body body body body"
      "offer offer offer status status status";
  }
  &--folded {
    grid-template-areas:
      "number name name offer status action chevron"
      "budget budget budget offer status action chevron";
    @media (max-width: 640px) {
      grid-template-areas:
        "number number number offer status action chevron"
        "name name name offer status action chevron"
        "budget budget budget offer status action chevron";
    }
    @media (max-width: 590px) {
      grid-template-areas:
        "number number number number action action"
        "name name name name action action"
        "budget budget budget budget budget budget"
        "headperiod headperiod headperiod headperiod headperiod headperiod"
        "offer offer offer status status status";
    }
  }
  &--pending {
    border-left: 3px solid $color-status-pending;
    .responsecard__status {
      background-color: $color-status-pending-light;
    }
    .responsecard__body {
      @media (max-width: 640px) {
        grid-template-areas:
          "period period period period period period"
          "spec spec spec spec spec spec"
          "file file file file file file"
          "cond cond cond cond cond cond";
        .responsecard__spec {
          margin-top: 0;
        }
      }
    }
  }
  &--payed {
    border-left: 3px solid $color-status-payed;
    .responsecard__status {
      background-color: $color-status-payed-light;
      &--hidden {
        display: none;
      }
    }
    &-unfold {
      .responsecard__offer {
        border-radius: 0;
      }
      grid-template-areas:
        "number name name offer offer action chevron"
        "budget budget budget customer customer action chevron"
        "body body body body body body body";
      @include media-breakpoint-down(md) {
        grid-template-areas:
          "number number number offer offer action chevron"
          "name name name offer offer action chevron"
          "budget budget budget customer customer action chevron"
          "body body body body body body body";
      }
      @media (max-width: 590px) {
        grid-template-areas:
          "number number number number number number"
          "name name name name name name"
          "budget budget budget budget budget budget"
          "headperiod headperiod headperiod headperiod headperiod headperiod"
          ". . . . action action"
          "offer offer offer offer offer offer"
          "customer customer customer customer customer customer"
          "body body body body body body"
          "extoffer extoffer extoffer status status status";
      }
    }
  }
  &--chosen {
    border-left: 3px solid $color-status-chosen;
    .responsecard__status {
      background-color: $color-status-chosen-light;
    }
  }
  &--closed {
    border-left: 3px solid $color-status-closed;
    .responsecard__status {
      background-color: $color-status-closed-light;
    }
  }
}
.contact {
  font: $font-regular;
  font-size: 14px;
  line-height: 17px;
  color: $black;
  @media (max-width: 590px) {
    font-size: 12px;
  }
  &__person {
    display: flex;
  }
  &__person-info {
    list-style: none;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
  }
  &__person-pic {
    display: block;
    width: 64px;
    height: 64px;
    border-radius: $border-radius-standart;
    background-color: $color-base-accent;
    margin-right: 10px;
  }
  &__requisites {
    list-style: none;
    margin-top: 15px;
    &-item {
      &:not(:last-child) {
        margin-bottom: 8px;
      }
    }
  }
}
.conditions {
  @media (max-width: 590px) {
    font-size: 12px;
    padding: 15px;
    padding-right: 0;
  }
  background-color: $color-base-gray-light;
  border-radius: $border-radius-standart;
  font: $font-regular;
  font-size: 14px;
  padding: 20px;
  padding-right: 60px;
  padding-left: 60px;
  position: relative;
  overflow: hidden;
  &__list {
    list-style: none;
    overflow-x: scroll;
    display: flex;
    scrollbar-width: none;
    scroll-behavior: smooth;
  }
  ul::-webkit-scrollbar {
    height: 0px;
  }

  &__item {
    display: flex;
    flex-direction: column;
    min-width: 56px;
    max-width: 160px;
    flex-shrink: 0;
    &:not(:last-child) {
      margin-right: 45px;
    }
  }
  &__name {
    color: $color-base-dark;
    margin-bottom: 12px;
  }
  &__control {
    cursor: pointer;
    position: absolute;
    width: 50px;
    height: 44px;
    background-color: $color-base-gray;
    right: 0;
    border-top-left-radius: 50%;
    border-bottom-left-radius: 50%;
    top: calc(50% - 22px);
    display: flex;
    justify-content: center;
    align-items: center;
    &--left {
      left: 0;
      transform: rotate(180deg);
    }
  }
  &__arrow {
    width: 9px;
    height: 14px;
  }
}
.chosen-msg {
  background-color: $color-status-chosen-light;
  border-radius: 32px;
  display: flex;
  align-items: center;
  padding: 5px 11px;
  // GRID
  align-self: start;
  &__icon {
    width: 28px;
    height: 28px;
    background-color: $color-base-light;
    margin-right: 16px;
    font-weight: 500;
    color: $color-base-attention;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  &__text {
    color: $color-base-dark;
    font: $font-regular;
    font-size: 12px;
  }
}
</style>