<template lang="pug">
	section.newbid-feedback
		h4.newbid-feedback__title Оставьте #[br] заявку
		form.newbid-feedback__form(@submit.prevent="prepareDataToSend")
			div.input-wrapper.newbid-feedback__input(:class="{'errors' : errors.has('name')}")
				input(
					v-model="name"
					data-vv-name="name"
					data-vv-as="Наименование"
					v-validate=`'required|min:2'`
					type="text" placeholder="Введите наименование товара").input-wrapper__input
				i.errors-list(v-if="errors.has('name')") {{...errors.collect('name')}}
			div.input-wrapper.newbid-feedback__input(:class="{'errors' : errors.has('phone')}")
				input(type="text" 
				placeholder="+7 (___) ___ __ __"
				data-vv-as="Телефон"
				data-vv-name="phone"
				v-model="phone"
				v-validate=`'required|mobilePhone'`
				v-mask="['+7 (###) ###-##-##']"
			).input-wrapper__input
				i.errors-list(v-if="errors.has('phone')") {{...errors.collect('phone')}}
			div.newbid-feedback__time
				p Время, в которое вам удобно перезвонить
				div.newbid-feedback__timebox
					.input-wrapper.newbid-feedback__timestart
						input(type="text"  data-vv-name='timeFrom'  data-vv-as="Время от" v-model="time.from" v-mask="`##:##`" placeholder="00 : 00").input-wrapper__input
						i.errors-list
					.input-wrapper.newbid-feedback__timeend
						input(type="text" data-vv-name='timeTo' data-vv-as="Время до"  v-model="time.to" v-mask="`##:##`" placeholder="00 : 00").input-wrapper__input
						i.errors-list
				i.errors-list(v-if="errors.has('timeFrom') || errors.has('timeTo')") {{...errors.collect('timeFrom')}}#[br]{{...errors.collect.timeTo}}   
			button.newbid-feedback__submit(type="submit" :disabled="sending") Отправить
</template>

<script>
import { mask } from "vue-the-mask";
import {  Validator } from 'vee-validate';
export default {
	directives: {
		mask
	},
	data: () => ({
		sending: false,
		name: null,
		phone: null,
		time: {
			from: null,
			to: null
		}
	}),
	created(){
		Validator.extend('mobilePhone',{
			field: (field) => `Поле ${field} имеет ошибочный формат`,
			validate: (value) => {
				return value.replace(/[^0-9]/gi, "").replace(/^[78]/, "").length==10}
		})
	},
	watch :{
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
				name: this.name,
				phone: this.phone.replace(/[^0-9]/gi, "").replace(new RegExp(/['+','\-',' ','(',')']/, "g"), "").replace(/^[78]/, "")
			};
			if (this.time.from!=null && this.time.to!=null && this.time.from.length==5 && this.time.to.length==5){
				
				data.time_from = this.time.from;
				data.time_to = this.time.to;
			}

			this.sending = true;
			axios
				.post("/api/v1/send/support/createdeal", data)
				.then(result => {
			if (result.data.result == true) {
					this.$store.dispatch("setSnackbar", {
					color: "success",
					text: `Спасибо за обращение, наш менеджер свяжется с Вами в ближайшее время.`,
					toggle: true
				});
				this.$emit('close');
			}
				})
				.catch(err => {
					console.log(err);
			this.$store.dispatch("setSnackbar", {
					color: "error",
					text: `Произошла ошибка, попробуйте позднее.`,
					toggle: true
				});
		})
				.then(() => {
					this.sending = false;
				});
		}
	}
};
</script>

<style lang="scss" scoped>
@import "~styleguide";

$font-regular: 400 1rem "Montserrat", sans-serif;

.newbid-feedback {
	display: flex;
	flex-direction: column;
	align-items: center;
	font: $font-regular;
	font-size: 14px;
	&__title {
		font-weight: 500;
		font-size: 26px;
		line-height: 34px;
		margin-bottom: 40px;
		text-align: center;
	}
	&__input {
		margin-top: 26px;
		input {
			background: #f6f6f6;
		}
	}
	&__time {
		display: flex;
		margin-top: 26px;
		p {
			font-size: 12px;
			line-height: 19px;
			color: #707070;
			width: 50%;
		}
	}
	&__timebox {
		display: flex;
		width: 50%;
		justify-content: flex-end;
		input {
			padding: 0 4px;
			width: 58px;
			background: #f6f6f6;
		}
	}
	&__timestart {
		margin-right: 17px;
		position: relative;
		&::after {
			content: "—";
			position: absolute;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 4px;
			width: 16px;
			right: -16px;
			top: calc(50% - 2px);
		}
	}
	&__submit {
		width: 209px;
		height: 43px;
		color: #fff;
		background-color: $color-base-accent;
		border-radius: 35px;
		display: flex;
		justify-content: center;
		align-items: center;
		margin-top: 40px;
		font-size: 14px;
		line-height: 24px;
		margin: 0 auto;
		margin-top: 50px;
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
		&::placeholder {
			color: $color-text-gray;
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
		}
		input,
		textarea {
			border: 1px solid $danger;
		}
	}
}
</style>