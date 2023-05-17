<template lang="pug">
.tabs
  .maintabs
    button.maintab.active Кошелёк
  div(v-if="profile")
    b-tabs(v-model="tabInd", nav-wrapper-class="subtabs", @input="changeTab")
      b-tab(title="Баланс", title-link-class="subtab")
        .content
          .balance_row
            p Баланс на сегодня
            p {{ profile.profile.ballance.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ') }}
              span ₽
          .payment_row
            div
              p Пополнение кошелька
            form.wallet__form.form-wallet(@submit.prevent="validateBeforeSubmit")
              .payment_right_block
                .custom-input-block
                  v-text-field.price__fieldlol(tabindex=1, v-model="form.price", solo, :light="true", placeholder="Сумма", data-vv-as="сумма", data-vv-name="price", v-validate=`'required|numeric'`, :error-messages="err.price ? err.price : errors.collect('price')")
                  label.custom-input-label(for="input_balance") Введите сумму
                .custom-input-block
                  p.payment__element
                    label.payment__element-wrapper
                      input.payment__element-value(tabindex=2, name="paymenttype", v-model="form.paymenttype", type="radio", value="bank-transaction")
                      p.payment__element-title
                        i.payment__element-icon
                          icon-bank
                        i.payment__element-text Банковский перевод
                  button.custom-btn-green__btn.profile__btn(tabindex=4, type="submit", :disabled="loading")
                    span {{ form.paymenttype == 'bank-transaction' ? 'Получить счет на оплату' : 'Оплатить' }}
                    b-spinner(v-if="loading", label="Загрузка...")
                  label.custom-input-label(for="payment_method") Способ оплаты

      b-tab(title="История платежей", title-link-class="subtab")
        .content(:class="{'active' : DropDownFilter}")
          .payments_button
            a.payments_filter_button(@mouseover="openDropDownFilter" @mouseleave="closeDropDownFilter") выбрать период
              svg(width="9", height="5", viewBox="0 0 9 5", fill="none", xmlns="http://www.w3.org/2000/svg")
                path(
                  d="M4.49294 4.49848C4.343 4.49877 4.19769 4.44649 4.08223 4.3507L0.231828 1.13807C0.100775 1.02901 0.0183608 0.872293 0.0027155 0.702393C-0.0129298 0.532492 0.0394754 0.363328 0.148402 0.232113C0.257329 0.100899 0.413856 0.0183834 0.583547 0.00271886C0.753238 -0.0129457 0.922194 0.0395238 1.05325 0.148585L4.49294 3.0271L7.93264 0.25139C7.99828 0.198018 8.07381 0.158161 8.15488 0.13411C8.23596 0.110059 8.32098 0.102288 8.40507 0.111245C8.48915 0.120201 8.57064 0.145707 8.64485 0.186298C8.71906 0.226889 8.78452 0.281764 8.83748 0.347768C8.89625 0.413833 8.94076 0.491339 8.96822 0.575428C8.99568 0.659518 9.0055 0.74838 8.99707 0.836446C8.98864 0.924513 8.96213 1.00989 8.91921 1.08722C8.87629 1.16455 8.81788 1.23218 8.74764 1.28585L4.89723 4.38925C4.77846 4.4699 4.63611 4.50836 4.49294 4.49848Z",
                  fill="#888888"
                )
            .payments_filter_menu(:class="{'active' : DropDownFilter}", @mouseleave="closeDropDownFilter" v-click-outside="closeDropDownFilter")
              ul
                li(:class="{ active: activeFilter == 0 }")
                  a(@click="filterPayments(0)") За неделю
                li(:class="{ active: activeFilter == 1 }")
                  a(@click="filterPayments(1)") За месяц
                li(:class="{ active: activeFilter == 2 }")
                  a(@click="filterPayments(2)") За три месяца
                li(:class="{ active: activeFilter == 3 }")
                  a(@click="filterPayments(3)") За шесть месяцев
                li(:class="{ active: activeFilter == 4 }")
                  a(@click="filterPayments(4)") За год
          .payments_table
            .payments_table_row_top
              p Дата
              p Назначение
              p Описание
              p СУММА, ₽

            PaymentItem(:item="item", v-for="item in paymentsList", :key="item.id")
</template>
<script>
import PaymentItem from "../components/PaymentItem"
import CustomInput from "./../../GeneralComponents/components/CustomInput"
import mixin from "../../../mixins/global"
import { Validator } from "vee-validate"
import vue from "vue"

vue.component("icon-bank", {
  template: `
	<svg xmlns="http://www.w3.org/2000/svg" width="28.018" height="28.004" viewBox="0 0 28.018 28.004">
		<defs>
		</defs>
		<g id="bank-building" transform="translate(0 -.012)">
				<g id="Layer_1_78_" transform="translate(0 .012)">
						<g id="Group_1032" data-name="Group 1032">
								<path id="Path_50" d="M26.732 42.718H1.273a1.273 1.273 0 0 0 0 2.547h25.459a1.273 1.273 0 0 0 0-2.547z" style="fill:#27d066" data-name="Path 50" transform="translate(0 -17.26)"/>
								<path id="Path_51" d="M3.942 28.54a1.274 1.274 0 0 0 0 2.547h22.277a1.274 1.274 0 0 0 0-2.547H25.9V17.083h.318a.636.636 0 0 0 0-1.272H3.942a.636.636 0 0 0 0 1.272h.318V28.54h-.318zm19.413-11.457V28.54h-3.819V17.083zm-6.365 0V28.54h-3.819V17.083zm-10.183 0h3.819V28.54h-3.82z" style="fill:#27d066" data-name="Path 51" transform="translate(-1.078 -6.393)"/>
								<path id="Path_52" d="M1.273 8.285h25.471a1.273 1.273 0 0 0 .413-2.478L14.526.123a1.276 1.276 0 0 0-1.044 0L.751 5.851a1.273 1.273 0 0 0 .522 2.434z" style="fill:#27d066" data-name="Path 52" transform="translate(0 -.012)"/>
						</g>
				</g>
		</g>
</svg>`
})
vue.component("icon-card", {
  template: `
		<svg id="credit-card" xmlns="http://www.w3.org/2000/svg" width="19.707" height="13.138" viewBox="0 0 19.707 13.138">
			<g id="Group_8522" data-name="Group 8522" transform="translate(0 0)">
				<g id="Group_8521" data-name="Group 8521" transform="translate(0 0)">
					<path id="Path_8871" data-name="Path 8871" d="M17.654,85.333H2.053A2.056,2.056,0,0,0,0,87.386v9.032a2.056,2.056,0,0,0,2.053,2.053h15.6a2.056,2.056,0,0,0,2.053-2.053V87.386A2.056,2.056,0,0,0,17.654,85.333Zm1.232,11.085a1.233,1.233,0,0,1-1.232,1.232H2.053A1.233,1.233,0,0,1,.821,96.418V87.386a1.233,1.233,0,0,1,1.232-1.232h15.6a1.233,1.233,0,0,1,1.232,1.232v9.032Z" transform="translate(0 -85.333)" fill="#2acc5a"/>
				</g>
			</g>
			<g id="Group_8524" data-name="Group 8524" transform="translate(0 2.463)">
				<g id="Group_8523" data-name="Group 8523" transform="translate(0 0)">
					<path id="Path_8872" data-name="Path 8872" d="M19.3,149.333H.411a.411.411,0,0,0-.411.411v2.463a.411.411,0,0,0,.411.411H19.3a.411.411,0,0,0,.411-.411v-2.463A.411.411,0,0,0,19.3,149.333Z" transform="translate(0 -149.333)" fill="#2acc5a"/>
				</g>
			</g>
			<g id="Group_8526" data-name="Group 8526" transform="translate(2.463 8.211)">
				<g id="Group_8525" data-name="Group 8525">
					<path id="Path_8873" data-name="Path 8873" d="M69.337,298.667H64.411a.411.411,0,1,0,0,.821h4.927a.411.411,0,1,0,0-.821Z" transform="translate(-64 -298.667)" fill="#2acc5a"/>
				</g>
			</g>
			<g id="Group_8528" data-name="Group 8528" transform="translate(2.463 9.854)">
				<g id="Group_8527" data-name="Group 8527">
					<path id="Path_8874" data-name="Path 8874" d="M69.337,341.333H64.411a.411.411,0,0,0,0,.821h4.927a.411.411,0,0,0,0-.821Z" transform="translate(-64 -341.333)" fill="#2acc5a"/>
				</g>
			</g>
			<g id="Group_8530" data-name="Group 8530" transform="translate(13.959 7.39)">
				<g id="Group_8529" data-name="Group 8529">
					<path id="Path_8875" data-name="Path 8875" d="M364.72,277.333H363.9a1.233,1.233,0,0,0-1.232,1.232v.821a1.233,1.233,0,0,0,1.232,1.232h.821a1.233,1.233,0,0,0,1.232-1.232v-.821A1.233,1.233,0,0,0,364.72,277.333Z" transform="translate(-362.667 -277.333)" fill="#2acc5a"/>
				</g>
			</g>
		</svg>
	`
})
export default {
  components: {
    PaymentItem,
    CustomInput
  },
  props: {
    profile: Object
  },
  mixins: {
    mixin
  },
  data() {
    return {
      snackbar: {
        toggle: false,
        timeout: 3000,
        color: "",
        text: ""
      },
      err: {},
      form: {
        price: this.$route.params.response ? this.$route.params.response.summ : null,
        paymenttype: "bank-transaction"
      },
      loading: false,
      paylink: null,
      paymentsList: [],
      today: null,
      weekFilter: null,
      monthFilter: null,
      threeMonthsFilter: null,
      sixMonthsFilter: null,
      yearFilter: null,
      DropDownFilter: false,
      activeFilter: null,
      tabs: ["balance", "history"],
      tabInd: 0
    }
  },
  mounted() {
    axios.post("/api/v1/finance-operations").then((resp) => {
      this.paymentsList = resp.data.data.data
      console.log(this.paymentsList)
    })

    if (this.$route.query.error) {
      this.addError(this.$route.query.error)
    }
  },
  computed: {
    ballance() {
      return this.profile.profile.ballance || 0
    }
  },
  methods: {
    closeDropDownFilter() {
      let blockTop = document.getElementsByClassName("payments_filter_button")
      if (event.composedPath().indexOf(blockTop[0]) == -1 && this.DropDownFilter) this.DropDownFilter = !this.DropDownFilter
    },
    openDropDownFilter() {
      this.DropDownFilter = !this.DropDownFilter
    },
    filterPayments(filterid) {
      this.activeFilter = filterid
      let date = new Date()

      switch (filterid) {
        case 0:
          date.setDate(date.getDate() - 7)
          break
        case 1:
          date.setDate(date.getDate() - 30)
          break
        case 2:
          date.setDate(date.getDate() - 90)
          break
        case 3:
          date.setDate(date.getDate() - 180)
          break
        case 4:
          date.setDate(date.getDate() - 365)
          break
        default:
      }

      let month = date.getMonth() + 1
      let today = new Date()
      let todayMonth = today.getMonth() + 1

      let filterdate = date.getFullYear() + "-" + month + "-" + date.getDate()
      today = today.getFullYear() + "-" + todayMonth + "-" + today.getDate()

      axios
        .post("/api/v1/finance-operations", {
          date_to: today,
          date_from: filterdate
        })
        .then((resp) => {
          this.paymentsList = resp.data.data.data
          console.log(this.paymentsList)
        })
    },
    loadingSet(val) {
      this.$parent.loading = val
    },
    changeTab() {
      this.setHash(this.tabs[this.tabInd])
    },
    setHash(hash) {
      this.$router
        .replace({
          hash
        })
        .catch(() => {})
    },
    changeType(type) {
      this.typeDeal = type
    },
    init() {
      const name = this.$router.history.current.hash.replace("#", "").split("&")[0]

      const ind = this.tabs.indexOf(name)

      this.$nextTick(() => {
        if (ind >= 0) {
          this.tabInd = ind
        } else {
          this.tabInd = 0
        }
        this.setHash(this.tabs[this.tabInd])
      })
    },
    getResponseData(respData) {
      return {
        amount: this.form.price,
        deal_id: respData.dealId,
        slug: respData.slug
      }
    },
    addError(message) {
      this.$store.dispatch("setSnackbar", {
        color: "error",
        text: message,
        toggle: true
      })
      this.$router.replace({ name: "lk.wallet" })
    },
    validateBeforeSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.err = {}

          this.goPay()
        }
      })
    },
    sendPaymentData() {
      let data = {}
      if (this.$route.params.response) {
        data = this.getResponseData(this.$route.params.response)
      } else {
        data.amount = this.form.price
      }
      axios
        .post("/api/v1/finance-operations/payture/store", data)
        .then((resp) => {
          if (resp.data.result) {
            window.location = resp.data.data.url
          } else {
            this.$store.dispatch("setSnackbar", {
              color: "error",
              text: "Произошла ошибка, попробуйте позднее",
              toggle: true
            })
          }
        })
        .catch((err) => {
          this.printErrorOnConsole(error)
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: "Произошла ошибка, попробуйте позднее",
            toggle: true
          })
        })
        .then(() => {
          this.loading = false
        })
    },
    goPay() {
      if (this.form.paymenttype == "card") {
        this.sendPaymentData()
      } else {
        let res = this.form.price + ""
        this.$parent.getBillPay({
          price: parseInt(res.replace(/ /g, ""), 10)
        })
      }
    }
  }
}
</script>
<style>
.v-input.v-text-field .v-input__control .v-input__slot {
  background-color: rgba(47, 192, 110, 0.1) !important;
}
.payment__element-title[data-v-45d6515a]::before {
  display: none;
}
.payment__element-title[data-v-45d6515a]::after {
  display: none;
}
.payment__element {
  background: rgba(47, 192, 110, 0.1);
  border-radius: 5px !important;
}
.v-input.v-text-field .v-input__control .v-input__slot {
  border: 0px solid rgba(47, 192, 110, 0.1) !important;
  height: 40px;
  width: 296px;
}

