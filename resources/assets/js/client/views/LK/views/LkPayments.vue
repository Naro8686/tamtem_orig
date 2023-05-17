<template lang="pug">
main
  section#panel
    .custom-container
      .panel
        .panel__tabs
          ul.panel__tabs-list
            li.panel__tabs-item
              a.panel__tabs-item-link.panel__tabs-item-link_active(href="#") Заказы
            li.panel__tabs-item
              a.panel__tabs-item-link(href="#") Предложения
        .panel__widgets
<!--          .panel__auth-->
<!--            a.panel__auth-link(href='#') Войти / Зарегистрироваться-->
          .panel__favorites
            a(href="#")
              svg(
                width="18",
                height="24",
                viewBox="0 0 18 24",
                fill="none",
                xmlns="http://www.w3.org/2000/svg"
              )
                path(
                  fill-rule="evenodd",
                  clip-rule="evenodd",
                  d="M7.50031 19.5L15.0006 24V6C15.0006 5.20435 14.6845 4.44129 14.1219 3.87868C13.5593 3.31607 12.7962 3 12.0005 3H3.00013C2.20444 3 1.44135 3.31607 0.878716 3.87868C0.316084 4.44129 0 5.20435 0 6V24L7.50031 19.5ZM1.50006 21.351L7.50031 17.751L13.5006 21.351V6C13.5006 5.60218 13.3425 5.22064 13.0612 4.93934C12.7799 4.65804 12.3983 4.5 12.0005 4.5H3.00013C2.60228 4.5 2.22074 4.65804 1.93942 4.93934C1.6581 5.22064 1.50006 5.60218 1.50006 6V21.351Z",
                  fill="#2FC06E"
                )
                path(
                  d="M18.001 21L16.5009 20.1V3C16.5009 2.60218 16.3428 2.22064 16.0615 1.93934C15.7802 1.65804 15.3987 1.5 15.0008 1.5H3.40234C3.66565 1.04395 4.04437 0.665247 4.50043 0.401943C4.95649 0.138639 5.47383 1.33777e-05 6.00045 0L15.0008 0C15.7965 0 16.5596 0.316071 17.1222 0.87868C17.6849 1.44129 18.001 2.20435 18.001 3V21Z",
                  fill="#2FC06E"
                )
          button.panel__account
            .panel__account-icon
              svg(
                width="22",
                height="22",
                viewBox="0 0 22 22",
                fill="none",
                xmlns="http://www.w3.org/2000/svg"
              )
                path(
                  fill-rule="evenodd",
                  clip-rule="evenodd",
                  d="M20.1667 22C20.1667 22 22 22 22 20.1667C22 18.3333 20.1667 12.8333 11 12.8333C1.83333 12.8333 0 18.3333 0 20.1667C0 22 1.83333 22 1.83333 22H20.1667ZM1.8425 20.2693V20.2657V20.2693ZM1.87367 20.1667H20.1263C20.1349 20.1657 20.1435 20.1644 20.152 20.163L20.1667 20.1593C20.1648 19.7083 19.8843 18.3517 18.6413 17.1087C17.446 15.9133 15.1965 14.6667 11 14.6667C6.80167 14.6667 4.554 15.9133 3.35867 17.1087C2.11567 18.3517 1.837 19.7083 1.83333 20.1593C1.84675 20.1619 1.8602 20.1644 1.87367 20.1667ZM20.1593 20.2693V20.2657V20.2693ZM11 9.16667C11.9725 9.16667 12.9051 8.78036 13.5927 8.09272C14.2804 7.40509 14.6667 6.47246 14.6667 5.5C14.6667 4.52754 14.2804 3.59491 13.5927 2.90727C12.9051 2.21964 11.9725 1.83333 11 1.83333C10.0275 1.83333 9.09491 2.21964 8.40728 2.90727C7.71964 3.59491 7.33333 4.52754 7.33333 5.5C7.33333 6.47246 7.71964 7.40509 8.40728 8.09272C9.09491 8.78036 10.0275 9.16667 11 9.16667ZM16.5 5.5C16.5 6.95869 15.9205 8.35764 14.8891 9.38909C13.8576 10.4205 12.4587 11 11 11C9.54131 11 8.14236 10.4205 7.11091 9.38909C6.07946 8.35764 5.5 6.95869 5.5 5.5C5.5 4.04131 6.07946 2.64236 7.11091 1.61091C8.14236 0.579463 9.54131 0 11 0C12.4587 0 13.8576 0.579463 14.8891 1.61091C15.9205 2.64236 16.5 4.04131 16.5 5.5Z",
                  fill="#2FC06E"
                )
              span.panel__accoutn-alerts 3
            .panel__account-name
              | Олег Иванов
          .panel__account-dropdown.custom-dropdown(role="list")
            ul.panel__account-dropdown-list
              li.panel__account-dropdown-item
                a.account-dropdown-link(href="#")
                  svg(
                    width="24",
                    height="20",
                    viewBox="0 0 24 20",
                    fill="none",
                    xmlns="http://www.w3.org/2000/svg"
                  )
                    path(
                      d="M11.7647 0L0 10.5882H3.52941V20H20V10.5882H23.5294L11.7647 0ZM11.7647 6.76471C12.4667 6.76471 13.14 7.04359 13.6365 7.54001C14.1329 8.03643 14.4118 8.70972 14.4118 9.41177C14.4118 10.1138 14.1329 10.7871 13.6365 11.2835C13.14 11.7799 12.4667 12.0588 11.7647 12.0588C11.0627 12.0588 10.3894 11.7799 9.89295 11.2835C9.39653 10.7871 9.11765 10.1138 9.11765 9.41177C9.11765 8.70972 9.39653 8.03643 9.89295 7.54001C10.3894 7.04359 11.0627 6.76471 11.7647 6.76471ZM11.7647 14.1176C13.5294 14.1176 17.0588 15 17.0588 16.7647V17.6471H6.47059V16.7647C6.47059 15 10 14.1176 11.7647 14.1176Z",
                      fill="#18A0FB"
                    ) Личный кабинет
              li.panel__account-dropdown-item
                a.account-dropdown-link(href="#")
                  svg(
                    width="21",
                    height="18",
                    viewBox="0 0 21 18",
                    fill="none",
                    xmlns="http://www.w3.org/2000/svg"
                  )
                    path(
                      d="M13.4062 0V2.57143H3.28125V15.4286H13.4062V18H0.75V0H13.4062ZM15.9375 5.14286L21 9L15.9375 12.8571V10.2857H5.8125V7.71429H15.9375V5.14286Z",
                      fill="#2FC06E"
                    ) Выход
  section#slider-categories
    .custom-container
      .slider-categories
        .owl-carousel.slider-categories__carousel
          .item
            a.sliders-categories__link(href="#") Продукты питания
          .item
            a.sliders-categories__link(href="#") Детский мир
          .item
            a.sliders-categories__link(href="#") Одежда, обувь, аксесуары
          .item
            a.sliders-categories__link(href="#") Всё для дома
          .item
            a.sliders-categories__link(href="#") Сельское хозяйство
          .item
            a.sliders-categories__link(href="#") Электрооборудование
          .item
            a.sliders-categories__link(href="#") Строительные
          .item
            a.sliders-categories__link(href="#") Строительные Строительные
          .item
            a.sliders-categories__link(href="#") Электрооборудование
  section.content
    .custom-container
      ul.breadcrumbs
        li Заказы
        li Олег Иванов
        li Мои Объявления
      .content__block
        .sidebar
          .name_card
            p.name_card_name Олег Иванов
            img(src="images/logo.png")
            .name_card_info
              p.name_card_info_bold_text Восток-М
              p.name_card_info_default_text Нижний Новгород
              p.name_card_info_default_text Россия
          .sidebar_menu
            ul
              li.active
                .sidebar_text
                  img(src="images/paid services.png")
                  span Мои объявления
              li
                .sidebar_text
                  img(src="images/paid services.png")
                  span Отклики
                .sidebar_circle 2
              li
                .sidebar_text
                  img(src="images/paid services.png")
                  span Закладки
              li
                .sidebar_text
                  img(src="images/paid services.png")
                  span Сообщения
                .sidebar_circle 2
              li
                .sidebar_text
                  img(src="images/paid services.png")
                  span Кошелёк
                .sidebar_money 0 ₽
              li
                .sidebar_text
                  img(src="images/paid services.png")
                  span Платные услуги
              li
                .sidebar_text
                  img(src="images/paid services.png")
                  span Настройки
        .main-content
          .tabs
            .maintabs
              button.maintab.active Кошелёк
            .subtabs
              button.subtab Баланс
              button.subtab.active История платежей
          .content
            .payments_button
              a.payments_filter_button
                | выбрать период
                svg(
                  width="9",
                  height="5",
                  viewBox="0 0 9 5",
                  fill="none",
                  xmlns="http://www.w3.org/2000/svg"
                )
                  path(
                    d="M4.49294 4.49848C4.343 4.49877 4.19769 4.44649 4.08223 4.3507L0.231828 1.13807C0.100775 1.02901 0.0183608 0.872293 0.0027155 0.702393C-0.0129298 0.532492 0.0394754 0.363328 0.148402 0.232113C0.257329 0.100899 0.413856 0.0183834 0.583547 0.00271886C0.753238 -0.0129457 0.922194 0.0395238 1.05325 0.148585L4.49294 3.0271L7.93264 0.25139C7.99828 0.198018 8.07381 0.158161 8.15488 0.13411C8.23596 0.110059 8.32098 0.102288 8.40507 0.111245C8.48915 0.120201 8.57064 0.145707 8.64485 0.186298C8.71906 0.226889 8.78452 0.281764 8.83748 0.347768C8.89625 0.413833 8.94076 0.491339 8.96822 0.575428C8.99568 0.659518 9.0055 0.74838 8.99707 0.836446C8.98864 0.924513 8.96213 1.00989 8.91921 1.08722C8.87629 1.16455 8.81788 1.23218 8.74764 1.28585L4.89723 4.38925C4.77846 4.4699 4.63611 4.50836 4.49294 4.49848Z",
                    fill="#888888"
                  )
              .payments_filter_menu
                ul
                  li.active
                    a(href="#") За неделю
                  li
                    a(href="#") За неделю
                  li
                    a(href="#") За неделю
                  li
                    a(href="#") За неделю
                  li
                    a(href="#") За неделю
            .payments_table
              .payments_table_row_top
                p Дата
                p Назначение
                p Описание
                p СУММА, ₽
              PaymentItem(
                v-for="(item, index) in paymentsList",
                :item="item",
                :key="index"
              )
