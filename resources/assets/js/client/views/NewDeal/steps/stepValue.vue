<template lang="pug">
form.newbid(@submit.prevent="prepareStepToSave()")
	section.newbid-content
		div.container
			h2.newbid__title Объём | Мин. партия
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Регулярность поставки
					button(@click.prevent="toggleTip('firstTip')").newbid-card__tiptrigger
						iconTips
					div#firstTip.newbid-card__tip Укажите объем, который вы готовы получить от одного поставщика. Также укажите минимальный объем партии.
					section.newbid-card__content.deal-value
						div.newbid__radio.newbid__radio--grid.deal-value__radio
							div.newbid__radio-item
								input(
									type="radio" 
									id="once-deal" 
									name="deal-type"
									value="once" 
									v-model="stepData.questions.dqh_type_deal"
								)
								label(for="once-deal") Разовая поставка
							div.newbid__radio-item
								input(
									type="radio" 
									id="permanent-deal" 
									name="deal-type" 
									value="permanent"
									v-model="stepData.questions.dqh_type_deal"
								)
								label(for="permanent-deal") Постоянная поставка
						section.deal-value__permanent
							div.permanent-regularity(v-if="stepData.questions.dqh_type_deal =='permanent'")
								div.permanent-regularity__wrapper(@click.exact="periodsVisible=false" :class="{'active': periodsVisible}")
								div.permanent-regularity__field(@click="periodsVisible=!periodsVisible")
									span {{stepData.period.value|capitalize}}
									button(type="button").permanent-regularity__arrow
										feather(type="chevron-down")
									ul.permanent-regularity__dropdown(:class="{'permanent-regularity__dropdown--active': periodsVisible}")
										li(v-for="period in periods" :key="period.id" @click="selectPeriod(period)") {{period.value|capitalize}}
								div.permanent-regularity__field.permanent-regularity__field--other-period(v-if="stepData.period.id==4")
									div.input-wrapper(:class="{'errors':errors.has('otherPeriod')}")
										input.input-wrapper__input(
											type="text"
											placeholder="Укажите подходящий период"
											data-vv-name='otherPeriod'
											data-vv-as='Период'
											v-validate=`'required'`
											v-model="stepData.otherPeriod"
										)
										i.errors-list(v-if="errors.has('otherPeriod')") {{...errors.collect('otherPeriod')}}
							div.newbid__fieldsrow
								div.newbid__fieldsrow-field
									label(for="permanent-value").newbid__label Общий объем заказа
									div.input-wrapper.permanent-value(:class="{'errors': errors.has('volume')}")
										input(
											data-vv-as="Общий объём заказа"
											data-vv-name="volume"
											v-model="stepData.questions.dqh_volume"
											v-validate=`'required|numeric'`
											type="text" 
											id="once-value" 
											placeholder="50").input-wrapper__input
										i.errors-list(v-if="errors.has('volume')") {{...errors.collect('volume')}}
								div.newbid__fieldsrow-field
									label(for="permanent-unit").newbid__label Единица измерения
									div.input-wrapper.permanent-unit(:class="{'errors': errors.has('unit')}")
										input(
											data-vv-as="единицы измерения"
											data-vv-name="unit"
											v-validate=`'required'`
											v-model="stepData.unit_for_all"
											type="text" 
											id="once-unit" 
											placeholder="тонн").input-wrapper__input
										i.errors-list(v-if="errors.has('unit')") {{...errors.collect('unit')}}
								div.newbid__fieldsrow-field
									label(for="permanent-minvalue").newbid__label Минимальная партия
									div.input-wrapper.permanent-minparty(:class="{'errors': errors.has('min_party')}")
										input(
											type="text" 
											data-vv-as="Минимальная партия" 
											data-vv-name="min_party" 
											v-validate=`'required'` 
											v-model="stepData.questions.dqh_min_party" 
											id="once-minvalue" 
											placeholder="50 тонн").input-wrapper__input
										i.errors-list(v-if="errors.has('min_party')") {{...errors.collect('min_party')}}
				div.newbid-tip.newbid-step__tip
					div.newbid-tip__text
						i.newbid-tip__icon
							iconTips
						p Укажите объем, который вы готовы получить от одного поставщика. Также укажите минимальный объем партии.
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Период поставки
					button(@click.prevent="toggleTip('secondTip')").newbid-card__tiptrigger
						iconTips
					div#secondTip.newbid-card__tip Выберите необходимый период поставки. Вы можете договориться о поставке заранее (например, за 3, 6, 12 месяцев).
					section.newbid-card__content
						div.deal-date
							div.deal-date__field
								label.newbid__label Укажите дату начала
								div.input-wrapper
									div.input-wrapper--background-white
										input(type="date" v-model="stepData.startDate").input-wrapper__input
										//div.input-wrapper__action
										//	icon-calendar
										//- button.input-wrapper__action-calendar
									i.errors-list
							div.deal-date__field
								label.newbid__label Укажите дату окончания
								div.input-wrapper
									div.input-wrapper--background-white
										input(type="date" v-model="stepData.endDate").input-wrapper__input
										//div.input-wrapper__action
										//	icon-calendar
									i.errors-list
				div.newbid-tip.newbid-step__tip
					div.newbid-tip__text
						i.newbid-tip__icon
							iconTips
						p Выберите необходимый период поставки. Вы можете договориться о поставке заранее (например, за 3, 6, 12 месяцев).
	footer.newbid-footer
		div.container.newbid-footer__container
			div.newbid-footer__nav
				button(
					type="button" 
					@click.prevent="$emit('goPrevStep')" 
				).newbid-footer__prev 
					feather(type="chevron-left")
					span Заказ | Спецификация
				button(
					type="submit" 
				).newbid-footer__next Далее
