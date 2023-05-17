<template lang="pug">
section.password-reset
	h4.password-reset__header Сброс #[br] пароля
	.password-reset-success(v-if='success')
		p На вашу почту отправлено письмо с новым паролем.
		p Перейдите по ссылке из письма и выполните вход.

	.password-reset-form(v-else)
		form(@submit.prevent='ressetSubmit')
			div.form-group
									div.input-wrapper(:class="{'errors' : (errors.has('email') || err['email'])}")
											input(
													placeholder="Введите email"
													v-model="email"
													data-vv-as='Email'
													data-vv-name='email'
													v-validate=`'required|email'`
													autocomplete='off'
											).input-wrapper__input
											i.errors-list {{...errors.collect('email'), ...err['email']}}
			div.password-reset__desc
				p Если вы забыли пароль, введите email. Контрольная строка для смены пароля, а также ваши регистрационные данные, будут высланы вам по email.
			button(
				type='submit' 
				:disabled='loading'
			).password-reset__submit 
				span Отправить 
		button.password-reset__link(type="button" @click="$root.$emit('showForm','authorizationForm')") Войти
</template>

<script>
export default {
  data() {
    return {
      email: "",
      loading: false,
      err: {},
      success: false
    };
  },
  methods: {
    ressetSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.formSend();
          return;
        }
      });
    },
    formSend() {
      this.err = {};
      this.loading = true;

      axios
        .post("/api/v1/user/passwordreset", { email: this.email })
        .then(resp => {
          this.loading = false;
          if (resp.data.result) {
            this.success = true;
          } else if (resp.data.error) {
            this.err = resp.data.error;
          } else {
            console.log("%cResponse not valid: %O", "color:orange;", resp.data);
          }
        })
        .catch(error => {
          this.printErrorOnConsole(error);
          this.loading = false;
        });
    },
    change() {
      this.err = {};
    }
  }
};
</script>

<style lang="scss">
@import "~styleguide";
@import "~mixins";

.form-group {
  margin-bottom: 26px;
  @media (max-width: 576px) {
    margin-bottom: 22px;
  }
}
@mixin hamster-field {
  background: $color-base-light;
  border-radius: 6px;
  border: 1px solid $color-border-gray;
  padding: 0 12px;
  min-height: 43px;
  display: flex;
  align-items: center;
}
.input-wrapper {
  position: relative;
  &__input {
    @include hamster-field;
    width: 100%;
    padding-right: 20px;
    background-color: #f6f6f6;
    &::placeholder {
      color: #707070;
    }
    &:active,
    &:focus {
      box-shadow: none;
      border-color: $color-base-accent;
      background-color: #ffffff;
    }
  }
  &.errors {
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
}
.password-reset {
  &__header {
    color: $color-text-bright;
    font: $font-medium;
    font-size: 32px;
    align-self: flex-start;
    margin-bottom: 54px;
    @include media-breakpoint-down(sm) {
      font-size: 26px;
      line-height: 36px;
    }
  }
  &__desc {
    display: flex;
    font: $font-regular;
    font-size: 12px;
    line-height: 15px;
    color: #000000;
    text-align: left;
    padding-left: 20px;
    margin-top: 15px;
    @include media-breakpoint-down(sm) {
      font-size: 10px;
      line-height: 13px;
    }
  }
  &__submit {
    @include big-rounded-btn;
		@include btnHov($color-hover-button);
    @include media-breakpoint-down(sm) {
      width: 100%;
    }
    margin: 82px auto 0;
  }
  &__link {
    color: $color-text-bright;
    font: $font-semibold;
    font-size: 14px;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
    margin: 28px auto 10px;
    display: flex;
  }
}
</style>
