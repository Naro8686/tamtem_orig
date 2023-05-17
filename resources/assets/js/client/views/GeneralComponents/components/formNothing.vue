<template lang="pug">
	div.nothing-form
		h3.nothing-form__title Не нашли, что искали?
		p #[br]Хотите продать свой товар быстрее? 
			a(
						@click="toSellCreate()"
						href="/newsell"
					).createorder__btn Создать объявление
		
		p #[br]Хотите сами поставить условия сделки и купить по выгодной цене? 
			a( 
							@click="toBidCreate()"
							href="/newbid"
						).createorder__btn Создать заказ
			//-p.nothing-form__desc
			//-| Заполните форму и получите заказы на свою продукцию. Это бесплатно!
		//-form(@submit.prevent="registerSubmit()").nothing-form__form
			.nothing-form__inputs
				v-text-field(
					v-model='form.text'
					solo
					height='40px'
					placeholder='Что хотите продать?'
					name='text'
					v-validate=`'required'`
					data-vv-as='Заказ'
					data-vv-name='text'
					:error-messages="err.text ? err.text : errors.collect('text')"
				).nothing-form__input

			//-.nothing-form__inputs.nothing-form__inputs--advanced(v-if='!isAuth')
				v-text-field(
					solo
					height='40px'
					placeholder='Имя'
					name='name'
					data-vv-as='Имя'
					data-vv-name='name'
					v-model='form.name'
					v-validate=`'required'`
					:error-messages="err.name ? err.name : errors.collect('name')"
				).nothing-form__input
				v-text-field(
					solo
					height='40px'
					placeholder='Email'
					name='email'
					data-vv-as='Email'
					data-vv-name='email'
					v-model='form.email'
					v-validate=`'required|email'`
					:error-messages="err.email ? err.email : errors.collect('email')"
				).nothing-form__input
				v-text-field(
					solo
					height='40px'
					name='phone'
					data-vv-as='Телефон'
					data-vv-name='phone'
					v-model='phone'
					placeholder="+7 (___) ___ __ __"
					v-validate=`'required|mobilePhone'`
					:error-messages="err.phone ? err.phone : errors.collect('phone')"
				).nothing-form__input
			//-.nothing-form__confidence.confidence(v-if='!isAuth')
				input(
					type="checkbox"
					id="confidence"
					v-model="form.confidence"
					v-validate=`'required'`
					name="confidence"
					data-vv-as='Условия'
					data-vv-name='confidence'
					:error-messages="err.confidence ? err.confidence : errors.collect('confidence')"
				).confidence__input
				label(for="confidence").confidence__label
					span(:class="{'has-error':errors.has('confidence')}").confidence__desc
						p Я принимаю условия 
							a(href="/files/agreement.pdf").confidence__link пользовательского соглашения 
							| и даю свое согласие на обработку персональных данных на условиях и целях, определенных 
							a(href="/files/politic.doc").confidence__link политикой конфиденциальности.
					span(
						class="error--text small--text"
						v-if="errors.has('confidence')") {{errors.first('confidence')}}
			v-btn(
				flat
				type='submit'
			).nothing-form__submit 
				span.px-3
					| Получить заказ
					b-spinner(v-if='loading' label='Загрузка...')
</template>

<script>
import { Validator } from 'vee-validate'
export default {
	data() {
		return {
			phone: null,
			loading: false,
			err: {},
			form: {
				theme: "Ничего не найдено",
				text: null,
				name: null,
				email: null,
				phone: null,
				confidence: null
			},
			url: "/api/v1/send/support",
			successmsg: `
				<p style='font-family: Montserrat;font-size: 16px;font-weight:600;'>
					Спасибо за обращение. Мы как можно скорее найдем для Вас интересующий товар.
				</p>
			`
		};
	},
	created(){
		Validator.extend('mobilePhone',{
			field: (field) => `Поле ${field} имеет ошибочный формат`,
			validate: (value) => {
				return value.replace(/[^0-9]/gi, "").replace(/^[78]/, "").length==10}
		})
	},
	computed: {
		isAuth() {
			if (this.$root.profile) {
				return true;
			} else {
				return false;
			}
		}
	},
	watch :{
		phone(newVal){
			this.phone = newVal.replace(/[^0-9]/gi, "").slice(0,11)
		}
	},
	methods: {
		formClear() {
			this.form.text = '';
			this.form.email = '';
			this.form.name = '';
			this.form.confidence = null;
			this.form.phone = '';
			this.phone = '';
		},
		formSend() {
			this.loading = true;
			this.err = {};
			this.form.form_type = 1;
			this.form.phone = this.phone;
			let data = Object.assign({}, this.form);
			if (this.isAuth) {
				data.text = `Идентификатор пользователя: ${this.$root.profile.profile.unique_id}. Заказ пользователя: " ${data.text}`;
			}
			axios
				.post(this.url, { data: data })
				.then(resp => {
					this.formClear();
					this.$nextTick(() => {
						this.$validator.reset();
						this.errors.clear();
					})
					this.loading = false;
					this.$store.dispatch("setModalMsg", {
						toggle: true,
						content: this.successmsg
					});
				})
				.catch(error => {
					this.printErrorOnConsole(error);
					this.err = error.response.data.errors;
					this.loading = false;
					this.$store.dispatch("setSnackbar", {
						color: "error",
						text: "Произошла ошибка, попробуйте позднее",
						toggle: true
					});
				});
		},
		registerSubmit() {
			this.$validator.validateAll().then(result => {
				if (result) {
					this.formSend();
					return;
				}
			});
		}
	}
};
</script>

