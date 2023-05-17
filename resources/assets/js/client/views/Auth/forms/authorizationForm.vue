<template lang="pug">
section.signin-form
	h4.signin-form__header Войти
	form(@submit.prevent='loginSubmit').signin-form__form
		.form-group
			div.input-wrapper(:class="{'errors' : (errors.has('email') || err['email'])}")
				input(
					placeholder="Введите email"
					v-model="user.email"
					data-vv-as='Email'
					data-vv-name='email'
					v-validate=`'required|email'`
					autocomplete='off'
				).input-wrapper__input
				i.errors-list {{...errors.collect('email'), ...err['email']}}
		.form-group
			div.input-wrapper.input-wrapper--extended(:class="{'errors' : (errors.has('password') || err['password'])}")
				input(
					:type="isPassOpen ? 'text' :'password'" 
					placeholder="Пароль"
					v-model="user.password"
					data-vv-as='Пароль'
					data-vv-name='password'
					v-validate=`'required|min:6'`
				).input-wrapper__input
				i.errors-list {{...errors.collect('password'), ...err['password']}}
				div.input-wrapper__action
					button(type="button" @click="isPassOpen=!isPassOpen" :class="{'active' : isPassOpen }").input-wrapper__action-eye
		p.auth__check
				input(type="checkbox" id="login-member").auth__check-input
				label(for="login-member").auth__check-label 
					span Запомнить меня
		p.errors-list(v-if="err") {{err.message}}		
			a(v-if="err.error_code == 'register'" 
					href="javascript:void(0);"
					@click='resendMail'
				).signin-form__link Отправить письмо повторно			
		button(
			type='submit' 
			:disabled='loading'
		).auth__submit
			span Войти
	.signin-form__links
		a.signin-form__link(
			href="javascript:void(0);"
			@click="$root.$emit('showForm','passwordResetForm')") Забыли пароль?
		p.signin-form__text Или пройти
		a.signin-form__link(
			href="javascript:void(0);" 
			@click="$root.$emit('showForm','registrationForm')") регистрацию	 
</template>

<script>
import Cookies from "js-cookie";

