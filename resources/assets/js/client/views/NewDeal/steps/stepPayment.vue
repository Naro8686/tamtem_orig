<template lang="pug">
form.newbid(@submit.stop.prevent="prepareStepToSave")
	section.newbid-content
		div.container
			h2.newbid__title Оплата | Цена закупки
			//- *******************************************************************
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Способ оплаты
					button(type="button" @click.prevent="toggleTip('firstTip')").newbid-card__tiptrigger
						iconTips
					div#firstTip.newbid-card__tip
						p Выберите подходящий для вас вариант расчета с поставщиком.
					section.newbid-card__content.payment-method(:class="{'errors' : errors.has('dqh_payment_method')}")
						div.newbid__select
							button(type="button" @click="addPaymentsType(['nocash','cash'])").newbid__select-all Выбрать все
							button(
								type="button"
								v-show="stepData.dqh_payment_method.length>0"
								@click="addPaymentsType([])"
							).newbid__select-clear Очистить
						div.newbid__radio.newbid__radio--grid
							div.newbid__radio-item
								input(
									type="checkbox" 
									id="paymentNocash" 
									name="paymentNocash"
									value="nocash"
									:checked="stepData.dqh_payment_method.includes('nocash')"
									v-model="stepData.dqh_payment_method"
									v-validate="`required`"
									data-vv-name="dqh_payment_method"
									data-vv-as="Способ оплаты"
								)
								label(for="paymentNocash") Безналичный расчёт
							div.newbid__radio-item
								input(
									type="checkbox" 
									id="paymentCash"
									name="paymentCash"
									value="cash"
									:checked="stepData.dqh_payment_method.includes('cash')"
									v-model="stepData.dqh_payment_method"
								)
								label(for="paymentCash") Наличный расчёт
						i.errors-list.errors-list--common(v-if="errors.has('dqh_payment_method')") {{...errors.collect('dqh_payment_method')}}
				div.newbid-tip.newbid-step__tip
					div.newbid-tip__text
						i.newbid-tip__icon
							iconTips
						p Выберите подходящий для вас вариант расчета с поставщиком.
			//- *******************************************************************
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Условия оплаты
					button(type="button" @click.prevent="toggleTip('secondTip')").newbid-card__tiptrigger
						iconTips
					div#secondTip.newbid-card__tip
						p Установите условия оплаты. Это позволит найти партнера, максимально подходящего вашей компании.
					section.newbid-card__content.payment-conditions(:class="{'errors' : errors.has('dqh_payment_term') || errors.has('prePayType') || errors.has('prePayDelayDays') ||  errors.has('prePayProcent')}")
						div.newbid__select
							button(type="button" @click="addPaymentsTerm(['prePay','onFact','delayPay'])").newbid__select-all Выбрать все
							button(
								type="button"
								@click="addPaymentsTerm([])"
								v-show="stepData.dqh_payment_term.length>0"
							).newbid__select-clear Очистить
						section.newbid__radio.newbid__radio--grid
							div.newbid__radio-item
								input(
									type="checkbox" 
									id="pre-pay" 
									name="pre-pay"
									value="prePay"
									:checked="stepData.dqh_payment_term.includes('prePay')"
									data-vv-name="dqh_payment_term"
									data-vv-as="Условия оплаты"
									v-validate="`required`"
									v-model="stepData.dqh_payment_term"
								)
								label(for="pre-pay") Предоплата
							div.newbid__radio-item
								input(
									type="checkbox" 
									id="on-fact"
									name="on-fact"
									value="onFact"
									:checked="stepData.dqh_payment_term.includes('onFact')"
									v-model="stepData.dqh_payment_term"
								)
								label(for="on-fact") По факту приемки продукции
							div.newbid__radio-item
								input(
									type="checkbox" 
									id="delay"
									name="delay"
									value="delayPay"
									:checked="stepData.dqh_payment_term.includes('delayPay')"
									v-model="stepData.dqh_payment_term"
								)
								label(for="delay") Отсрочка платежа
						i.errors-list.errors-list--common(v-if="errors.has('dqh_payment_term')") {{...errors.collect('dqh_payment_term')}}
						section.newbid__fieldsrow.payment-conditions-prepay(v-if="stepData.dqh_payment_term.includes('prePay') && stepData.dqh_payment_term.length==1")
							div.newbid__fieldsrow-field
								div.newbid__radio.newbid__radio--small
									p.newbid__radio-item.newbid__radio-item--field
										input(
											type="radio" 
											id="pre-pay-chunk" 
											name="pre-pay-value"
											v-model="stepData.prePayType"
											value="partlyPrepay"
											:checked="stepData.prePayType=='partlyPrepay'"
											data-vv-name="prePayType"
											data-vv-as="Тип предоплаты"
											v-validate="`required`"
										)
										label(for="pre-pay-chunk") Частичная
							div.newbid__fieldsrow-field
								div.newbid__radio.newbid__radio--small
									p.newbid__radio-item.newbid__radio-item--field
										input(
											type="radio" 
											id="pre-pay-full" 
											name="pre-pay-value"
											:checked="stepData.prePayType=='fullPrepay'"
											v-model="stepData.prePayType"
											value="fullPrepay"
										)
										label(for="pre-pay-full") Полная
						i.errors-list.errors-list--common(v-if="errors.has('prePayType')") {{...errors.collect('prePayType')}}
						section.newbid__fieldsrow.payment-conditions-prepay-partly(
							v-if="stepData.dqh_payment_term.includes('prePay') && stepData.dqh_payment_term.length==1 && stepData.prePayType=='partlyPrepay'"
						)
							div.newbid__fieldsrow-field
								div.payment-conditions-percent
									label.newbid__label Процент #[br] предоплаты
									div.input-wrapper
										input(
											type="text" 
											placeholder="50"
											v-model="stepData.prePayProcent"
											data-vv-as="Процент предоплаты"
											data-vv-name="prePayProcent"
											v-validate="`required|numeric|between:1,100`"
											).input-wrapper__input
										div.input-wrapper__action %
							div.newbid__fieldsrow-field
								div.payment-conditions-days
									label.newbid__label Количество дней для конечной #[br] оплаты после приёмки
									div.input-wrapper
										input(
											v-model="stepData.prePayDelayDays"
											data-vv-as="Количество дней"
											data-vv-name="prePayDelayDays"
											v-validate="`required|numeric|min_value:1`"
											type="text" 
											placeholder="14"
										).input-wrapper__input
						div.errors-list.errors-list--common(
							v-if="errors.has('prePayDelayDays') || errors.has('prePayProcent')") 
							p(v-if="errors.has('prePayDelayDays')") {{...errors.collect('prePayDelayDays')}}
							p(v-if="errors.has('prePayProcent')") {{...errors.collect('prePayProcent')}}
						section.newbid__fieldsrow.payment-conditions-delay(v-if="stepData.dqh_payment_term.includes('delayPay') && stepData.dqh_payment_term.length==1")
							div.newbid__fieldsrow-field(:class="{'errors': errors.has('delayDays')}")
								div.payment-conditions-days
									label.newbid__label Количество #[br] дней
									div.input-wrapper
										input(
											v-model="stepData.delayDays"
											type="text"
											placeholder="14"
											data-vv-name="delayDays"
											data-vv-as="Количество дней"
											v-validate="`required|numeric|min_value:1`"
										).input-wrapper__input
								i.errors-list.errors-list--common(v-if="errors.has('delayDays')") {{...errors.collect('delayDays')}}		
				div.newbid-tip.newbid-step__tip
					div.newbid-tip__text
						i.newbid-tip__icon
							iconTips
						p Установите условия оплаты. Это позволит найти партнера, максимально подходящего вашей компании.
			//- *******************************************************************
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Цена закупки
					button(type="button" @click.prevent="toggleTip('thirdTip')").newbid-card__tiptrigger
						iconTips
					div#thirdTip.newbid-card__tip 
						p Укажите диапазон цен и единицу измерения товара.
					section.newbid-card__content.payment-cost(
						:class="{'errors' : errors.has('budget_from') || errors.has('budget_to') || errors.has('unit_for_unit')}"
					)
						div.newbid__fieldsrow.payment-cost__row
							div.newbid__fieldsrow-field.payment-cost__min
								label.newbid__label От
								div.input-wrapper
									input(
										type="text" 
										placeholder="900"
										data-vv-name="budget_from"
										data-vv-as="Цена от"
										v-validate="'required|price|less:budget_to'"
										ref="budget_from"
										v-model="stepData.budget_from"
										).input-wrapper__input
									div.input-wrapper__action ₽
							div.newbid__fieldsrow-field.payment-cost__max
								label.newbid__label До
								div.input-wrapper
									input(
										type="text" 
										placeholder="1300"
										data-vv-name="budget_to"
										data-vv-as="Цена до"
										ref="budget_to"
										v-validate="'required|price|more:budget_from'"
										v-model="stepData.budget_to"
										).input-wrapper__input
									div.input-wrapper__action ₽
							div.newbid__fieldsrow-field.payment-cost__unit
								label.newbid__label За единицу измерения
								div.input-wrapper
									input(
										type="text" 
										placeholder="шт"
										v-model="stepData.unit_for_unit"
										data-vv-as="Единица измерения"
										data-vv-name="unit_for_unit"
										v-validate="`required`"
										).input-wrapper__input
									div.input-wrapper__action
						div.errors-list.errors-list--common(
							v-if="errors.has('budget_to') || errors.has('budget_from') || errors.has('unit_for_unit') "
						) 
							p(v-if="errors.has('budget_from')") {{...errors.collect('budget_from')}}
							p(v-if="errors.has('budget_to')") {{...errors.collect('budget_to')}}
							p(v-if="errors.has('unit_for_unit')") {{...errors.collect('unit_for_unit')}}
						div.newbid__checkfield
							input(
								type="checkbox" 
								id="payment-tax"
								:checked="stepData.nds"
								v-model="stepData.nds"
								)
							label(for="payment-tax") Цена указана с учётом НДС
				div.newbid-tip.newbid-step__tip
					div.newbid-tip__text
						i.newbid-tip__icon
							iconTips
						p Укажите диапазон цен и единицу измерения товара.
	footer.newbid-footer
		div.container.newbid-footer__container
			div.newbid-footer__nav
				button(
					type="button" 
					@click.prevent="$emit('goPrevStep')" 
				).newbid-footer__prev 
					feather(type="chevron-left")
					span Логистика
				button(
					type="submit" 
				).newbid-footer__next Разместить	
