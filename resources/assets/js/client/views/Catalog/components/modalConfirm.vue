<template lang="pug">
	section.confirm-response
		b.confirm-response__name {{username}},
		p.confirm-response__notice обращаем ваше внимание, что отправить предложение заказчику можно бесплатно!
		div.confirm-response__info.confirm-info
			div.confirm-info__box
				p.confirm-info__txt Стоимость контактных данных после выбора #[br] #[b Вас победителем]
				p.confirm-info__equal =
				p.confirm-info__sum {{comission}} ₽
			.confirm-info__notice
				.confirm-info__notice--icon
					icon-information(fill="#ffffff")
				.confirm-info__notice--text Важно! Заказчик выберет только одного поставщика
		button(@click='$emit("sendRequest")').confirm-response__btn.confirm-response__btn--cancel Отправить предложение
		button(@click='$emit("closeConfirm")').confirm-response__btn.confirm-response__btn--accept Отменить предложение
</template>

<script>
import iconInformation from "../../Icons/iconInformation";
export default {
	props: {
		comission: {
			type: [String, Number],
			default: 150
		},
		username: {
			type: String
		}
	},
	components: { iconInformation }
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
.confirm-response {
	font-family: $font-family;
	font-size: 14px;
	&__name {
		margin-top: 45px;
		display: block;
		text-align: center;
		font-size: 18px;
		line-height: 28px;
		margin-bottom: 10px;
	}
	&__notice {
		text-align: center;
		line-height: 1.5;
		margin-bottom: 55px;
	}
	&__info {
		margin-bottom: 57px;
	}
	&__btn {
		border-radius: 32px;
		font-size: 14px;
		min-width: 164px;
		min-height: 43px;
		max-width: 242px;
		width: 90%;
		display: flex;
		justify-content: center;
		align-items: center;
		margin-left: auto;
		margin-right: auto;
		&:not(:last-child) {
			margin-bottom: 18px;
		}
		&--accept {
			color: #000000;
			border: 1px solid $danger;
			background-color: #ffffff;
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
	.confirm-info {
		&__box {
			display: flex;
			align-items: center;
			padding-bottom: 5px;
			border-bottom: 1px solid $color-border-gray;
			justify-content: space-between;
		}
		&__txt {
			width: 200%;
			line-height: 1.5;
			b {
				font-weight: 500;
			}
		}
		&__equal {
			width: 20%;
			display: flex;
			justify-content: center;
			font-weight: 500;
		}
		&__sum {
			display: flex;
			width: 100%;
			justify-content: flex-end;
			font-weight: 500;
			font-size: 18px;
		}
		&__notice {
			font-size: 12px;
			text-align: center;
			margin: 5px 0;
			display: flex;
			justify-content: center;
			align-items: center;
			&--icon {
				width: 22px;
				height: 22px;
				background-color: $color-voply-card-header;
				border-radius: 50%;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			&--text {
				max-width: 180px;
				margin-left: 10px;
				font: $font-medium;
				font-size: $fontsize-smaller;
				color: $color-agent-link;
			}
		}
	}
}
</style>