export default {
  data() {
    return {
      isPassOpen: false,
      loading: false,
      user: {
        email: "",
        password: ""
      },
      err: {},
      msg: null
    };
  },
  props: {
    cls: String
  },
  methods: {
    loginSubmit() {
      this.yandexReachGoal("voity_on_form_auth");
      this.googleReachGoal("voity_on_form_auth");
      this.$validator.validateAll().then(result => {
        if (result) {
          this.formSend();
          return;
        }
      });
    },
    async formSend() {
      this.err = {};
      this.loading = true;

      const login = await Api.login(this.user);
      if (login.api_token) {
        this.$root.login();
        this.loading = false;
        this.err = {};
        this.$emit('close');
      } else {
        // if (login.status === 401) {
        this.errInit(login.error);
        this.loading = false;
      }
    },
    errInit(err) {
      Cookies.remove("api_auth");
      this.$root.profile = null;
      this.err = err;
      this.msg = null;
      this.loading = false;
    },
    resendMail() {
      axios
        .post("/api/v1/register/repeateregistermail", {
          email: this.user.email
        })
        .then(resp => {
          if (resp.data.result) {
            this.err = {};
            this.msg = "Письмо успешно отправлено, перейдите на почту.";
          } else {
            this.err = {};
            this.err.message = "Произошла ошибка, попробуйте позднее";
          }
        });
    }
  }
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";

$eye-active: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxOSIgaGVpZ2h0PSIxMS4wODMiIHZpZXdCb3g9IjAgMCAxOSAxMS4wODMiPiAgPHBhdGggaWQ9ImV5ZS01IiBkPSJNOS41MTIsNi41ODNhMTAuMzMsMTAuMzMsMCwwLDEsNy41MjQsMy42N0MxNS45MjcsMTEuNzA4LDEzLjMsMTQuNSw5LjUxMiwxNC41Yy0zLjUsMC02LjI3OC0yLjgtNy41LTQuMjgxQTEwLjQzMSwxMC40MzEsMCwwLDEsOS41MTIsNi41ODNaTTkuNTEyLDVDMy41Miw1LDAsMTAuMTg2LDAsMTAuMTg2czMuODI4LDUuOSw5LjUxMiw1LjljNi4xMjIsMCw5LjQ4OC01LjksOS40ODgtNS45UzE1LjYsNSw5LjUxMiw1Wk05LjUsNy4zNzVhMy4xNjcsMy4xNjcsMCwxLDAsMy4xNjcsMy4xNjdBMy4xNjcsMy4xNjcsMCwwLDAsOS41LDcuMzc1WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCAtNSkiIGZpbGw9IiMyZmMwNmUiLz48L3N2Zz4=";
$eye-inactive: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxOSIgaGVpZ2h0PSIxNC42MDUiIHZpZXdCb3g9IjAgMCAxOSAxNC42MDUiPiAgPHBhdGggaWQ9ImV5ZS04IiBkPSJNMTUuNTIsMi41NjMsMTIuODcyLDUuMDQ2YTEwLjQ2OSwxMC40NjksMCwwLDAtMy4zNi0uNTUzQzMuNTIsNC40OTIsMCw5LjY3OSwwLDkuNjc5YTE2LjM4NSwxNi4zODUsMCwwLDAsNC4wNzMsNC4wNjhsLTIuMywyLjMsMS4xMTksMS4xMTlMMTYuNjQsMy42ODJaTTEwLjc1Nyw3LjEzN2EzLjE1NiwzLjE1NiwwLDAsMC00LjE2OCw0LjEyMUw1LjIyMywxMi42MDlhMTQuMzg4LDE0LjM4OCwwLDAsMS0zLjIxNC0yLjksMTAuNDMyLDEwLjQzMiwwLDAsMSw3LjUtMy42MzYsOC44LDguOCwwLDAsMSwyLjA2Ni4yNDlaTTguNDc1LDEzLjAyLDEyLjUsOS4wNjdBMy4xNTksMy4xNTksMCwwLDEsOC40NzUsMTMuMDJaTTE5LDkuNjc5cy0zLjM2Niw1LjktOS40ODgsNS45YTguNjQxLDguNjQxLDAsMCwxLTMuMDQ5LS41ODNMNy43NDEsMTMuNzRhNi44LDYuOCwwLDAsMCwxLjc3MS4yNTNjMy43OTMsMCw2LjQxNS0yLjc5Miw3LjUyNC00LjI0N0ExMi4xMiwxMi4xMiwwLDAsMCwxNC4yLDcuNDA5bDEuMTc4LTEuMTU2QTEzLjE1NCwxMy4xNTQsMCwwLDEsMTksOS42NzlaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIC0yLjU2MykiIGZpbGw9IiNhNWE1YTUiLz48L3N2Zz4=";
.errors-list {
  margin: 5px 0;
  padding: 0px 10px;
  font: $font-regular;
  font-size: 10px !important;
  color: $danger;
  display: block;
}

.signin-form {
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
  &__form {
    margin-bottom: 28px;
  }
  &__text {
    margin-top: 28px;
    font-size: $fontsize-base;
    margin-bottom: 5px;
    color: $color-text-dark;
  }
  &__links {
    text-align: center;
  }
  &__link {
    color: $color-text-bright;
    font: $font-semibold;
    font-size: 14px;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
  }
  &-description {
    text-align: center;
    margin: 0;
    font-size: 1.2rem;
    line-height: 1.2;
    margin-bottom: 2.5rem;
  }
  .form-group {
    p {
      line-height: 1.1;
      font-size: 1.4rem;
    }
  }
}
.auth__check {
  font: $font-regular;
  font-size: 12px;
  display: flex;
  align-items: center;
  min-height: 20px;
  span {
    display: block;
    padding-top: 3px;
  }
  .auth__check-input {
    display: none;
  }
  .auth__check-label {
    position: relative;
    padding-left: 28px;
    margin: 0;
    a {
      color: $color-base-accent;
    }
  }
  .auth__check-input + .auth__check-label::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: 1px solid $color-gray-dark;
  }
  .auth__check-input:checked + .auth__check-label::before {
    background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4LjgiIGhlaWdodD0iNi41NTgiIHZpZXdCb3g9IjAgMCA4LjggNi41NTgiPiAgICA8ZGVmcz4gICAgICAgIDxzdHlsZT4gICAgICAgICAgICAuY2xzLTF7ZmlsbDpub25lO3N0cm9rZTojZmZmO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoxMDtzdHJva2Utd2lkdGg6MnB4fSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iY2hlY2stc3F1YXJlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTS0xLjUyNSAyLjM3OGwyLjA1MyAyLjE4IDIuNzMxLTIuNjIyTDUuMjc1IDAiIGNsYXNzPSJjbHMtMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS41MjUpIi8+ICAgIDwvZz48L3N2Zz4=),
      $color-base-accent;
    background-repeat: no-repeat, no-repeat;
    background-size: 70%, contain;
    background-position: center;
    border-color: $color-base-accent;
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
  &--extended {
    .input-wrapper__input {
      padding-right: 25px;
      &:focus ~ .input-wrapper__action {
        & /deep/ svg {
          stroke: $color-base-accent;
        }
      }
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
    &-eye {
      width: 20px;
      height: 20px;
      border: none;
      background: url($eye-inactive);
      background-position: center;
      background-repeat: no-repeat;
      &.active {
        background: url($eye-active);
        background-position: center;
        background-repeat: no-repeat;
      }
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
.auth__submit {
  @include big-rounded-btn;
	@include btnHov($color-hover-button);
  @include media-breakpoint-down(sm) {
    width: 100%;
  }
  margin: 82px auto 0;
}
</style>