</template>
<script>
import mixin from "../../../mixins/global";
import PaymentItem from "./../components/PaymentItem";
export default {
  components: {
    PaymentItem
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
      paymentsList: null,
      err: {},
      form: {
        price: this.$route.params.response
          ? this.$route.params.response.summ
          : null,
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
    axios
      .post("/api/v1/finance-operations")
      .then((resp) => {
        console.log(resp);
        this.paymentsList = resp.data.data.data;
      })
      .catch((err) => {
        this.printErrorOnConsole(error);
        this.$store.dispatch("setSnackbar", {
          color: "error",
          text: "Произошла ошибка, попробуйте позднее",
          toggle: true
        });
      });
  },
  computed: {
    ballance() {
      return this.profile.profile.ballance || 0;
    }
  },
  methods: {
    getResponseData(respData) {
      return {
        amount: this.form.price,
        deal_id: respData.dealId,
        slug: respData.slug
      };
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
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.err = {};

          this.goPay();
        }
      });
    },
    sendPaymentData() {
      let data = {};
      if (this.$route.params.response) {
        data = this.getResponseData(this.$route.params.response);
      } else {
        data.amount = this.form.price;
      }
      axios
        .post("/api/v1/finance-operations/payture/store", data)
        .then((resp) => {
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
        .catch((err) => {
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
