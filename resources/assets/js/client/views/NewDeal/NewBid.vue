<template lang="pug">
section.newbid
	header.newbid-topbar
		div.container.newbid-topbar__container
			ul.newbid-nav(v-if="type == 'buy'")
				li(
					:class="{'newbid-nav__item--active' : currentStep == steps[0].name}"
				).newbid-nav__item
					span.newbid-nav__count 1
					p.newbid-nav__text 
						span.newbid-nav__word Заказ
						span Спецификация
				li(:class="{'newbid-nav__item--active' : currentStep == steps[1].name}").newbid-nav__item
					span.newbid-nav__count 2
					p.newbid-nav__text 
						span.newbid-nav__word Объём
						span Мин. партия
				li(:class="{'newbid-nav__item--active' : currentStep == steps[2].name}").newbid-nav__item
					span.newbid-nav__count 3
					p.newbid-nav__text Логистика
				li(:class="{'newbid-nav__item--active' : currentStep == steps[3].name}").newbid-nav__item
					span.newbid-nav__count 4
					p.newbid-nav__text 
						span.newbid-nav__word Оплата
						span Цена закупки
			ul.newbid-nav(v-else)
				li(
					:class="{'newbid-nav__item--active' : currentStep == steps[5].name}"
				).newbid-nav__item
					span.newbid-nav__count 1
					p.newbid-nav__text {{steps[5].title}}
			a(v-b-modal="'modalNewbidHelp'" v-if="type == 'buy'").newbid-help 
				i.newbid-help__pic
					icon-help-manager
				span.newbid-help__text Помощь менеджера
	//- template(v-if="type == 'buy'")
	component(
		:is="currentStep" 
		:activeStep="currentStep"
    :typeDeal="type"
		@goPrevStep="goPrevStep" 
		@goNextStep="goNextStep" 
		@createOrder="uniteData"
		@createSell="uniteDataSell"
	)
	//- template(v-if="type == 'sell'")
	//- 	component(
	//- 		:is="sellCreate"
	//- 	)
	.errors-server-list(v-if="serverResult") {{serverResult}}
	b-modal.modal-wrap(
		id="modalNewbidEscape"
		modal-class="custom-modal"
		content-class="modal-content-custom"
		header-class="modal-header-custom"
		body-class="modal-body-custom"
		ref="modalNewbidEscape"
		@close="getAnswer('no')"
	)
		newbid-escape(@selectAnswer="getAnswer")
		div(slot='modal-footer')
	b-modal.modal-wrap(
		id="modalNewbidHelp"
		modal-class="custom-modal"
		content-class="modal-content-custom"
		header-class="modal-header-custom"
		body-class="modal-body-custom"
		ref="modalNewbidHelp"
	)
		newbid-help(@close="closeHelp")
		div(slot='modal-footer')
	b-modal.modal-wrap(
		id="modalNewbidFinal"
		ref="modalNewbidFinal"
		modal-class="custom-modal"
		content-class="modal-content-custom"
		header-class="modal-header-custom"
		body-class="modal-body-custom"
	)
		newbid-done
		div(slot='modal-footer')
</template>

<script>
import stepSpecification from "./steps/stepSpecification.vue";
import stepValue from "./steps/stepValue.vue";
import stepLogistic from "./steps/stepLogistic.vue";
import stepPayment from "./steps/stepPayment.vue";
import stepFinal from "./steps/stepFinal.vue";
import sellCreate from "./steps/sellCreate.vue";
import iconHelpManager from "../Icons/iconHelpManager.vue";

