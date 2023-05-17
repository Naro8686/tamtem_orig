<template lang="pug">
form.newbid(@submit.prevent="prepareStepToSave")
	section.newbid-content
		div.container
			h2.newbid__title Логистика
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Способ доставки
					div.
					button(type="button" @click.prevent="toggleTip('firstTip')").newbid-card__tiptrigger
						iconTips
					div#firstTip.newbid-card__tip 
						p Укажите, нужна ли доставка или у вас есть возможность вывезти продукцию. Вы можете выбрать оба варианта. 
						p Укажите город доставки товара, кто будет производить погрузочно-разгрузочные работы и области/округа, из которых производится самовывоз. Это увеличит охват поставщиков.
					section.newbid-card__content.logistic(:class="{'errors' : errors.has('dqh_logistics')}")
						div.newbid__select
							button(type="button" @click="addDeliverys(['delivery','pickup'])").newbid__select-all Выбрать все
							button(
								type="button"
								v-show="stepData.dqh_logistics.length>0"
								@click="addDeliverys([])"
							).newbid__select-clear Очистить
						section.newbid__radio.logistic__radio.newbid__radio--grid(:class="{'errors' : errors.has}")
							div.newbid__radio-item
								input(
									type="checkbox" 
									id="deliver" 
									name="deliver"
									value="delivery"
									:checked="stepData.dqh_logistics.includes('delivery')"
									v-model="stepData.dqh_logistics"
									v-validate="`required`"
									data-vv-name="dqh_logistics"
									data-vv-as="Способ доставки"
								)
								label(for="deliver") Доставка
							div.newbid__radio-item
								input(
									type="checkbox" 
									id="pickup"
									name="pickup"
									value="pickup"
									:checked="stepData.dqh_logistics.includes('pickup')"
									v-model="stepData.dqh_logistics"
								)
								label(for="pickup") Самовывоз
						i.errors-list(v-if="errors.has('dqh_logistics')") {{...errors.collect('dqh_logistics')}}
						section.delivery(v-if="stepData.dqh_logistics.includes('delivery') && stepData.dqh_logistics.length==1")
							div.newbid__fieldsrow.delivery__region
								div.newbid__fieldsrow-field
									label(for="delivery-region").newbid__label Регион
									div.input-wrapper(@click="showRegionsList('delivery')" :class="{'errors' : errors.has('deliveryRegion')}")
										input(type="text" v-validate=`'required|requiredSet'` hidden data-vv-as="Регион" data-vv-name="deliveryRegion" v-model="stepData.delivery")
										input(
											readonly
											type="text"
											id="delivery-region"
											name="delivery-region"
											placeholder="Петропавловск-камчатский"
										).input-wrapper__input
										div.input-wrapper__action
											icon-map-pin
										i.errors-list(v-if="errors.has('deliveryRegion')") {{...errors.collect('deliveryRegion')}}
							ul.regions-tags
								li(v-for="region in stepData.delivery") 
									span {{region.name}}
									button(type="button" @click="removeRegion('delivery',region)") &times;
							section.delivery__shipment
								p.newbid__label Погрузочно-разгрузочные работы на складе заказчика (покупателя)
								div.newbid__radio.newbid__radio--small
									div.newbid__radio-item
										input(
											type="radio" 
											id="shipment-vendor" 
											name="shipment"
											value="0"
											:checked="stepData.whoUnpack=='0'"
											v-model="stepData.whoUnpack"
										)
										label(for="shipment-vendor") Поставщик
									div.newbid__radio-item
										input(
											type="radio" 
											id="shipment-customer" 
											name="shipment"
											value="1"
											:checked="stepData.whoUnpack=='1'"
											v-model="stepData.whoUnpack"
										)
										label(for="shipment-customer") Заказчик
							div.delivery__row
								section.delivery__regime
									p.newbid__label Поставки по
									div.newbid__radio.newbid__radio--small
										div.newbid__radio-item
											input(
												type="radio" 
												id="regime-workday"
												name="regime"
												value="0"
												:checked="stepData.whenDelivery=='0'"
												v-model="stepData.whenDelivery"
											)
											label(for="regime-workday") По рабочим дням
										div.newbid__radio-item
											input(
												type="radio" 
												id="regime-weekend" 
												name="regime"
												value="1"
												:checked="stepData.whenDelivery=='1'"
												v-model="stepData.whenDelivery"
											)
											label(for="regime-weekend") Выходным дням
										div.newbid__radio-item
											input(
												type="radio" 
												id="regime-anyday" 
												name="regime"
												value="2"
												:checked="stepData.whenDelivery=='2'"
												v-model="stepData.whenDelivery"
											)
											label(for="regime-anyday") Любым дням
								section.delivery__time
									p.newbid__label Время поставки
									div.delivery__time-fields
										div.delivery__time-field
											input(
												type="text"
												placeholder="00 : 00"
												id="delivery-time-start"
												name="delivery-time-start"
												v-mask="`##:##`"
												v-model="stepData.deliveryTime.startTime"
											)
										div.delivery__time-field
											input(
												type="text"
												placeholder="00 : 00"
												id="delivery-time-end"
												name="delivery-time-end"
												v-mask="`##:##`"
												v-model="stepData.deliveryTime.endTime"
											)
						section.pickup(v-if="stepData.dqh_logistics.includes('pickup') && stepData.dqh_logistics.length==1")
							div.newbid__fieldsrow
								div.newbid__fieldsrow-field.pickup__region
									label(for="pickup-region").newbid__label Регион, в котором удобно забрать товар
									div.input-wrapper( @click="showRegionsList('pickup')" :class="{'errors' : errors.has('pickupRegion')}")
										input(type="text" v-validate=`'required|requiredSet'` hidden data-vv-as="Регион" data-vv-name="pickupRegion" v-model="stepData.pickup")
										input(
											readonly
											type="text" 
											id="pickup-region"
											name="pickup-region"
											placeholder="Петропавловск-камчатский"
										).input-wrapper__input
										div.input-wrapper__action
											icon-map-pin
										i.errors-list(v-if="errors.has('pickupRegion')") {{...errors.collect('pickupRegion')}}
							ul.regions-tags
								li(v-for="region in stepData.pickup") 
									span {{region.name}}
									button(type="button" @click="removeRegion('pickup',region)") &times;
				div.newbid-tip.newbid-step__tip
					div.newbid-tip__text
						i.newbid-tip__icon
							iconTips
						p Укажите, нужна ли доставка или у вас есть возможность вывезти продукцию. Вы можете выбрать оба варианта. 
						p Укажите город доставки товара, кто будет производить погрузочно-разгрузочные работы и области/округа, из которых производится самовывоз. Это увеличит охват поставщиков.
			footer.newbid-footer
				div.container.newbid-footer__container
					div.newbid-footer__nav
						button(
							type="button" 
							@click.prevent="$emit('goPrevStep')" 
						).newbid-footer__prev 
							feather(type="chevron-left")
							span Объём | Мин. партия
						button(
							type="submit" 
						).newbid-footer__next Далее
			b-modal.modal-wrap(
				id="modalNewbidRegions"
				ref="modalNewbidRegions"
				header-class="modal-header-custom"
				modal-class="custom-modal-wide"
				body-class="modal-body-custom"
				content-class="modal-content-custom"
			)
				newbid-regions(
					@close="hideRegionsSelect"
					@selectRegion="selectRegions"
					:activeList="typeDelivery=='delivery' ? stepData.delivery : stepData.pickup"
				)
				div(slot='modal-footer')
