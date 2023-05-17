<template lang="pug">
	form(@submit.prevent="prepareDataToSend()").response-report
		template(v-if="!sended")
			h2.response-report__title Сделка #[br] не состоялась
			label.response-report__label Опишите, почему сделка не состоялась
			.input-wrapper.textarea-wrapper(:class="{'errors':errors.has('text')}")
				textarea.response-report__text(
					placeholder="Комментарий"
					v-model="text"
					data-vv-as="Текст"
					data-vv-name="text"
					v-validate=`'required'`
				)
				i.errors-list(v-if="errors.has('text')") {{...errors.collect('text')}}
			div.input-wrapper.response-report__phone(:class="{'errors' : errors.has('phone')}")
				input(
					type="text" 
					placeholder="+7 (___) ___ __ __"
					data-vv-as="Телефон"
					data-vv-name="phone"
					v-model="phone"
					v-validate=`'mobilePhone'`
				).input-wrapper__input
				i.errors-list(v-if="errors.has('phone')") {{...errors.collect('phone')}}
			button.response-report__submit(type="submit") Отправить заявку
		template(v-if="sended")
			div.success-pic.response-report__success-pic
				feather(type="check")
			h2.response-report__title.response-report__title--success Отправлено #[br] на рассмотрение
			p.response-report__success-message Ваш запрос отправлен на рассмотрение. Если возникнут вопросы, менеджер tamtem.ru с вами свяжется.
</template>

<script>
import { mask } from "vue-the-mask";
import { Validator } from "vee-validate";
export default {
  props: {
    id: {
      type: [Number, String]
    }
  },
  directives: {
    mask
  },
  data: () => ({
    sended: false,
    text: "",
    phone: null
  }),
  created() {
    Validator.extend("mobilePhone", {
      field: field => `Поле ${field} имеет ошибочный формат`,
      validate: value => {
        return value.replace(/[^0-9]/gi, "").replace(/^[78]/, "").length == 10;
      }
    });
  },
  methods: {
    prepareDataToSend() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.formSend();
        }
      });
    },
    formSend() {
      let data = {
        deal_id: this.id,
        comment: this.text
      };
      if (this.phone) {
        data.phone = this.phone.replace(/[^0-9]/gi, "").replace(/^[78]/, "");
      }
      axios
        .post("/api/v1/send/support/dealfailed", data)
        .then(response => {
          if (response.data.result == true) {
            this.sended = true;
          } else {
            this.$store.dispatch("setSnackbar", {
              color: "error",
              text: "Произошла ошибка, попробуйте позднее",
              toggle: true
            });
          }
        })
        .catch(err => {
          this.printErrorOnConsole(err);
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: "Произошла ошибка, попробуйте позднее",
            toggle: true
          });
        });
    }
  }
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
.response-report {
  font-family: $font-family;
  display: flex;
  flex-direction: column;
  align-items: center;
  &__title {
    font-size: 26px;
    font-weight: 500;
    line-height: 34px;
    text-align: center;
    margin-bottom: 50px;
    &--success {
      margin-bottom: 30px;
    }
  }
  &__label {
    font-size: 12px;
    font-weight: 400;
    line-height: 19px;
    color: $color-text-gray;
    margin-bottom: 5px;
    align-self: flex-start;
  }
  &__text {
    width: 100%;
    height: 170px;
    border: 1px solid $color-border-gray;
    background-color: #f6f6f6;
    resize: none;
    border-radius: 4px;
    padding: 13px;
  }
  &__phone {
    width: 100%;
    margin-bottom: 47px;
  }
  &__submit {
    width: 183px;
    height: 43px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 35px;
    border: solid 1px $color-base-accent;
    background-color: $color-base-accent;
    color: #ffffff;
    font-weight: 500;
    font-size: 14px;
  }
  &__success-pic {
    margin-top: 60px;
  }
  &__success-message {
    text-align: center;
    font-size: 14px;
    line-height: 19px;
    font-weight: 400;
  }
}
.success-pic {
  border-radius: 50%;
  border: 2px solid $color-base-accent;
  width: 72px;
  height: 72px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 40px;
  & /deep/ svg {
    width: 30px;
    height: auto;
    stroke: $color-base-accent;
    stroke-width: 3;
  }
}
.input-wrapper {
  position: relative;
  &__input {
    @include hamster-field;
    font-size: 14px;
    line-height: 19px;
    width: 100%;
    padding-right: 26px;
    background-color: #f6f6f6;
    &::placeholder {
      color: $color-text-gray;
    }
  }
  &.textarea-wrapper {
    margin-bottom: 26px;
		width: 100%;
  }
  &__action {
    position: absolute;
    height: 20px;
    top: calc(50% - 10px);
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
    }
    input,
    textarea {
      border: 1px solid $danger;
    }
  }
}
</style>