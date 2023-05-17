<template lang="pug" functional>
div
	template(v-if="props.variant === 'loaded'")
		<svg xmlns="http://www.w3.org/2000/svg" width="57.851" height="57.852" viewBox="0 0 57.851 57.852">
			<g id="image" transform="translate(.5 .5)">
				<rect id="Rectangle-path" width="56.851" height="56.852" class="cls-1" rx="2"/>
				<ellipse id="Oval" cx="4.704" cy="4.704" class="cls-1" rx="4.704" ry="4.704" transform="translate(12.651 12.651)"/>
				<path id="Shape" d="M50.579 15.681L34.773 0 0 34.5" class="cls-1" transform="translate(6.272 22.354)"/>
			</g>
		</svg>
	template(v-if="props.variant === 'upload'")
		<svg xmlns="http://www.w3.org/2000/svg" width="42.038" height="42"><g data-name="Group 9738" fill="#fff"><path d="M21 0a21 21 0 0115.326 35.356A20.775 20.775 0 0121 42a21 21 0 110-42z" data-name="Path 17214" stroke-dasharray="2 1"/><g stroke="#2fc06e" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"><path d="M36.689 29.373v9.7"/><path data-name="Shape" d="M31.841 34.221h9.7"/></g><g transform="translate(12.849 12.849)" stroke="#2fc06e" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"><rect width="16.524" height="16.524" rx="2"/><circle cx="1.377" cy="1.377" transform="translate(3.672 3.672)" r="1.377"/><path data-name="Shape" d="M16.524 11.016l-4.588-4.59-10.1 10.1"/></g></g></svg>
</template>

<script>
export default {
	props: {
		variant: String
	}
}
</script>
<style scoped>
	.cls-1{fill:#fff;stroke:#2fc06e;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10}

</style>