</template>
<script>
import newbidRegions from "../components/newbidRegions.vue";
import iconMapPin from "../../Icons/iconMapPin.vue";
import { mapGetters, mapActions } from "vuex";
import { mask } from 'vue-the-mask';
import { Validator } from 'vee-validate';
export default {
  components: {
    iconMapPin,
    newbidRegions
  },
  directives: {
		mask
	},
  data() {
    return {
      typeDelivery: null,
      stepData: {
		delivery: null,
		whoUnpack: null,
		whenDelivery: null,
        deliveryTime: {
			startTime: null,
			endTime: null,
		},
        pickup: null,
        dqh_logistics: []
      }
    };
  },
  created() {
    this.stepData = Object.assign({}, this.currentStep);
  
	Validator.extend('requiredSet',{
		 getMessage: field =>
		  `Поле ${field} обязательно для заполнения`,
		  validate: value => {
			  console.log(value)
			  return (value && value.size>0)
		  }
	})
 
 },
  computed: {
    ...mapGetters("createBid", ["getStep"]),
    currentStep: {
      get() {
        return this.getStep("step_2");
      },
      set(data) {
        this.saveStep({ step: "step_2", data: data });
      }
    }
  },
  methods: {
	...mapActions('createBid',["saveStep"]),
	addDeliverys(array){
		this.stepData.dqh_logistics = [...array]
	},
    hideRegionsSelect() {
      this.$refs.modalNewbidRegions.hide();
    },
    removeRegion(type, region) {
      this.stepData[type].delete(region);
      this.stepData[type] = new Set(this.stepData[type]);
    },
    showRegionsList(typeDelivery) {
      this.typeDelivery = typeDelivery;
      this.$refs.modalNewbidRegions.show();
    },
    selectRegions(regions) {
      this.stepData[this.typeDelivery] = new Set(regions);
      this.hideRegionsSelect();
    },
    toggleTip(id) {
      document.getElementById(id).classList.toggle("newbid-card__tip--active");
    },
    prepareStepToSave() {
		this.$validator.validateAll().then(result=>{
			if (result){
				this.currentStep = this.stepData;
				this.$emit('goNextStep')
			}else{
					let errorFieldNameToScroll = this.errors.items[0].field
					let containerWithError = document.querySelector(`[data-vv-name='${errorFieldNameToScroll}']`).closest('section.newbid-step__element')
					containerWithError.scrollIntoView()
				}
		})
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
	input {
		border: 1px solid $danger;
	}
}
.logistic {
  &__radio {
    margin-bottom: 25px;
  }
  &__select {
    position: absolute;
    top: 26px;
    right: 20px;
    display: flex;
    flex-direction: column;
    @media (max-width: 768px) {
      right: 62px;
    }
    @media (max-width: 425px) {
      top: 16px;
    }
    &-all {
      font-size: 14px;
      line-height: 24px;
      border-bottom: 1px solid $color-base-accent;
    }
    &-clear {
      margin-top: 5px;
      &::before {
        content: "×";
        padding: 4px;
        border-radius: 50%;
        margin-right: 7px;
        background: #fff;
      }
    }
  }
}
.delivery {
  .newbid__fieldsrow {
    margin-bottom: 20px;
  }
  .newbid__fieldsrow-field {
    grid-column: 1 / 4;
  }
  &__shipment {
    margin-bottom: 20px;
    .newbid__radio {
      flex-direction: column;
      &-item {
        margin-bottom: 10px;
      }
    }
  }
  &__row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    column-gap: 4%;
    @media (max-width: 1024px) {
      grid-template-columns: 1fr 1fr;
    }
    @media (max-width: 768px) {
      grid-template-columns: 1fr;
    }
  }
  &__region {
    .input-wrapper__input {
      cursor: pointer;
    }
  }
  &__regime {
    .newbid__radio {
      flex-direction: column;
      &-item {
        margin-bottom: 10px;
      }
    }
  }
  &__time {
    &-fields {
      display: flex;
    }
    &-field {
      @include hamster-field;
      width: 73px;
      input {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
      }
    }
    &-field:first-child {
      margin-right: 31px;
      position: relative;
	  &::after {
        content: "—";
        margin: 0 22px;
		    position: absolute;
    		top: 25%;
    		right: -45px;
      }
    }
  }
}
.pickup {
  margin-top: 45px;
  .newbid__fieldsrow-field {
    grid-column: 1 / 3;
    @media (max-width: 520px) {
      grid-column: 1 / 4;
    }
  }
  &__region {
    .input-wrapper__input {
      cursor: pointer;
    }
  }
}
.regions-tags {
  display: flex;
  flex-wrap: wrap;
  margin-top: 30px;
  li {
    list-style: none;
    font-size: 14px;
    line-height: 18px;
    border-bottom: 1px dashed #000;
    position: relative;
    margin-bottom: 14px;
    &:not(:last-child) {
      margin-right: 27px;
    }
    button {
      position: absolute;
      right: -9px;
      top: -9px;
      font-style: normal;
      font-size: 16px;
    }
  }
}
</style>