</template>

<script>
import iconCalendar from "../../Icons/iconCalendar.vue";
import { mapActions, mapGetters } from "vuex";
export default {
	components: {
		iconCalendar
	},
	props: {
		activeStep: {
			type: String
		}
	},
	filters: {
		capitalize(text) {
			return text[0].toUpperCase() + text.slice(1);
		}
	},
	data() {
		return {
			periods: [
				{
					id: 0,
					value: "в день"
				},
				{
					id: 1,
					value: "в неделю"
				},
				{
					id: 2,
					value: "в месяц"
				},
				{
					id: 3,
					value: "в квартал"
				},
				{
					id: 4,
					value: "Другой вариант"
				}
			],
			periodsVisible: false,
			typeDeal: "once",
			isOnceDeal: true,
			isPermanentDeal: false,
			commonValue: "",
			valueUnit: "",
			regularity: "",
			startDate: "",
			endDate: "",
			minParty: "",
			maxParty: "",
			partyUnit: "",

			stepData: {
				otherPeriod: null,
				startDate: "",
				endDate: "",
				period: {
					id: 2,
					value: "В месяц"
				},
				unit_for_all: null,
				date_of_event: null,
				questions: {
					dqh_type_deal: "once",
					dqh_min_party: null,
					dqh_volume: null
				}
			}
		};
	},
	created() {
		// сохраняем объект из вычисляемого свойства
		this.stepData = Object.assign({}, this.currentStep);
	},
	computed: {
		...mapGetters("createBid", ["getStep"]),
		currentStep: {
			get() {
				// забирает данные из хранилища
				return this.getStep("step_1");
			},
			set(data) {
				this.saveStep({ step: "step_1", data: data });
			}
		}
	},
	methods: {
		...mapActions("createBid", ["saveStep"]),
		prepareStepToSave() {
			this.$validator.validateAll().then(result => {
				if (result) {
					this.currentStep = this.stepData;
					this.$emit("goNextStep");
				}else{
					let errorFieldNameToScroll = this.errors.items[0].field
					let containerWithError = document.querySelector(`[data-vv-name='${errorFieldNameToScroll}']`).closest('section.newbid-step__element')
					containerWithError.scrollIntoView()
				}
			});
		},
		selectPeriod(period) {
			this.stepData.period = Object.assign({}, period);
		},
		toggleTip(id) {
			document.getElementById(id).classList.toggle("newbid-card__tip--active");
		}
	}
};
</script>

<style lang="scss" scoped>
@import "~styleguide";

input[type="date"] {
	&::-webkit-inner-spin-button {
		display: none !important;
	}
	&::webkit-clear-button {
		display: none !important;
	}
}
.newbid__title {
	background-color: #fff;
}
.deal-value__radio {
	&-item {
		margin-right: 25px;
	}
}

.newbid {
	&__fieldsrow {
		&-field {
			// align-self: start !important;
		}
	}
}

.deal-value__common {
	margin-top: 45px;
}
.deal-value__permanent {
	margin-top: 45px;
}
.permanent-regularity {
	display: grid;
	grid-template-columns: 1fr 1fr;
	column-gap: 4%;
	margin-bottom: 20px;
	align-items: start;
	&__wrapper{
		&.active{
			position: fixed;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			z-index: 1;
		}
	}
	&__field {
		grid-column: 1 / 2;
		@include hamster-field;
		position: relative;
		@media (max-width: 520px) {
			grid-column: 1 / 4;
		}
		&--other-period {
			grid-column: 2/2;
			padding: 0;
			width: 100%;
			border: none;
			background: transparent;
			@media (max-width: 520px) {
				grid-column: 1/4;
				margin-top: 10px;
			}
		.input-wrapper{
		width: 100%;
		input{
			width: 100%;
		}  
		}
		}
	}
	&__arrow {
		position: absolute;
		height: 20px;
		right: 4px;
		top: calc(50% - 10px);
		display: flex;
		align-items: center;
		justify-content: center;
		background: none;
		border: none;
	}
	&__dropdown {
		position: absolute;
		left: 0;
		top: 100%;
		width: 100%;
		height: 0;
		overflow: hidden;
		z-index: 2;
		border: 1px solid $color-border-gray;
		border-top: none;
		transition: height 0.3s;
		&--active {
			height: auto;
		}
		li {
			list-style: none;
			padding: 12px;
			font-size: 12px;
			background: #fff;
			cursor: pointer;
			&:hover {
				background-color: $color-pale-green;
			}
		}
	}
}
.deal-date {
	display: grid;
	grid-template-columns: 1fr 1fr 1fr;
	column-gap: 4%;
	@media (max-width: 768px) {
		grid-template-columns: 1fr 1fr;
	}
	@media (max-width: 450px) {
		grid-template-columns: 1fr;
		row-gap: 20px;
	}
	&__field {
		align-self: end;
	}
}
</style>