<template lang="pug">
section.services
	h3.services__title Цены
	ul.row.services__list
		//- li(v-for='(item, ind) in servicesList' :key='item.id').col-md-4.services__item
		//- 	.servcard
		//- 		h4.servcard__name  {{item.name}}
		//- 		p.servcard__price {{priceFormat(item.cost)}}&nbsp;&#8381;
		//- 		p.servcard__desc {{item.description}}
		li.col-md-4.services__item
			.servcard
				h4.servcard__name Разместить заказ
				p.servcard__price 0  ₽
				p.servcard__desc Бесплатно! Создайте подробный заказ самостоятельно или с помощью личного менеджера.
		li.col-md-4.services__item
			.servcard
				h4.servcard__name  Предложение
				p.servcard__price 0  ₽
				p.servcard__desc Бесплатно! Поставщик может отправлять предложения на неограниченное количество заказов.
		li.col-md-4.services__item
			.servcard
				h4.servcard__name  Получение контактов
				p.servcard__price от 200 ₽
				p.servcard__desc В зависимости от средней суммы заказа. Поставщик оплачивает предоставление контактных данных закупщика только в случае, если закупщик выбрал поставщика и его предложение. Сумма оплаты за контакт закупщика видна при отправке предложения закупщику
</template>
<script>
export default {
  data() {
    return {
      tarifsList: [],
      servicesList: [],
      isTarifs: true,
      isServices: false
    };
  },
  filters: {
    countableDays(n) {
      return n % 10 == 1 && n % 100 != 11
        ? n + " день"
        : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)
        ? n + " дня"
        : n + " дней";
    }
  },
  // если пользователь авторизован - сразу перенаправляем его на страницу тарифов в ЛК
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (vm.$root.profile != null) {
        next({ name: "lk.tarifs" });
      }
    });
  },
  computed: {
    getLinkTarifs() {
      if (this.$root.isAuth)
        return "/api/v1/paymentservice/tariffsavailableforuser";
      return "/api/v1/paymentservice/tariffsavailableforall";
    },
    getLinkServices() {
      if (this.$root.isAuth)
        return "/api/v1/paymentservice/servicesavailableforuser";
      return "/api/v1/paymentservice/servicesavailableforall";
    }
  },
  watch: {
    "$root.isAuth"(val) {
      if (val) {
        this.$router.replace({ name: "lk.tarifs" });
      }
    }
  },
  methods: {
    getTarifs() {
      this.$parent.loading = true;
      axios
        .get(this.getLinkTarifs)
        .then(resp => {
          this.tarifsList = resp.data.data.data;
          this.tarifsList.sort((a, b) => {
            // if (a.is_active) {
            // 	return -1
            // } else
            if (a.is_promo) {
              return -1;
            } else if (a.cost > b.cost) {
              return 1;
            }
            return 0;
          });
        })
        .catch(error => {
          this.printErrorOnConsole(error);
        })
        .then(() => {
          this.$parent.loading = false;
        });
    },
    getServices() {
      this.$parent.loading = true;
      axios
        .get(this.getLinkServices)
        .then(resp => {
          this.servicesList = resp.data.data.data;
        })
        .catch(error => {
          this.printErrorOnConsole(error);
        })
        .then(() => {
          this.$parent.loading = false;
        });
    },
    toggle(type) {
      switch (type) {
        case "tarifs":
          this.isTarifs = true;
          this.isServices = false;
          break;
        case "services":
          this.isTarifs = false;
          this.isServices = true;
          break;
      }
    },
    dateEnd(dStart, dateDuration) {
      let endDate = new Date(dStart);
      endDate.setDate(endDate.getDate() + dateDuration);

      return endDate
        .toISOString()
        .slice(0, 10)
        .split("-")
        .reverse()
        .join(".");
    },
    activate() {
      axios
        .post(
          "/api/v1/paymentservice/subscriptions/payment_all_in_3_days/activate"
        )
        .then(resp => {
          if (resp.data.result == true) {
            this.$store.dispatch("setSnackbar", {
              color: "success",
              text: "Подарок активирован",
              toggle: true
            });
            this.$emit("profileUpdateEmit");
          } else {
            this.$store.dispatch("setSnackbar", {
              color: "error",
              text: resp.data.error,
              toggle: true
            });
          }
        })
        .catch(error => {
          this.printErrorOnConsole(error);
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: "Ошибка",
            toggle: true
          });
        });
    }
  },
  mounted() {
    this.getTarifs();
    this.getServices();
  }
};
</script>
<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
.tarifs {
  .tarifs-filter {
    margin-bottom: 1.5rem;
    .btn {
      padding-left: 2.5rem;
      padding-right: 2.5rem;
      border-color: $old-primary;
      color: $old-primary;
      background: #fff;

      &.active {
        background: #fff;
        border-color: $gray;
        color: $gray;
        &:focus {
          box-shadow: none;
        }
      }

      & + .btn {
        margin-left: 1.5rem;
      }
    }
  }
}
.centralized {
  float: right;
  margin-top: 0.7rem;
  font-size: 1.5rem;
}
.tarifs-list {
  list-style: none;
  padding: 0;

  @include media-breakpoint-down(md) {
    margin-right: 1rem;
  }
}
.service-item,
.tarif-item {
  position: relative;
  height: calc(100% - 5rem);
  box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0);
  border: solid 1px $light-gray;
  background-color: #fff;
  padding: 1rem;
  margin-bottom: 5rem;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
  @include hover-focus {
    box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
    transform: translateY(-0.1rem);
  }
}
.tarif-item {
  &.m-payment_all_in_3_days {
    .tarif-title {
      color: $success;
    }
  }
  &.m-payment_all_in {
    .tarif-title {
      color: $color-text-gray;
    }
  }
  &.m-payment_all_in_3_months {
    .tarif-title {
      color: $old-primary;
    }
  }
  &.m-payment_all_in_6_months {
    .tarif-title {
      color: $danger;
    }
  }
}
.service-item {
  .tarif-info {
    padding-bottom: 2.5rem;
    min-height: 15rem;
  }
}
.current-tarif {
  background-color: $old-primary;
  font-size: 1.4rem;
  font-weight: 500;
  color: #fff;
  height: 4.5rem;
  width: 90%;
  margin: 0 auto;
  text-align: center;
  line-height: 4.5rem;
  position: absolute;
  bottom: -4.5rem;
  left: 5%;

  &.active {

  }
}
.tarif-title {
  font-size: 1.6rem;
  font-weight: 500;
  text-align: left;
  margin: 1rem 0;
  font-weight: 500;
  line-height: 1;
  height: 4.8rem;
  overflow: hidden;
  padding: 0;
}
.tarif-price {
  font-size: 2rem;
  font-weight: 500;
  text-align: left;
  color: $body-color;
  line-height: 1;
  height: 2rem;
  overflow: hidden;
  margin-bottom: 2rem;
}
.tarif-info {
  border-top: 0.1rem dotted $gray;
  padding: 2.5rem 1rem 7.5rem;
  margin: 0 -1rem;
  font-size: 1.4rem;
  font-weight: normal;
  min-height: 26rem;
  display: flex;
  align-items: center;
  font-style: italic;
  color: $color-text-gray;

  p {
    margin: 0;
  }
}
.footer-info {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 7rem;
  padding: 1rem;
  text-align: center;
  font-size: 1.4rem;
  font-weight: normal;
  font-style: italic;
  color: $light;

  .btn {
    font-style: normal;
    font-size: 1.8rem;
    padding: 0.7rem 3rem;
  }
}
.current-tarif {
  background: transparent;
  height: auto;
  line-height: inherit;
  background-color: $old-primary;
  font-size: 1.6rem;
  font-weight: 500;
  color: #fff;
  height: 4.5rem;
  width: 90%;
  margin: 0 auto;
  text-align: center;
  line-height: 4.5rem;
  position: absolute;
  bottom: -4.5rem;
  left: 5%;
}
.free-tarif {
  height: auto;
  line-height: inherit;
  height: 4.5rem;
  width: 100%;
  margin: 0 auto;
  text-align: center;
  line-height: 4.5rem;
  position: absolute;
  bottom: -5rem;
  left: 0;
  padding: 0 1rem;

  .btn {
    font-size: 1.5rem;
    padding: 0.7rem 2.5rem;
  }
}
.services {
  font: $font-regular;
  font-size: 14px;
  color: $color-text-dark;
  &__title {
    font: $font-medium;
    font-size: 32px;
    color: $color-base-dark;
    margin-bottom: 46px;
  }
  &__list {
		list-style: none;
		box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
		padding-top: 25px;
		padding-left: 25px;
		padding-right: 25px;
		padding-bottom: 100px;
  }
  &__item {
    align-self: stretch;
    margin-bottom: 20px;
    min-height: 352px;
  }
}
.servcard {
  display: flex;
  height: 100%;
  flex-direction: column;
  align-items: center;
  padding: 32px 12px;
	border-radius: 10px;
	border: solid 1px #d6e1e7;
  // box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
  // border-left: 3px solid $color-base-accent;
  &__name {
    color: $color-base-dark;
    font: $font-semibold;
    font-size: 14px;
    line-height: 19px;
    min-height: 40px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    margin-bottom: 27px;
    text-align: center;
    padding: 0 5%;
  }
  &__price {
    background-color: $color-pale-green;
    color: $color-base-dark;
    font: $font-semibold;
    font-size: 17px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 40px;
    border-radius: 32px;
  }
  &__desc {
    text-align: center;
    margin-top: 30px;
    padding: 0 5%;
  }
}
</style>