<template lang="pug">
	article.ctg-pic
		.ctg-pic__maincircle
			img(src="/images/categories/ctg-test@2x.jpg")
		.ctg-pic__backing

</template>
<script>
export default {
	
}
</script>
<style lang="scss" scoped>
.ctg-pic {
	width: 315px;
	height: 315px;
	display: flex;
	justify-content: center;
	align-items: center;
	position: relative;
	overflow: hidden;
	&__maincircle {
		width: 213px;
		height: 213px;
		border-radius: 50%;
		overflow: hidden;
		z-index: 8;
		img {
			width: 100%;
			height: auto;
		}
	}
	&__backing {
		position: absolute;
		z-index: 6;
		top: -5px;
		left: -5px;
		width: 100%;
		height: 100%;
		border-radius: 50%;
		background: linear-gradient(26deg, #437fec 12%, #4cd9ed 92%);
		transform: rotate(-45deg);
		&::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			height: 50%;
			width: 100%;
			background-color: #fff;
			z-index: 7;
		}
	}
}
</style>