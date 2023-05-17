<template functional>
	<svg xmlns="http://www.w3.org/2000/svg" width="24.867" height="18.318" viewBox="0 0 24.867 18.318">
		<g id="check-square" transform="translate(2.5 2.5)">
			<path fill="none" :stroke="props.color" id="Shape" d="M-1.525 6.949l6 6.369 7.981-7.663L18.343 0" class="cls-1" transform="translate(1.525)"/>
		</g>
	</svg>
</template>
<script>
export default {
	props: {
		color: String
	}
}
</script>

<style scoped>
	.cls-1{
		fill:none;
		stroke-linecap:round;
		stroke-linejoin:round;
		stroke-miterlimit:10;
		stroke-width: 5px}
</style>