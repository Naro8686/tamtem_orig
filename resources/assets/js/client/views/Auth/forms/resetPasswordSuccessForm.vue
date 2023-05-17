<template lang="pug">
section.restore-password-success
	div.restore-password-success__box.restore-password-success__box
		.restore-password-success__pic
				feather(type='check')
		h4.restore-password-success__header.restore-password-success__header--confirm Спасибо,#[br]что вы с нами!
		div.restore-password-success__confirm
			p.restore-password-success__confirm-text Ваш пароль успешно сброшен
		div.restore-password-success__links 
			a.restore-password-success__link(
				href="javascript:void(0);" 
				@click="$root.$emit('showForm','authorizationForm')") Вход
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
@import "~styleguide";

.restore-password-success {
  &__header {
	color: $color-text-bright;
	font: $font-medium;
	font-size: 32px;
	line-height: 42px;
	align-self: flex-start;
	margin-bottom: 54px;
	&--confirm {
	  text-align: center;
	  margin-bottom: 40px;
	}
	@media (max-width: 767px) {
	  font-size: 26px;
	  line-height: 36px;
	}
  }
  &__pic {
	margin: 30px auto;
	border-radius: 50%;
	border: 2px solid $color-base-accent;
	width: 72px;
	height: 72px;
	display: flex;
	justify-content: center;
	align-items: center;
	& /deep/ svg {
	  width: 30px;
	  height: auto;
	  stroke: $color-base-accent;
	  stroke-width: 3;
	}
  }
  &__confirm {
	display: flex;
	flex-direction: column;
	align-items: center;
	&-text {
	  text-align: center;
	}
  }
  &__links {
	text-align: center;
	margin-top: 50px;
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
}
</style>