</template>

<script>
import { Validator } from "vee-validate";
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      stepData: {
        dqh_payment_method: [],
        dqh_payment_term: [],
        prePayType: null,
        prePayProcent: null,
        prePayDelayDays: null,
        delayDays: null,
        budget_from: null,
        budget_to: null,
        unit_for_unit: null,
        nds: false
      }
    };
  },
  created() {
    this.stepData = Object.assign({}, this.currentStep);

    Validator.extend("price", {
      getMessage: field =>
        `Поле ${field} должно быть числом и может содержать 2 знака после запятой`,
      validate: value => {
        return /^\d*[,.]?\d{0,2}$/.test(value);
      }
    });

    Validator.extend(
      "less",
      {
        getMessage: field =>
          "Минимальная цена должна быть меньше максимальной.",
        validate: (value, [args]) => {
          return (
            parseFloat(value.replace(",", ".")) <
            parseFloat(args.replace(",", "."))
          );
        }
      },
      {
        hasTarget: true
      }
    );
    Validator.extend(
      "more",
      {
        getMessage: field =>
          "Максимальная цена должна быть больше минимальной.",
        validate: (value, [args]) => {
          return (
            parseFloat(value.replace(",", ".")) >
            parseFloat(args.replace(",", "."))
          );
        }
      },
      {
        hasTarget: true
      }
    );
  },
  computed: {
    ...mapGetters("createBid", ["getStep"]),
    currentStep: {
      get() {
        return this.getStep("step_3");
      },
      set(data) {
        this.saveStep({ step: "step_3", data: data });
      }
    }
  },
  methods: {
    ...mapActions("createBid", ["saveStep"]),
    addPaymentsType(array) {
      this.stepData.dqh_payment_method = [...array];
    },
    addPaymentsTerm(array) {
      this.stepData.dqh_payment_term = [...array];
    },
    toggleTip(id) {
      document.getElementById(id).classList.toggle("newbid-card__tip--active");
    },
    prepareStepToSave() {
		this.yandexReachGoal('zakaz_razmestit');
		this.googleReachGoal('zakaz_razmestit');
		this.$validator.validateAll().then(result => {
			if (result) {
				this.currentStep = this.stepData;
				// в этом шаге генерируется событие создания заказа
				this.$emit("createOrder");
			} else {
				let errorFieldNameToScroll = this.errors.items[0].field
				let containerWithError = document.querySelector(`[data-vv-name='${errorFieldNameToScroll}']`).closest('section.newbid-step__element')
				containerWithError.scrollIntoView()
			}
		});
	}
  }
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
.errors {
  .errors-list {
    margin: 5px 0;
    padding: 0px 10px;
    font: $font-regular;
    font-size: 10px;
    color: $danger;
    display: block;
  }
	.errors-list--common {
		// общие поля ошибок приподнять к самим полям. Но убедитесь, что у тех полей есть нижний отступ в 25px
		margin-top: -15px;
	}
  input {
    border: 1px solid $danger;
  }
}
.payment-method {
	.newbid__radio--grid {
		margin-bottom: 25px;
	}
}
.payment-conditions {
  .newbid__fieldsrow {
    margin-bottom: 25px;
  }
  .newbid__radio--grid {
    margin-bottom: 25px;
    @media (max-width: 510px) {
      grid-template-columns: 1fr;
      row-gap: 20px;
    }
  }
  .newbid__fieldsrow-field {
    // margin-bottom: 25px;
  }
}

