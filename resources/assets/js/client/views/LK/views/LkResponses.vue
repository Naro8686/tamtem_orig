<template lang="pug">
.responses
	//- h3.responses--title Мои предложения
	section.responses-filter
		ul.responses-filter__list
			li.responses-filter__item
				button.responses-filter__btn
					span.responses-filter__txt Все предложения
			//li.responses-filter__item.active
				button.responses-filter__btn
					span.responses-filter__txt Ожидание
					span.responses-filter__count.responses-filter__count--waiting +2
			//li.responses-filter__item
				button.responses-filter__btn
					span.responses-filter__txt Завершенные
					span.responses-filter__count.responses-filter__count--done +2
	Loader(v-if='this.loading')
	ul.responses-list(v-if="responsesList.length")
		li.responses-list--item(v-for="response in responsesList")
			response-card(:response='response' @leaveDeal="leaveDeal" @reloadResponses="getResponses")
	.responses-empty(v-else) У вас нет откликов
</template>
<script>
import ResponseCard from "../components/responsecard";
import { mapFields } from "vee-validate";

export default {
  components: {
    ResponseCard
  },
  data() {
    return {
      loading: false,
      responsesList: []
    };
  },
  beforeMount() {
    this.getResponses();
  },
  mounted() {
    if (this.$route.query.error) {
      this.addError(this.$route.query.error);
    }
  },
  computed: {
    organizationId() {
      return this.$root.profile.company.id;
    }
  },
  methods: {
    addError(message) {
      this.$store.dispatch("setSnackbar", {
        color: "error",
        text: message,
        toggle: true
      });
      this.$router.replace({ name: "lk.responses" });
    },
    leaveDeal(id) {
      axios
        .post(`/api/v1/deals/user/responses/${id}/finish`)
        .then(result => {
          if (result.data.result == true) {
            this.getResponses();
          }
        })
        .catch(err => {
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: `Произошла ошибка, попробуйте позднее`,
            toggle: true
          });
        });
    },
    getResponses() {
      this.loading = true;
      axios
        .get("/api/v1/deals/user/responses")
        .then(result => {
          if (result.data.result) {
            this.responsesList = this.processedData(result.data.data.items);
          }
        })
        .catch(err => {})
        .then(() => {
          this.loading = false;
        });
    },
    getAnswers(questions) {
      return Object.assign(
        {},
        ...Object.keys(questions).map(item => {
          return {
            [item]: {
              name: questions[item].slug,
              header: questions[item].header,
              value: questions[item].question
            }
          };
        })
      );
    },
    //  org_id == user.organization_id -
    getResponseStatus(status, winner_id) {
      let result;
      if (
        (status === "waiting_winner" &&
          (!winner_id || winner_id !== this.organizationId)) ||
        status === "moderation"
      ) {
        result = "pending";
      }
      if (status === "waiting_payment" && winner_id == this.organizationId) {
        result = "chosen";
      }
      if (status === "finished" && winner_id == this.organizationId) {
        result = "payed";
      }
      if (
        (status === "finished" &&
          (!winner_id || winner_id !== this.organizationId)) ||
        status === "banned"
      ) {
        result = "closed";
      }

      return result;
    },
    transformDate(dateObj) {
      if (dateObj) {
        let [year, month, day] = dateObj.date.split(" ")[0].split("-");
        return `${day}.${month}.${year}`;
      } else {
        return null;
      }
    },
    getUser(owner, organization) {
      let result;
      if (owner) {
        result = {
          company: organization ? organization.name : null,
          companyDescription: organization ? organization.org_products : null,
          userId: owner.id,
          userName: owner.name,
          userPhoto: owner.photo,
          userMail: owner.email,
          userPhone: owner.phone
        };
      }
      return result;
    },
    processedData(rawResponses) {
      return rawResponses.map(item => {
        return {
          dealId: item.deal.id,
          dealName: item.deal.name,
          responseId: item.id,
          status: this.getResponseStatus(
            item.trading_status,
            item.deal.winner_id
          ),
          files: item.deal.files,
          budget_to: item.deal.budget_to,
          budget_from: item.deal.budget_from,
          budget_with_nds: item.deal.budget_with_nds,
          priceOffer: item.price_offer,
          is_shipping_included: item.is_shipping_included,
          dealPeriod: item.deal.date_of_event,
          isPayed: item.deal.is_payed,
          isReadedByMe: item.is_readed_owner_response,
          deadlinePayment: this.transformDate(
            item.deal.winner_wating_payment_at || null
          ),
          summ: item.deal.commission,
          paymentslug: item.deal.slug_payment_type,
          answers: this.getAnswers(item.deal.questions),
          user: this.getUser(item.deal.owner, item.deal.organization)
        };
      });
    }
  }
};
</script>
<style lang="scss">
// это публичное пространство - для кастомизации модального окна. эти стили в закрытом пространстве не применяются. когда-нибудь вынесу правила для разных окон в отдельный scss и импортирую в открытую область в App
// $color-order-input-placeholder: #D1D1D1;
// $danger: #ff564b;
// @mixin hamster-field {
// 	background: #fff;
// 	border-radius: 4px;
// 	border: 1px solid #ececec;
// 	padding: 0 12px;
// 	min-height: 43px;
// 	display: flex;
// 	align-items: center;
// 	@media(max-width: 768px) {
// 		min-height: 40px;
// 		font-size: 14px;
// 	}
// 	@media(max-width: 425px) {
// 		min-height: 36px;
// 		font-size: 13px;
// 	}
// }
// .modal-response {
//   font-family: "Montserrat", sans-serif;
// 	font-weight: 400;
//   @media (max-width: 320px) {
//     padding: 0 !important;
//   }
//   .modal-header {
//     .close {
//       font: 400 20px/24px "Montserrat", sans-serif;
//       background: #cecece;
//       display: flex;
//       justify-content: center;
//       align-items: center;
//       transform: none;
//       text-shadow: none;
//       font-weight: 300;
//       border-radius: 6px;
//       min-width: 24px;
//       min-height: 24px;
//       color: #fff;
//       &::before,
//       &::after {
//         display: none;
//       }
//       &:hover {
//         background-color: #27d066;
//       }
//     }
//   }

