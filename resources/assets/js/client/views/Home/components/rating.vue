<template lang="pug">
	ul.rate
		li(v-for="star in rateActive").rate__item.rate__item--active
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758" height="19.779" viewBox="0 0 20.758 19.779">
				<defs>
					<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5" y2="1.096" gradientUnits="objectBoundingBox">
						<stop offset="0" stop-color="#2fc06e"/>
						<stop offset="1" stop-color="#48c3c2"/>
					</linearGradient>
				</defs>
				<g id="Group_23596" data-name="Group 23596" transform="translate(0)">
					<path id="Path_42004" data-name="Path 42004" d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z" transform="translate(-1140.075 -678.815)" fill="url(#linear-gradient)"/>
				</g>
			</svg>
		li(v-for="item in rateUnactive").rate__item.rate__item--unactive
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758" height="19.779" viewBox="0 0 20.758 19.779">
				<defs>
					<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5" y2="1.096" gradientUnits="objectBoundingBox">
						<stop offset="0" stop-color="#2fc06e"/>
						<stop offset="1" stop-color="#48c3c2"/>
					</linearGradient>
				</defs>
				<g id="Group_23596" data-name="Group 23596" transform="translate(0)">
					<path id="Path_42004" data-name="Path 42004" d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z" transform="translate(-1140.075 -678.815)" fill="url(#linear-gradient)"/>
				</g>
			</svg>
</template>

<script>
export default {
	props: {
		rate: Number, 
	},
	data() {
		return {
			max: 5,
		}
	},
	computed: {
		rateUnactive() {
			return this.max - this.rate
		},
		rateActive() {
			return this.rate
		}
	}
}
</script>

<style lang="scss" scoped>
.rate {
	list-style: none;
	display: flex;
	&__item {
		&:not(:last-child) {
			margin-right: 3px;
		}
	}
}
.rate__item--unactive {
	svg {
		opacity: 0.2;
	}
}
</style>