<style lang="scss" scoped>
@import "../../../../../sass/styleguide.scss";
@import "../../../../../sass/mixins.scss";
.nothing-form {
	@include media-breakpoint-up(sm) {
		border: 1px solid $color-base-light;
		border-radius: $border-radius-small;
		box-shadow: 0 2px 6px 0 rgba(4, 91, 99, 0.18);
		padding: 36px 20px 37px;
	}
	display: flex;
	flex-direction: column;
	align-items: center;
	background: $color-base-light;
	a{
		text-decoration: underline;
	}
	&__title {
		@include media-breakpoint-up(xl) {
			font-size: 32px;
			line-height: 42px;
		}
		font: $font-medium;
		font-size: 17px;
		line-height: 20px;
		color: $color-base-dark;
		margin-bottom: 7.7px;
	}
	&__desc {
		@include media-breakpoint-up(xl) {
			font-size: 16px;
			line-height: 19px;
		}
		font-size: 14px;
		line-height: 18px;
		color: $black;
		text-align: center;
	}
	&__form {
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 100%;
		margin-top: 20px;
	}
	&__inputs {
		@include media-breakpoint-up(sm) {
			flex-direction: row;
		}
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		width: 100%;
		&--advanced {
			margin-top: 12px;
		}
	}
	&__input {
		@include media-breakpoint-up(sm) {
			margin-bottom: 0;
		}
		width: 100%;
		height: 40px;
		&:not(:last-child) {
			margin-bottom: 12px;
			@include media-breakpoint-up(sm) {
				margin-bottom: 0;
				margin-right: 20px;
			}
		}
		&.error--text {
			@media screen and (max-width: 425px) {
				margin-bottom: 30px;
			}
		}
	}
	&__submit {
		@include big-rounded-btn;
		@include media-breakpoint-down(sm){
			width: 100%;
		}
		width: 207px;
		margin-top: 27px;
	}
	& /deep/ .v-input.v-text-field .v-input__control .v-input__slot {
		background-color: $color-base-light !important;
		input::placeholder {
			color: $black;
		}
		.v-text-field__prefix {
			margin-right: 5px;
		}
	}
}
.error--text,
.error--text ~ .v-input {
	@include media-breakpoint-up(sm) {
		margin-bottom: 20px;
	}
}
.confidence {
	margin-top: 27px;
	width: 100%;
	font: $font-regular;
	font-size: 12px;
	display: flex;
	align-items: center;
	min-height: 20px;
	span {
		display: block;
	}
	.confidence__input {
		display: none;
	}
	.confidence__label {
		position: relative;
		padding-left: 36px;
		margin: 0;
	}
	.confidence__input + .confidence__label::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 20px;
		height: 20px;
		border-radius: 4px;
		border: 1px solid $color-border-gray;
	}
	.confidence__input:checked + .confidence__label::before {
		background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4LjgiIGhlaWdodD0iNi41NTgiIHZpZXdCb3g9IjAgMCA4LjggNi41NTgiPiAgICA8ZGVmcz4gICAgICAgIDxzdHlsZT4gICAgICAgICAgICAuY2xzLTF7ZmlsbDpub25lO3N0cm9rZTojZmZmO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoxMDtzdHJva2Utd2lkdGg6MnB4fSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iY2hlY2stc3F1YXJlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTS0xLjUyNSAyLjM3OGwyLjA1MyAyLjE4IDIuNzMxLTIuNjIyTDUuMjc1IDAiIGNsYXNzPSJjbHMtMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS41MjUpIi8+ICAgIDwvZz48L3N2Zz4=), $color-base-accent;
		background-repeat: no-repeat, no-repeat;
		background-size: 70%, contain;
		background-position: center;
		border-color: $color-base-accent;
	}
	&__link {
		color: $color-base-accent;
	}
}
</style>
