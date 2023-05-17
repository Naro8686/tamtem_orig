<template lang="pug">
	section.unsub
		div.container.unsub__container(v-if="unsub == 'true'")
			div.unsub__box
				div.unsub__pic
					<svg xmlns="http://www.w3.org/2000/svg" width="56.224" height="53.699">
						<g data-name="Group 17722"><path data-name="Path 17244" d="M45.151 19.008L25.015 7.291a4.08 4.08 0 00-4.106 0L.773 19.008A1.556 1.556 0 000 20.352v1.555L20.646 36.13a4.081 4.081 0 004.632 0l20.646-14.223v-1.555a1.556 1.556 0 00-.773-1.344zm0 0" fill="#2fc06e"/><path data-name="Path 17245" d="M20.646 36.129L0 21.908v27.554a1.633 1.633 0 001.633 1.633h42.658a1.633 1.633 0 001.633-1.633V21.908L25.277 36.129a4.081 4.081 0 01-4.632 0zm0 0" fill="#0079b2"/><path data-name="Path 17247" d="M20.646 36.13a4.082 4.082 0 004.632 0l15.165-10.447V2.892A2.411 2.411 0 0038.032.481H7.892a2.411 2.411 0 00-2.409 2.411v22.79zm0 0" fill="#fff"/><path data-name="Path 17250" d="M35.62 7.234H10.303a.964.964 0 110-1.929H35.62a.964.964 0 110 1.929zm0 0" fill="#0079b2"/><path data-name="Path 17251" d="M35.62 13.02H10.303a.964.964 0 110-1.929H35.62a.964.964 0 110 1.929zm0 0" fill="#0079b2"/><path data-name="Path 17252" d="M35.62 18.807H10.303a.964.964 0 110-1.929H35.62a.964.964 0 110 1.929zm0 0" fill="#0079b2"/><path data-name="Path 17253" d="M35.62 24.594H10.303a.964.964 0 110-1.929H35.62a.964.964 0 110 1.929zm0 0" fill="#0079b2"/><path data-name="Path 17255" d="M52.936 41.074a9.424 9.424 0 11-9.424-9.424 9.424 9.424 0 019.424 9.424zm0 0" fill="#fff" stroke="#0079b2" stroke-width=".5"/><path data-name="Path 17259" d="M51.595 53.698a1.023 1.023 0 01-.731-.3l-5.668-5.668a.036.036 0 00-.05 0l-.431.432a1.033 1.033 0 01-1.762-.673l-.354-6.321a1.033 1.033 0 011.089-1.089l6.322.354a1.033 1.033 0 01.673 1.762l-.432.432a.041.041 0 000 .049l5.668 5.668a1.033 1.033 0 010 1.461l-3.595 3.594a1.024 1.024 0 01-.73.3z" fill="#2fc06e"/><path data-name="Path 17260" d="M22.962 37.327a4.562 4.562 0 01-2.59-.8L5.207 26.08a.482.482 0 01-.208-.4V2.89A2.9 2.9 0 017.892-.003h30.139a2.9 2.9 0 012.893 2.893v22.79a.482.482 0 01-.208.4L25.551 36.528a4.561 4.561 0 01-2.589.8zm-17-11.9l14.956 10.3a3.618 3.618 0 004.084 0l14.957-10.3V2.893A1.931 1.931 0 0038.03.964H7.892a1.931 1.931 0 00-1.929 1.929zm0 0" fill="#2fc06e"/></g>
					</svg>
				h1.unsub__title Вы успешно отписались от уведомлений
				p.unsub__text Передумали? Перейдите в личный кабинет для управления подписками.
		div.unsub-oldlink.container.unsub__container(v-if="unsub == 'false'")
			div.unsub__box
				h1.unsub__title Данная ссылка недействительна
</template>

<script>
export default {
	data() {
		return {
			unsub: false,
		}
	},
	created() {
		this.unsub = this.$route.query.unsub;
	}
}
</script>

<style lang="scss" scoped>
.unsub {
	&__box {
		background: #ececec;
		border-radius: 10px;
		border: solid 1px #ececec;
		min-height: 335px;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
	&__pic {
		margin-bottom: 50px;
		display: flex;
		justify-content: center;
	}
	&__title {
		font-size: 18px;
		font-weight: 500;
		margin-bottom: 20px;
		text-align: center;
	}
	&__text {
		font-size: 14px;
		text-align: center;
	}
}
</style>