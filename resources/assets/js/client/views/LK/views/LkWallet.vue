<template lang="pug">
.card.profile-card.mb-5
	.card-body
		section.wallet(v-if='profile')
			form.wallet__form.form-wallet(@submit.prevent='validateBeforeSubmit')
				h3.wallet__title Мой кошелёк
					//- span.wallet__history(v-if="!modal")
						router-link(:to="{name: 'lk.wallet.history'}") История платежей
				div.wallet__balance.balance
					feather(type="credit-card").balance__icon
					span.balance__value {{profile.profile.ballance.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ")}} руб.
				section.wallet__payment.payment
					h5.payment__title Введите сумму оплаты
					.payment__price.price
						v-text-field(
							tabindex=1
							v-model='form.price'
							solo
							:light='true'
							placeholder='Сумма'
							data-vv-as='сумма'
							data-vv-name='price'
							v-validate=`'required|numeric'`
							:error-messages=`err.price ? err.price : errors.collect('price')`
						).price__field
						.price__symbol &#8381;
					h5.payment__title Выберите способ оплаты
					section.payment__section
						p.payment__element
							label.payment__element-wrapper
								input.payment__element-value(
									tabindex=2
									name="paymenttype"
									v-model="form.paymenttype"
									type="radio"
									value="bank-transaction"
								)
								p.payment__element-title
									i.payment__element-icon
										icon-bank
									i.payment__element-text Банковский перевод
						//-p.payment__element
							label.payment__element-wrapper
								input.payment__element-value(
									tabindex=3
									name="paymenttype"
									v-model="form.paymenttype"
									type="radio"
									value="card"
								)
								p.payment__element-title
									i.payment__element-icon
										icon-card
									i.payment__element-text Оплата картой
				div.wallet__submit
					button(
						tabindex=4
						type='submit' 
						:disabled='loading'
					).wallet__submit-btn
						span {{ form.paymenttype=='bank-transaction' ? "Выписать счёт" : "Оплатить"}} 
						b-spinner(v-if='loading' label='Загрузка...')
</template>
<script>
import mixin from "../../../mixins/global";
import { Validator } from "vee-validate";
import vue from "vue";

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
});
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
      paylink: null
    };
  },
  mounted() {
    if (this.$route.query.error) {
      this.addError(this.$route.query.error);
	}
  },
  computed: {
    ballance() {
      return this.profile.profile.ballance || 0;
    }
  },
  methods: {
	getResponseData(respData){
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
      });
      this.$router.replace({ name: "lk.wallet" });
    },
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.err = {};

          this.goPay();
        }
      });
    },
    sendPaymentData() {
		let data = {}
		if (this.$route.params.response){
			data = this.getResponseData(this.$route.params.response)
			}else{
				data.amount = this.form.price
			}
      axios
        .post("/api/v1/finance-operations/payture/store", data)
        .then(resp => {
          if (resp.data.result) {
            window.location = resp.data.data.url;
          } else {
            this.$store.dispatch("setSnackbar", {
              color: "error",
              text: "Произошла ошибка, попробуйте позднее",
              toggle: true
            });
          }
        })
        .catch(err => {
          this.printErrorOnConsole(error);
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: "Произошла ошибка, попробуйте позднее",
            toggle: true
          });
        })
        .then(() => {
          this.loading = false;
        });
    },
    goPay() {
      if (this.form.paymenttype == "card") {
        this.sendPaymentData();
      } else {
        let res = this.form.price + "";
        this.$parent.getBillPay({
          price: parseInt(res.replace(/ /g, ""), 10)
        });
      }
    }
  }
};
</script>
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
		box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
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
			font-size: 16px;
			font-style: normal;
			@media (max-width: 420px) {
				font-size: 12px;
			}
		}
		.payment__element-value:checked + .payment__element-title::after {
			content: '';
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
				width: 28px !important;
			}
		}
	}
}
.price {
	position: relative;
	color: $color-text-gray;
	width: 100%;
	margin-bottom: 35px;
	& /deep/ .v-text-field .v-input__control .v-input__slot {
		background-color: $color-base-light !important;
  	height: 41px;
		border-radius: 10px;
		border: none;
		box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
		padding-right: 25px;
	}
	&__symbol {
		position: absolute;
		right: 10px;
		top: 11px;
		font-size: 14px;
	}
}
</style>
