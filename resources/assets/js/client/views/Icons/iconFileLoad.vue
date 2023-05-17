<template lang="pug" functional>
div
	template(v-if="props.variant === 'loaded'")
		<svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42">
			<g id="Group_15330" data-name="Group 15330" transform="translate(-627 -1000)">
				<g id="Group_15327" data-name="Group 15327" transform="translate(-43 -175)">
					<g id="Ellipse_139" data-name="Ellipse 139" transform="translate(670 1175)" fill="#fff" stroke="#ececec" stroke-width="1" stroke-dasharray="2 1">
						<ellipse cx="21" cy="21" rx="21" ry="21" stroke="none"/>
						<ellipse cx="21" cy="21" rx="20.5" ry="20.5" fill="none"/>
					</g>
				</g>
				<g id="Group_15329" data-name="Group 15329" transform="translate(488 -31.875)">
					<g id="paperclip" transform="translate(151.893 1044.042)">
						<path id="Shape" d="M16.607,8.255,8.756,16.107A5.129,5.129,0,0,1,1.5,8.853L9.354,1A3.419,3.419,0,0,1,14.19,5.837l-7.86,7.852a1.71,1.71,0,0,1-2.418-2.418l7.254-7.245" transform="translate(0 0)" fill="#fff" stroke="#2fc06e" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1"/>
					</g>
				</g>
			</g>
		</svg>
	template(v-if="props.variant === 'upload'")
		<svg xmlns="http://www.w3.org/2000/svg" width="42.038" height="42" viewBox="0 0 42.038 42">
			<g id="Group_15330" data-name="Group 15330" transform="translate(-627 -1000)">
				<g id="Group_15327" data-name="Group 15327" transform="translate(-43 -175)">
					<g id="Ellipse_139" data-name="Ellipse 139" transform="translate(670 1175)" fill="#fff" stroke="#ececec" stroke-width="1" stroke-dasharray="2 1">
						<ellipse cx="21" cy="21" rx="21" ry="21" stroke="none"/>
						<ellipse cx="21" cy="21" rx="20.5" ry="20.5" fill="none"/>
					</g>
					<g id="plus" transform="translate(701.841 1204.373)">
						<path id="Shape" d="M0,0V9.7" transform="translate(4.848)" fill="#fff" stroke="#2fc06e" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1"/>
						<path id="Shape-2" data-name="Shape" d="M0,0H9.7" transform="translate(0 4.848)" fill="#fff" stroke="#2fc06e" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1"/>
					</g>
				</g>
				<g id="Group_15329" data-name="Group 15329" transform="translate(488 -31.875)">
					<g id="paperclip" transform="translate(151.893 1044.042)">
						<path id="Shape-3" data-name="Shape" d="M16.607,8.255,8.756,16.107A5.129,5.129,0,0,1,1.5,8.853L9.354,1A3.419,3.419,0,0,1,14.19,5.837l-7.86,7.852a1.71,1.71,0,0,1-2.418-2.418l7.254-7.245" transform="translate(0 0)" fill="#fff" stroke="#2fc06e" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1"/>
					</g>
				</g>
			</g>
		</svg>
</template>
<script>
export default {
	props: {
		variant: String
	}
}
</script>
<style scoped>

</style>