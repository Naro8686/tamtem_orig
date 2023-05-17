<template lang="pug">
	.bid-retreat
		.bid-retreat__pic
			icon-exit
		h3.bid-retreat__title Отменить предложение
		p.bid-retreat__text {{text}}
		template(v-if="buttonsVisible")
			button.bid-retreat__btn.bid-retreat__btn--accept(@click="$emit('leave')") Отменить предложение
			button.bid-retreat__btn.bid-retreat__btn--cancel(@click="$emit('close')") Сохранить предложение
</template>

<script>
import iconExit from "../../Icons/iconExit.vue";
export default {
	components: {
		iconExit
	},
	props:{
		responseId:{
			type:[String,Number]
		},
		text:{
			type: String,
			default: 'Вы действительно хотите отменить своё предложение на заказ?'
		},
		buttonsVisible:{
			type: [String,Boolean],
			default: true
		}
	},
}
</script>

<style lang="scss" scoped>
@import '~styleguide';
.bid-retreat {
	font-family: $font-family;
	font-size: 14px;
	display: flex;
	flex-direction: column;
	align-items: center;
	padding-top: 50px;
	padding-bottom: 50px;
	&__pic {

	}
	&__title {
		text-align: center;
		font-size: 26px;
		line-height: 34px;
		margin-top: 33px;
	}
	&__text {
		margin-top: 36px;
		line-height: 19px;
		text-align: center;
	}
	&__btn {
		border-radius: 32px;
		font-size: 14px;
		min-width: 164px;
		min-height: 43px;
		width: 90%;
		display: flex;
		justify-content: center;
		align-items: center;
		&--accept {
			color: #000000;
			border: 1px solid $danger;
			background-color: #ffffff;
			margin-top: 54px;
			&:hover {
				background-color: #fdebeb;
				color: $danger;
			}
		}
		&--cancel {
			margin-top: 20px;
			background-color: $color-base-accent;
			color: $color-base-light;
		}
	}
}
</style>