.payment-conditions-prepay-partly {
  // display: flex;
  // flex-wrap: wrap;
  @media (max-width: 520px) {
    row-gap: 20px;
  }
  div:first-child {
    grid-column: 1 / 2;
    @media (max-width: 520px) {
      grid-column: 1 / 3;
    }
    @media (max-width: 450px) {
      grid-column: 1 / 2;
    }
  }
  div:last-child {
    grid-column: 2 / 4;
    @media (max-width: 520px) {
      grid-column: 1 / 4;
    }
    @media (max-width: 450px) {
      grid-column: 1 / 2;
    }
  }
}
.payment-conditions-percent,
.payment-conditions-days {
  display: flex;
  align-items: flex-end;
  @media (max-width: 560px) {
    flex-direction: column;
    align-items: flex-start;
  }
  @media (max-width: 520px) {
    flex-direction: row;
    align-items: flex-end;
    flex-wrap: wrap;
  }
  .newbid__label {
    margin-bottom: 0;
    @media (max-width: 560px) {
      margin-bottom: 15px;
    }
    @media (max-width: 520px) {
      margin-bottom: 0;
    }
  }
  .input-wrapper {
    width: 76px;
  }
}
.payment-conditions-percent {
  justify-content: space-between;
  .newbid__label {
    margin-right: 5px;
    @media (max-width: 450px) {
      margin-right: auto;
    }
  }
}
.payment-conditions-days {
  .newbid__label {
    margin-right: 10%;
    @media (max-width: 450px) {
      margin-right: auto;
    }
  }
}
.payment-conditions-delay {
  .payment-conditions-days {
    justify-content: space-between;
		margin-bottom: 25px;

  }
}
.input-wrapper__action {
  color: #707070;
}

.payment-cost {
  &__row {
    display: flex;
    // gap: 0;
    // лучше сбрасывать гапы, потому что файрфокс применяет их к флексам, зараза
    @media (max-width: 425px) {
   	 	gap: 0;
      flex-direction: column;
      align-items: flex-start;
    }
  }
  &__minmax {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin-right: 6%;
    @media (max-width: 425px) {
      margin-right: 0;
    }
    &::after {
      content: "—";
      position: absolute;
      width: 10px;
      height: 4px;
      display: flex;
      justify-content: center;
      align-items: center;
      left: calc(50% - 5px);
      bottom: 19px;
      @media (max-width: 425px) {
        bottom: 17px;
      }
    }
  }
	&__minmax {

	}
  &__min {
    // margin-right: 10%;
  }
  &__max {
		
  }
  &__unit {
    flex-shrink: 2;
    @media (max-width: 425px) {
      align-self: flex-start;
      margin-top: 20px;
      width: 45%;
    }
  }
}
</style>