.v-input.v-text-field.v-input--is-focused .v-input__control .v-input__slot {
  border: 1px solid #888888 !important;
}
</style>
<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
.wallet {
  font: $font-regular;
  &__form {
    display: flex;
    flex-direction: column;
    margin-bottom: 35px;
  }
  &__title {
    font: $font-medium;
    font-size: 32px;
    color: $color-base-dark;
    margin-bottom: 40px;
    @media (max-width: 420px) {
      font-size: 28px;
      align-self: center;
      margin-bottom: 30px;
    }
  }
  &__balance {
    align-self: flex-start;
    margin-bottom: 20px;
    @media (max-width: 420px) {
      align-self: center;
    }
  }
  &__payment {
    margin-bottom: 55px;
    @media (max-width: 420px) {
      margin-bottom: 20px;
    }
  }
  &__submit {
    @include big-rounded-btn;
    font-size: 14px;
    align-self: center;
    min-width: 232px;
  }
  .balance {
    background-color: $color-pale-green;
    color: $color-base-dark;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 20px;
    border-radius: 22px;
    &__icon {
      color: $color-base-dark;
    }
    &__value {
      font: $font-medium;
      font-size: 12px;
      margin-left: 15px;
    }
  }
}

.payment {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 280px;
  align-self: center;
  @media (max-width: 420px) {
    width: 230px;
  }
  &__title {
    font-size: 14px;
    color: $color-base-dark;
    text-align: center;
    margin-bottom: 10px;
  }
  &__section {
    width: 100%;
  }
  &__element {
    height: 41px;
    // box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
    border-radius: 10px;
    margin-bottom: 18px;
    &-wrapper {
      display: flex;
      align-items: center;
      position: relative;
    }
    &-value {
      display: none;
    }
    &-title {
      position: relative;
      width: 100%;
      height: 41px;
      display: flex;
      align-items: center;
      padding-left: 15px;
      padding-right: 15px;
      &::before {
        content: "";
        position: absolute;
        top: 50%;
        margin-top: -9px;
        left: -36px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid $color-base-accent;
        transition: all 0.1s 0.1s;
        @media (max-width: 420px) {
          left: -23px;
        }
      }
    }
    &-text {
      font-size: 14px;
      font-style: normal;
      @media (max-width: 420px) {
        font-size: 12px;
      }
    }
    .payment__element-value:checked + .payment__element-title::after {
      content: "";
      position: absolute;
      width: 10px;
      height: 10px;
      top: 50%;
      margin-top: -5px;
      left: -32px;
      background-color: $color-base-accent;
      border-radius: 50%;
      transition: all 0.1s 0.1s;
      @media (max-width: 420px) {
        left: -19px;
      }
    }
    &-icon {
      margin-right: 20px;
      & >>> svg {
        width: 18px !important;
      }
    }
  }
}
.price {
  position: relative;
  color: $color-text-gray;
  width: 100%;
  margin-bottom: 35px;

  &__symbol {
    position: absolute;
    right: 10px;
    top: 11px;
    font-size: 14px;
  }
}
</style>