//   .modal-dialog {
//     @media (min-width: 720px) {
//       width: 457px;
//     }
//     @media (min-width: 576px) {
//       margin-top: 100px;
//     }
//     @media (max-width: 320px) {
//       margin: 0;
//     }
//   }
//   .modal-content {
//     border-radius: 10px;
//     @media (max-width: 320px) {
//       border: none;
//       border-radius: 0;
//     }
//   }
//   .modal-body {
//     @media (min-width: 720px) {
//       padding: 0 86px;
//     }
//     @media (max-width: 320px) {
//       padding: 0 42px;
//     }
//   }
//   .response-modal-body {
//     min-height: 490px;
//   }
//   /deep/ .input-wrapper {
//     position: relative;
//     &__input {
//       @include hamster-field;
//       font-size: 14px;
//       line-height: 19px;
//       width: 100%;
//       padding-right: 26px;
//       &::placeholder {
//         color: $color-order-input-placeholder;
//         opacity: 1;
//       }
//     }
//     &__action {
//       position: absolute;
//       height: 20px;
//       top: calc(50% - 10px);
//       right: 10px;
//       display: flex;
//       align-items: center;
//       button {
//         outline: none;
//       }
//     }
//     &.errors {
//       .errors-list {
//         margin: 5px 0;
//         padding: 0px 10px;
//         font-weight: 400;
//         font-style: normal;
//         font-size: 10px;
//         color: $danger;
//         display: block;
//       }
//       input,
//       textarea {
//         border: 1px solid $danger;
//       }
//     }
//   }
// }
</style>
<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";

.responses {
  padding-bottom: 20px;
  &--title {
    text-align: left;
    font: $font-medium;
    font-size: 22px;
    color: $color-base-dark;
    line-height: 32px;
    margin-bottom: 10px;
  }
  &-list {
    list-style-type: none;
    &--item {
      &:not(:last-child) {
        margin-bottom: 15px;
      }
    }
  }
}
.responses-filter {
  margin-bottom: 30px;
  &__list {
    list-style: none;
    display: flex;
    align-items: center;
    position: relative;
    padding-bottom: 5px;
    &::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      height: 1px;
      background-color: $color-base-accent;
      @media (max-width: 425px) {
        display: none;
      }
    }
    @media (max-width: 425px) {
      flex-direction: column;
      align-items: flex-start;
    }
  }
  &__item {
    &:not(:last-child) {
      margin-right: 30px;
      @media (max-width: 500px) {
        margin-right: 20px;
      }
      @media (max-width: 425px) {
        margin-bottom: 10px;
      }
    }
  }
  &__txt {
    font-size: 14px;
    line-height: 19px;
    @media (max-width: 500px) {
      font-size: 12px;
    }
    @media (max-width: 425px) {
      font-size: 14px;
    }
  }
  &__item.active {
    .responses-filter__txt {
      position: relative;
      &::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 3px;
        background-color: $color-base-accent;
        top: calc(100% + 7px);
        left: 0;
        @media (max-width: 500px) {
          top: calc(100% + 6px);
        }
        @media (max-width: 425px) {
          top: calc(100% + 1px);
          height: 2px;
        }
      }
    }
  }
  &__btn {
    display: flex;
    align-items: center;
  }
  &__count {
    margin-left: 5px;
    min-width: 42px;
    height: 26px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 15px;
    font-size: 18px;
    @media (max-width: 500px) {
      font-size: 14px;
      min-width: 34px;
      height: 24px;
    }
    @media (max-width: 425px) {
      font-size: 12px;
      min-width: 30px;
      height: 22px;
    }
    &--waiting {
      background: #fff8e6;
      color: #f7c849;
    }
    &--done {
      background: #d4f5de;
      color: $color-base-accent;
    }
  }
}
</style>