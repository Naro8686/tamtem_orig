<template lang="pug">
	section.newbid-done
		div.success-pic
			feather(type="check")
		h4.newbid-done__title Заказ создан!
		p.newbid-done__text
			| Ваш заказ отправлен на модерацию. #[br] Если возникнут вопросы, менеджер tamtem.ru с вами свяжется.
</template>

<script>
export default {
	
}
</script>

<style lang="scss" scoped>
$color-base-accent: #2fc06e;
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
.newbid-done {
	display: flex;
	flex-direction: column;
	align-items: center;
	&__title {
		font-weight: 500;
		font-size: 26px;
		line-height: 34px;
		text-align: center;
		margin: 30px 0;
	}
	&__text {
		text-align: center;
		font-size: 14px;
		line-height: 19px;
	}
}
</style>