import newbidDone from "./components/newbidDone.vue";
import newbidHelp from "./components/newbidHelp.vue";
import newbidEscape from "./components/newbidEscape.vue";
import { mapGetters,  mapActions, mapState } from "vuex";
export default {
    name: "NewBid",
    components: {
      stepSpecification,
      stepValue,
      stepLogistic,
      stepPayment,
      stepFinal,
      iconHelpManager,
      newbidDone,
      newbidHelp,
      newbidEscape,
      sellCreate
    },
    data() {
      return {
        serverResult: null,
        next: null,
        sending: false,
        currentStep: "step-specification",
        steps: [
          { name: "step-specification", title: "Заказ | Спецификация" },
          { name: "step-value", title: "Объём | Мин. партия" },
          { name: "step-logistic", title: "Логистика" },
          { name: "step-payment", title: "Оплата | Цена закупки" },
          { name: "step-final", title: "Общие данные заказа" },
          { name: "sell-create", title: "Создание объявления"}
        ],
        type: ''
      };
    },
    created(){
      this.type = this.$route.meta.type
      if (this.type == 'sell') {
        this.currentStep = 'sell-create'
      }
      // подписываемся, ставим лайки, а видео на обзор...
      // подписываемся на рутовое событие (порождается кнопкой `создать заказ` в хедере)
      this.$root.$on('resetFormCreate',()=>{
        this.resetBidCreate();
      })
      // если пользователь не авторизован, скопируем себе "преднулевой" шаг из хранилища и отметим, что нужна авторизация
      if (!this.$root.isAuth) {
        let subzero = Object.assign({}, this.subzeroValue);
        subzero.needAuth = 1;
        // метод тоже из хранилища, он вносит в хранищище объект указанного шага
        this.saveStep({'step': 'step_subzero', 'data': subzero});
      }
      
    },
    beforeDestroy(){
        // удаление подписок на событие
        this.$root.$off('resetFormCreate')
    },
    watch: {
        // должен перевызвать метод подготовки данных, если токен появился, тогда цепочка методов отправки запустится
        'subzeroValue.token'(newVal,oldVal) {
            if ((newVal!=oldVal) && newVal!=null) {
                if(this.type=='buy') {
                  this.uniteData();
                } else if(this.type=='sell') {
                  this.uniteDataSell();
                }
            }
        },
        currentStep(newVal){
          if(newVal != 'step-final') {
              this.$nextTick(() => {
                  const activeStep = document.querySelector('li.newbid-nav__item--active');
                  const offset = activeStep.offsetLeft-15;
                  const parentContainer = document.querySelector('ul.newbid-nav')
                  parentContainer.scrollLeft = offset;
              })
          }
		},
		$route(to, from) {
			this.type = to.meta.type
		}
    },
    computed: {
        subzeroValue() {
            // метод позволяет получить объект состояния указанного шага из хранищила
            return this.getStep('step_subzero')
        },
        ...mapGetters("createBid",["getStep"]),
        ...mapState(["profile"]),
        prevStep() {
            let name = this.currentStep;
            let p = null;
            for (let i = 0; i < this.steps.length; i++) {
                if (this.steps[i].name === name) {
                    p = i;
                }
            }
            return p - 1;
        },
        nextStep() {
          let name = this.currentStep;
          let p = null;
          for (let i = 0; i < this.steps.length; i++) {
            if (this.steps[i].name === name) {
              p = i;
            }
          }
          return p + 1;
        },
    },
    methods: {
        // получаем сюда несколько методов из хранилища
        ...mapActions('createBid',["saveStep"]),
        ...mapActions('createBid',["clearSteps"]),
        // это метод очистки состояния, вызывается только при открытии модуля, когда с кнопки приходит событие
        resetBidCreate(){
            if (this.currentStep=='step-final'){
                this.clearSteps();
                if(this.type=='buy') {
                  this.currentStep = "step-specification";
                } else if(this.type=='sell') {
                  this.currentStep = "sell-create";
                }
            } else {
                this.$store.dispatch("setSnackbar", {
                    color: "error",
                    text: `Вы находитесь в данном разделе`,
                    toggle: true
                });
            }
        },
        closeHelp() {
            this.$refs.modalNewbidHelp.hide();
        },
        goPrevStep() {
            this.currentStep = this.steps[this.prevStep].name;
            window.scrollTo(0, 0);
        },
        goNextStep() {
            if(this.currentStep == 'sell-create') {
              this.currentStep = 'step-final'
            } else {
              this.currentStep = this.steps[this.nextStep].name;
            }
            window.scrollTo(0, 0);
        },
        // вопрос при попытке покинуть текущий адрес, м модалке
        getAnswer(answer) {
            if (answer == "yes") {
                this.clearSteps();
                this.next();
            } else {
                this.next(false);
            }
            this.$refs.modalNewbidEscape.hide();
        },
        // методы ниже вызываются в uniteData, они форматируют данные для последующей отправки
        transformTypeDeal(obj) {
            let type = obj.questions.dqh_type_deal;
            let result = `${
                type == "once"
                ? "Разовая поставка"
                : `${obj.period.id !=4 ? `Постоянная поставка раз ${obj.period.value}` : `Постоянная поставка раз ${obj.otherPeriod}` }`
            }`;
            return result;
        },
        transformLogistic(obj) {
            const arr = obj.dqh_logistics;
            let result = "";
            if (arr.length == 2) {
                result = "Доставка, Самовывоз";
            } else {
                if (arr[0] == "pickup") {
                    const arr = Array.from(obj.pickup);
                    const res = arr.reduce((acc, value) => `${acc}, ${value.name}`, "");

                    result = `Самовывоз:${res.replace(",", "")}`;
                } else {
                    const arr = Array.from(obj.delivery);
                    const res = arr.reduce((acc, value) => `${acc}, ${value.name}`, "");
                    const unpacker = obj.whoUnpack
                        ? obj.whoUnpack == "0"
                        ? "поставщик"
                        : "заказчик"
                        : null;

                    result = `Доставка:${res.replace(",", "")}.${
                        unpacker
                        ? ` Погрузочно-разгрузочные работы на складе заказчика (покупателя) осуществляет ${unpacker}.`
                        : ""
                    }`;

                    if (obj.whenDelivery) {
                        result = `${result} Поставки по ${
                        obj.whenDelivery == "0"
                            ? "рабочим дням"
                            : obj.whenDelivery == "1"
                            ? "выходным дням"
                            : "любым дням"
                        }.`;
                    }
                    if (obj.deliveryTime.startTime && obj.deliveryTime.endTime) {
                        result = `${result}. Время поставки: с ${obj.deliveryTime.startTime} до ${obj.deliveryTime.endTime}.`;
                    }
                }
            }
            return result;
        },
        transformPaymentMethod(obj) {
            let result = obj.dqh_payment_method.reduce(
                (acc, val) =>
                `${acc}, ${val == "cash" ? "Наличный расчёт" : "Безналичный расчёт"}`,
                ""
            );

            return result.replace(",", "");
        },
        transformPaymentTerm(obj) {
            let termsArray = obj.dqh_payment_term;
            let result = "";

            if (termsArray.length > 1 || termsArray[0] == "onFact") {
                result = termsArray
                .reduce(
                    (acc, item) =>
                    `${acc}, ${
                        item == "prePay"
                        ? "Предоплата"
                        : item == "onFact"
                        ? "По факту приемки продукции"
                        : "Отсрочка платежа"
                    }`,
                    ""
                )
                .replace(",", "");
            } else {
                if (termsArray[0] == "delayPay") {
                    let pluralizeRus = count => {
                        return count % 10 == 1 && count % 100 != 11
                        ? `${count} день`
                        : count % 10 >= 2 &&
                            count % 10 <= 4 &&
                            (count % 100 < 10 || count % 100 >= 20)
                        ? `${count} дня`
                        : `${count} дней`;
                    };
                    result = `Отсрочка платежа ${pluralizeRus(obj.delayDays)}.`;
                } else {
                    if (obj.prePayType == "fullPrepay") {
                        result = `Предоплата полная.`;
                    } else {
                        result = `Предоплата частичная. Процент оплаты ${obj.prePayProcent}%. Количество дней для конечной оплаты после приемки: ${obj.prePayDelayDays}`;
                    }
                }
            }
            return result;
        },

        getDateOfEvent(startDate, endDate) {
            let newStart = new Date (startDate).toLocaleString('ru', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            let newEnd = new Date(endDate).toLocaleString('ru', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            let result = "Не указано.";
            if (startDate && endDate) {
                result = `${newStart} - ${newEnd}`;
            } else if (startDate || endDate) {
                newStart!='Invalid Date' ? result = newStart : ""
                newEnd!='Invalid Date' ? result = newEnd : "";
            } else {
                result = "Не указано.";
            }
            return result;
		    },
        // лучше отделить подготовку данных для объявления
        uniteDataSell() {
          const stepData_sell = this.getStep("create_sell")
          
          let typedeal = 'sell'
          
          let transformedSteps = {
            name: stepData_sell.name,
            category_id: stepData_sell.category.id,
            city_id: stepData_sell.city.id,
            type_deal: typedeal,
            description: stepData_sell.description,
            tags: stepData_sell.keywords
          }
          // добавление в итоговый объект переменной прикрепленного файла
          if (stepData_sell.uploadedFiles){
            // let files = []
            // files.push(stepData_sell.uploadedFile)
            transformedSteps.files = stepData_sell.uploadedFiles;
          }
          // если установлено, что необходима авторизация, открыть окно регистрации
          if (this.subzeroValue.needAuth==1 && this.subzeroValue.token==null && !this.$root.isAuth){
            this.$root.$emit('showForm','registrationForm'); 
          } else {
            this.transformToFormData(transformedSteps);
          }
        },
        uniteData() {
          const stepData_0 = this.getStep("step_0");
          const stepData_1 = this.getStep("step_1");
          const stepData_2 = this.getStep("step_2");
          const stepData_3 = this.getStep("step_3");

          let questions = {
            dqh_specification: stepData_0.description,
            dqh_doc_confirm_quality: stepData_0.dqh_doc_confirm_quality
            ? "Требуются"
            : "Не требуются",
            dqh_type_deal: this.transformTypeDeal(stepData_1),
            dqh_min_party: stepData_1.questions.dqh_min_party,
            dqh_volume: stepData_1.questions.dqh_volume,
            dqh_logistics: this.transformLogistic(stepData_2),
            dqh_payment_method: this.transformPaymentMethod(stepData_3),
            dqh_payment_term: this.transformPaymentTerm(stepData_3)
          };

          let deadline_deal = new Date();
          deadline_deal.setDate(deadline_deal.getDate() + 30);
          let dd = deadline_deal.toISOString().split("T")[0];
          let typedeal='buy';

          let transformedSteps = {
            name: stepData_0.name,
            category_id: stepData_0.category.id,
            city_id: stepData_0.city.id,
            description: stepData_0.description,
            unit_for_all: `${stepData_1.unit_for_all} ${stepData_1.period.id ==4 ? stepData_1.otherPeriod : ''}`,
            date_of_event: this.getDateOfEvent(stepData_1.startDate, stepData_1.endDate),
            budget_from: stepData_3.budget_from.replace(",", "."),
            budget_to: stepData_3.budget_to.replace(",", "."),
            unit_for_unit: stepData_3.unit_for_unit,
            budget_with_nds: Number(stepData_3.nds),
            questions: questions,
            deadline_deal: dd,
            type_deal: typedeal,
          };
          // добавление в итоговый объект переменной прикрепленного файла
          if (stepData_0.uploadedFile){
            transformedSteps.file = stepData_0.uploadedFile;
          }
          // если установлено, что необходима авторизация, открыть окно регистрации
          if (this.subzeroValue.needAuth==1 && this.subzeroValue.token==null && !this.$root.isAuth){
            this.$root.$emit('showForm','registrationForm'); 
          } else {
            this.transformToFormData(transformedSteps);
          }
        },
        transformToFormData(dataObj) {
          let result = new FormData();
          for (let key in dataObj) {
            switch (key) {
              case "city_id":
                result.set("cities[]", dataObj[key]);
                break;
              case "category_id":
                result.set("categories[]", dataObj[key]);
                break;
              case "files":
                // console.log(dataObj[key])
                if(this.type == 'sell') {
                  
                } 
                // else {
                //   result.set("files[]", dataObj[key]);
                // }
                dataObj.files.forEach((item, idx) => {
                  let arrkey = `files[${idx}]`
                  result.append(arrkey, item)
                })
                break;
              case "tags":
                result.set("tags", dataObj[key]);
                break;
              case "questions":
                for (let item in dataObj[key]) {
                  let arraykey = `questions[${item}]`;
                  result.set(arraykey, dataObj[key][item]);
                }
                break;
              default:
                result.set(key, dataObj[key]);
                break;
            }
          }
          this.sendData(result);
        },
        sendData(formData) {
            const token = this.subzeroValue.token;
            this.sending = true;
            if (token!=null){
                // new implementation
                const axiosImplement = axios.create({
                    headers: { "Content-Type": "multipart/form-data", 'Authorization': `Bearer ${token}` }
                });
                axiosImplement.post("/api/v1/deals/store", formData,)
                    .then(result => {
                        if (result.data.result == true) {
                          this.clearSteps();
                          this.$bvModal.hide('registrationModal');
                          this.goNextStep();
                        }
                    })
                    .catch(err => {
                        // this.serverResult = err;
                        this.$store.dispatch("setSnackbar", {
                            color: "error",
                            text: `Произошла ошибка, попробуйте позднее`,
                            toggle: true
                        });
                        this.printErrorOnConsole(err);
                    })
                    .then(() => {
                        this.sending = false;
                    });
            } else {
                axios
                    .post("/api/v1/deals/store", formData, {
                        headers: { "Content-Type": "multipart/form-data" }
                    })
                    .then(result => {
                        if (result.data.result == true) {
                            this.clearSteps();
                            this.goNextStep();
                        }
                    })
                    .catch(err => {
                        // this.serverResult = err;
                        this.$store.dispatch("setSnackbar", {
                            color: "error",
                            text: `Произошла ошибка, попробуйте позднее`,
                            toggle: true
                        });
                        this.printErrorOnConsole(err);
                    })
                    .then(() => {
                        this.sending = false;
                    });
            }
        }
    },
    beforeRouteLeave(to, from, next) {
        if (this.currentStep == "step-final"){
            next();
        } else {
            this.next = next;
            this.$refs.modalNewbidEscape.show();
        }
    }
};
</script>

<style scoped lang="scss">
@import "~mixins";
@import "~styleguide";

.newbid {
	font: $font-regular;
	font-size: 14px;
		padding-bottom: 30px;
	&__title {
		font-weight: 500;
		font-size: 26px;
		line-height: 42px;
		margin-bottom: 40px;
		@media (max-width: 768px) {
		font-size: 22px;
		line-height: 42px;
		}
		@media (max-width: 425px) {
		font-size: 20px;
		line-height: 42px;
		}
	}

	/deep/ .newbid {
		&__title {
			font-weight: 500;
			font-size: 26px;
			line-height: 42px;
			margin-bottom: 40px;
			@media (max-width: 768px) {
				font-size: 22px;
				line-height: 42px;
			}
			@media (max-width: 425px) {
				font-size: 20px;
				line-height: 42px;
			}
		}
		&__label {
			font-size: 12px;
			line-height: 19px;
			color: $color-text-gray;
			margin-bottom: 7px;
		}
		&__radio {
			display: flex;
			flex-wrap: wrap;
			@media (max-width: 425px) {
				flex-direction: column;
			}
			&-item {
				&:not(:last-child) {
					margin-right: 25px;
					@media (max-width: 425px) {
						margin-right: 0;
						margin-bottom: 15px;
					}
				}
			}
			input {
				display: none;
			}
			label {
				padding-left: 35px;
				position: relative;
				cursor: pointer;
				&::before {
					content: "";
					width: 22px;
					height: 22px;
					border-radius: 50%;
					border: 1px solid #707070;
					background-color: #ffffff;
					position: absolute;
					left: 0;
					top: calc(50% - 11px);
				}
				&::after {
					content: "";
					width: 10px;
					height: 10px;
					position: absolute;
					top: calc(50% - 5px);
					left: 6px;
					background: transparent;
					border-radius: 10px;
				}
			}
			input:checked + label::before {
				border-color: $color-base-accent;
			}
			input:checked + label::after {
				background-color: $color-base-accent;
			}
			&--grid {
				display: grid;
				grid-template-columns: 1fr 1fr 1fr;
				column-gap: 4%;
				align-items: center;
				@media (max-width: 425px) {
					grid-template-columns: 1fr;
					row-gap: 15px;
				}
			}
			&--small {
				.newbid__radio-item {
					margin-right: 0;
				}
				label {
					padding-left: 25px;
				}
				label::before {
					width: 14px;
					height: 14px;
					top: calc(50% - 7px);
				}
				label::after {
					width: 4px;
					height: 4px;
					left: 5px;
					top: calc(50% - 2px);
				}
				input:checked + label::before {
					background-color: $color-base-accent;
					border-color: $color-base-accent;
				}
				input:checked + label::after {
					background-color: #fff;
				}
				.newbid__radio-item--field {
				width: 100%;
				label {
					@include hamster-field;
					padding-left: 37px;
					&::before {
					left: 13px;
					}
					&::after {
					left: 18px;
					}
				}
				input:checked + label {
					border-color: $color-base-accent;
				}
				}
			}
		}
		&__fieldsrow {
			// самый общий вариант, остальное делается уникальными классами или каскадом
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			column-gap: 6%;
			margin-bottom: 25px;
			@media (max-width: 450px) {
				grid-template-columns: 1fr;
				row-gap: 20px;
			}
			&-field {
				align-self: end;
			}
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
				padding-left: 20px;
				position: relative;
				margin-top: 3px;
				&::before {
				content: "×";
				position: absolute;
				width: 12px;
				height: 12px;
				top: calc(50% - 6px);
				left: 0;
				border-radius: 50%;
				margin-right: 7px;
				background: #f6f6f6;
				display: flex;
				justify-content: center;
				align-items: center;
				}
			}
		}
		&__checkfield {
			margin-top: 30px;
			input {
				display: none;
			}
			label {
				position: relative;
				padding-left: 35px;
				cursor: pointer;
				@media (max-width: 425px) {
					font-size: 12px;
					line-height: 19px;
				}
				&::before {
					content: "";
					position: absolute;
					width: 18px;
					height: 18px;
					top: 0;
					left: 0;
					border-radius: 3px;
					border: 1px solid #000;
					background-repeat: no-repeat;
					background-position: center;
					background-size: 99%;
				}
			}
			input:checked + label::before {
				background-image: url(/images/check-square.svg);
				border-color: $color-base-accent;
			}
		}
		.newbid-content {
		margin-bottom: 30px;
		}
		.newbid-footer {
			&__container {
				display: grid;
				grid-template-columns: repeat(12, 1fr);
				column-gap: 24px;
			}
			&__nav {
				grid-column: 1 / 9;
				display: grid;
				grid-template-columns: 1fr 1fr;
				@media (max-width: 768px) {
				grid-column: 1 / 13;
				}
			}
			&__next {
				@include big-rounded-btn;
				text-transform: none;
				grid-column: 2 / 3;
				justify-self: end;
			}
			&__prev {
				grid-column: 1 / 2;
				justify-self: start;
				display: flex;
				align-items: center;
				cursor: pointer;
				min-width: 153px;
				border: none;
				background: none;
				label {
					text-align: left;
				}
			}
		}
		.newbid-step {
		&__element {
			display: grid;
			grid-template-columns: repeat(12, 1fr);
			column-gap: 24px;
			&:not(:last-child) {
				margin-bottom: 74px;
				@media (max-width: 768px) {
					margin-bottom: 45px;
				}
				@media (max-width: 425px) {
					margin-bottom: 30px;
				}
			}
			&--nostretch {
				.newbid-tip {
					align-self: start;
					padding-bottom: 25px;
				}
			}
		}
		&__card {
			grid-column: 1 / 9;
			@media (max-width: 768px) {
				grid-column: 1 / 13;
			}
		}
		&__tip {
			grid-column: 9 / 13;
			@media (max-width: 768px) {
				display: none;
			}
		}
		}
    .newbid-card {
      @include contentbox;
      position: relative;
      padding-top: 20px;
      @media (max-width: 425px) {
        padding: 20px 10px;
      }
      &__title {
        font-size: 18px;
        line-height: 42px;
        font-weight: 500;
        margin-bottom: 20px;
        @media (max-width: 768px) {
          font-size: 17px;
        }
        @media (max-width: 425px) {
          font-size: 15px;
          line-height: 19px;
          margin-bottom: 28px;
        }
      }
      &__tiptrigger {
        @media (min-width: 769px) {
          display: none;
        }
        position: absolute;
        right: 20px;
        top: 26px;
        @media (max-width: 425px) {
          right: 10px;
          top: 15px;
        }
      }
      &__tip {
        @media (min-width: 769px) {
          display: none;
        }
        &--active {
          height: auto;
          visibility: visible;
          padding: 14px;
          margin-bottom: 25px;
          margin-top: 30px;
          border: 1px solid $color-pale-green;
          p:not(:first-child) {
            margin-top: 10px;
          }
        }
        height: 0;
        overflow: hidden;
        visibility: hidden;
        padding: 0px;
        background-color: #ecfaf1;
        font-size: 12px;
        line-height: 16px;
      }
    }
    .newbid-tip {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
      padding: 26px 13px 13px 66px;
      position: relative;
      &__icon {
        font-style: normal;
        position: absolute;
        top: 20px;
        left: 15px;
      }
      &__text {
        p {
          font-size: 12px;
          line-height: 19px;
          &:not(:last-child) {
            margin-bottom: 15px;
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
  }
}

.newbid-topbar {
  box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
  margin-bottom: 40px;
  &__container {
    display: flex;
    justify-content: space-between;
    min-height: 68px;
    align-items: center;
    @media (max-width: 660px) {
      display: grid;
      grid-template-columns: 1fr;
      grid-template-areas: "help" "nav";
      padding-top: 10px;
      padding-bottom: 10px;
      gap: 20px;
    }
    ul::-webkit-scrollbar {
      height: 0px;
    }
  }
}
.newbid-nav {
  display: flex;
  @media (max-width: 660px) and (min-width: 541px) {
    flex-wrap: wrap;
    grid-area: nav;
  }
  @media (max-width: 540px) {
    overflow-x: scroll;
    padding-top: 30px;
    scrollbar-width: none;
    scroll-behavior: smooth;
  }
  &__item {
    display: flex;
    align-items: center;
    list-style: none;
    cursor: pointer;
    @media (max-width: 660px) and (min-width: 541px) {
      margin-bottom: 10px;
    }
    @media (max-width: 540px) {
      // карточка
      min-width: 213px;
      height: 90px;
      border: 1px solid $color-base-accent;
      border-radius: 10px;
      position: relative;
      align-items: flex-end;
      padding: 15px;
    }
    &:not(:last-child) {
      margin-right: 20px;
      @media (max-width: 540px) {
        margin-right: 10px;
      }
    }
    &--active {
      @media (max-width: 540px) {
        background-color: $color-base-accent;
      }
      .newbid-nav__text {
        border-bottom: 2px solid $color-base-accent;
        @media (max-width: 540px) {
          color: $color-text-light;
        }
      }
      .newbid-nav__count {
        background-color: $color-base-accent;
        color: $color-text-light;
        @media (max-width: 540px) {
          color: #000;
          font-weight: 500;
          background-color: #fff;
        }
      }
    }
  }
  &__text {
    font-size: 13px;
    line-height: 24px;
    display: flex;
    @media (max-width: 1050px) and (min-width: 541px) {
      flex-direction: column;
      line-height: 17px;
      padding-bottom: 4px;
    }
    .newbid-nav__word {
      &::after {
        content: "|";
        margin-left: 5px;
        margin-right: 5px;
        @media (max-width: 1050px) and (min-width: 541px) {
          display: none;
        }
      }
    }
  }
  &__count {
    margin-right: 10px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: 1px solid $color-base-accent;
    font-size: 13px;
    flex-shrink: 0;
    @media (max-width: 540px) {
      width: 53px;
      height: 53px;
      font-size: 22px;
      position: absolute;
      top: -27px;
      left: 15px;
      background-color: $color-base-light;
    }
  }
}
.newbid-help {
  background-color: $color-pale-green;
  color: #000;
  font-size: 13px;
  line-height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 43px;
  padding: 5px 25px;
  border-radius: 35px;
  @media (max-width: 660px) {
    grid-area: help;
    justify-self: end;
  }
  &__pic {
    display: flex;
    margin-right: 14px;
    @media (max-width: 768px) {
      margin-right: 0;
    }
  }
  &__text {
    @media (max-width: 768px) {
      display: none;
    }
  }
}
</style>
