<template lang="pug">
section.error-page
	.container
		h1.hidden 404
		div.error-page__box
			div.error-page__text
				h2.error-page__title Извините, страница #[br] временно недоступна
				router-link(:to="{name: 'lk.profile'}").error-page__link 
					span Личный кабинет
					div.error-page__link-arrow
						feather(type="chevron-right")
			div.error-page__number.error-page__number--first 4
			div.error-page__picwrap
				img.error-page__pic(src="/images/404-pic.png" alt="0")
			div.error-page__number.error-page__number--last 4
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {},
  mounted() {
  }
};
</script>

<style lang="scss" scoped>
// @import "../../../sass/mixins.scss";
@import "~styleguide";
.hidden {
	height: 0;
	overflow: hidden;
	visibility: hidden;
}
.error-page {
  background-color: $color-base-accent;
	height: 100%;
	color: $color-text-light;
	padding: 46px 0;
	font: $font-regular;
	&__title {
		font: $font-regular;
		font-size: 26px;
		line-height: 42px;
		@media(max-width: 768px) {
			font-size: 22px;
			line-height: 32px;
		}
	}
	&__link {
		font: $font-medium;
		font-size: 14px;
		line-height: 42px;
		display: flex;
		align-items: center;
		color: $color-text-light;
		text-decoration: none;
		&-arrow {
			padding-left: 120px;
			position: relative;
			display: flex;
			margin-left: 8px;
			&::before {
				content: '';
				position: absolute;
				width: 134px;
				height: 2px;
				top: 50%;
				margin-top: -1px;
				background-color: $color-text-light;
				left: 0;
			}
			& /deep/ .feather__content {
				stroke-width: 2px;
			}
		}
	}
	&__box {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr;
		grid-template-areas: 
			". pic ."
			"first pic ."
			"first . last"
			"text text last";
		@media(max-width: 768px) {
			grid-template-columns: 1fr 1fr auto;
		}
		@media(max-width: 540px) {
			row-gap: 20px;
			// grid-template-columns: 
		}
		@media(max-width: 425px) {
			grid-template-areas: 
				". pic ."
				"first pic ."
				"first . last"
				". . last"
				"text text text";
		}
	}
	&__picwrap {
		grid-area: pic;
		justify-self: end;
		margin-right: -25px;
		@media(max-width: 425px) {
			margin-right: -52px;
			margin-left: 40px;
		}
	}
	&__pic {
		width: 100%;
		height: auto;
		max-width: 366px;
		min-width: 113px;
		@media(max-width: 768px) {
			max-width: 231px;
		}
	}
	&__number {
		justify-self: center;
		font-size: 34rem;
		line-height: 34rem;
		line-height: 80%;
		font-weight: 500;
		&--first {
			grid-area: first;
			justify-self: start;
			align-self: start;
		}
		&--last {
			grid-area: last;
			justify-self: end;
			align-self: end;
		}
		@media(max-width: 768px) {
			font-size: 30rem;
		}
		@media(max-width: 640px) {
			font-size: 23rem;
		}
		@media(max-width: 480px) {
			font-size: 19rem;
		}
		@media(max-width: 380px) {
			font-size: 16rem;
		}
		@media(max-width: 320px) {
			font-size: 14rem;
		}
	}
	&__text {
		grid-area: text;
	}
}
</style>
