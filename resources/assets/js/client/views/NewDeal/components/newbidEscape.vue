<template lang="pug">
	section.newbid-escape
		div.newbid-escape__pic
			icon-exit
		h4.newbid-escape__title Выход
		p.newbid-escape__text Вы действительно хотите завершить создание?
		button.newbid-escape__exit(@click="$emit('selectAnswer','yes')") Завершить создание
		button.newbid-escape__continue(@click="$emit('selectAnswer','no')") Продолжить создание
</template>

<script>
import iconExit from "../../Icons/iconExit.vue"
export default {
	components: {
		iconExit
	}
}
</script>

<style lang="scss" scoped>
$color-base-accent: #2fc06e;
.newbid-escape {
	display: flex;
	flex-direction: column;
	align-items: center;
	&__pic {
		margin-bottom: 30px;
	}
	&__title {
		font-weight: 500;
		font-size: 26px;
		line-height: 34px;
		margin: 20px 0;
	}
	&__text {
		font-size: 14px;
		line-height: 19px;
		text-align: center;
	}
	&__exit {
		color: $color-base-accent;
		font-size: 14px;
		font-weight: 500;
		line-height: 24px;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #fff;
		border-radius: 35px;
		border: 1px solid #d4f5de;
		width: 209px;
		height: 43px;
		margin-top: 54px;
	}
	&__continue {
		color: #fff;
		font-weight: 500;
		font-size: 14px;
		line-height: 24px;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: $color-base-accent;
		border-radius: 35px;
		width: 209px;
		height: 43px;
		margin-top: 18px;
	}
}
</style>