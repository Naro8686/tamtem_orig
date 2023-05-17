<template lang="pug">
	//- div(
		:class="pointClass"
		).pointsblank__mark
	div.answer-yes(v-if="flag == true")
		iconTick(color="#2fc06e")
	div.answer-no(v-else) -
</template>

<style lang="scss" scoped>
@import "~styleguide";
.pointsblank__mark {
	grid-area: mark;
	width: 18px;
	height: 18px;
	border-radius: 50%;
	border: 1px solid $black;
	color: $color-base-light;
	display: flex;
	justify-content: center;
	align-items: center;
	&::before {
		content: '';
		width: 100%;
		height: 100%;
		background-repeat: no-repeat;
		background-size: 54%;
		background-position: center;
	}
	&--yes {
		background-color: $color-base-accent;
		border-color: $color-base-accent;
		&::before {
			background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4LjgiIGhlaWdodD0iNi41NTgiIHZpZXdCb3g9IjAgMCA4LjggNi41NTgiPiAgICA8ZGVmcz4gICAgICAgIDxzdHlsZT4gICAgICAgICAgICAuY2xzLTF7ZmlsbDpub25lO3N0cm9rZTojZmZmO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoxMDtzdHJva2Utd2lkdGg6MnB4fSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iY2hlY2stc3F1YXJlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTS0xLjUyNSAyLjM3OGwyLjA1MyAyLjE4IDIuNzMxLTIuNjIyTDUuMjc1IDAiIGNsYXNzPSJjbHMtMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS41MjUpIi8+ICAgIDwvZz48L3N2Zz4=);
		}
	}
	&--not {
		border-color: $color-base-attention;
		background-color: $color-base-attention;
		&::before {
			content: '—';
			display: flex;
			justify-content: center;
			align-items: center;
			font-size: 10px;
			font-weight: 700;
		}
	}
}
.answer-yes {
	width: 15px;
	& /deep/ svg {
		width: 100%;
		height: auto;
	}
}
.answer-no {
	color: #ff564b;
	width: 15px;
	height: 2px;
	background-color: #ff564b;
	display: flex;
	justify-content: center;
	align-items: center;
}
</style>
<script>
import iconTick from "../../Icons/iconTick.vue";

export default {
		components: {
			iconTick
		},
    props: {
			flag: Boolean
		},
    computed: {
        pointClass () {
            if(this.flag == true) {
							return 'pointsblank__mark--yes';
            } 
            else {
							return 'pointsblank__mark--not';
            }
        }
    }
}
</script>