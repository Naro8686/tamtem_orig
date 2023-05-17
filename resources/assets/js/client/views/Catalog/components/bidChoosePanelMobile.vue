<template lang="pug">
section.bid-body__pointsblank.pointsblank
		usercontacts(:userdata="winner[0].userdata" v-if="winner.length>0")
		ul.pointsblank__list
				li.pointsblank__item(v-if="hasMembers")
						div.pointsblank__mark.pointsblank__mark--yes
						div.pointsblank__content.points-offer
								div.pointsblank__cap.points-offer__cap 
										| Предложение
										div.points-offer__icon(v-if="activeMember.is_shipping_included==1")
												deliverycar
								p.pointsblank__text.points-offer__value {{activeMember.price_offer}} ₽
						p.pointsblank__delivery-cap(v-if="activeMember.is_shipping_included==1") Доставка включена в стоимость
						p.pointsblank__delivery-cap(v-else) Доставка не включена в стоимость
				li.pointsblank__item(v-for="item in sortSequence")
						div.pointsblank__mark(v-if="hasMembers" :class="{'pointsblank__mark--yes' : activeMember.answers[item].is_agree==1, 'pointsblank__mark--not' : activeMember.answers[item].is_agree==0}")
						div.pointsblank__content
								p.pointsblank__cap {{answers[item].header}}
								p.pointsblank__text(v-if="item=='dqh_specification'")
									template
										div(v-html="answers[item].value")
										a.uploaded-file__wrapper(v-if="files && files.length>0" :href="files[0].path")
											.uploaded-file__icon
												icon-file-load(variant="loaded")
											span.uploaded-file__name {{files[0].name}}
								p.pointsblank__text(v-else) {{answers[item].value}} {{ item=='dqh_volume' ? unit_for_all : '' }}
		section.pointsblank__comment.comment(v-if="hasMembers")
				div.comment__field(v-if="activeMember.notice") {{activeMember.notice}}
		section.pointsblank__comment
			.comment__field.conditions__files
					p.conditions__cap Файлы от поставщика
					.filelist-file
						.filelist-file__wrapper(v-for="item,i in activeMember.files" :key="i")
							.filelist-file__file(:style="addImage(item)" @click.prevent="viewImage(item)")
		modal-wrapper.wide-modal(v-if="viewDetailImage" @close="viewDetailImage=false")
			img(:src="viewingImage")
</template>
<script>
import usercontacts from "./usercontacts";
import iconFileLoad from "../../Icons/iconFileLoad";
import modalWrapper from '../../GeneralComponents/components/modal-wrapper.vue'
export default {
	components: {
		usercontacts,
		iconFileLoad,
		modalWrapper
	},
	props: {
		winner: {
			type: Array
		},
		files:{
			type: [Object,Array]
		},
		unit_for_all: {
			type: String
		},
		hasMembers: {
			type: Boolean
		},
		activeMember: {
			type: Object
		},
		answers: {
			type: [Object, Array]
		}
	},
	data() {
		return {
			viewDetailImage: false,
			viewingImage: null,
			sortSequence: [
				"dqh_specification",
				"dqh_type_deal",
				"dqh_volume",
				"dqh_min_party",
				"dqh_logistics",
				"dqh_doc_confirm_quality",
				"dqh_payment_method",
				"dqh_payment_term"
			]
		};
	},
	methods:{
		viewImage(fileData){
			this.viewingImage = fileData.file_full_path;
			this.viewDetailImage = true;
		},
		addImage(file){		
			return `background: url(${file.file_full_path})`
		},
	}
};
</script>
<style lang="scss" scoped>

@import "~styleguide";

.wide-modal {
	align-items: center;
	/deep/ .wrapper{
		&-content{
			max-width: 90%;
			width: auto;
			min-width: 200px;
			max-height: 90%;
			min-height: 200px;
		}
		&-slot{
			height: auto;
			max-height: 100%;
			min-height: 200px;
			min-width: 200px;
			width: auto;
			margin: 0px 10px;
			img{
				width: 100%;
				max-width: 800px;
				height: auto;
				max-height: 600px;
			}
		}
	}
}

.conditions__files{
	padding-left: 5px !important;
}
.filelist{
	display: flex;
	&-file{
		display: flex;	
		&__wrapper{
			position: relative;
			width: 92px;
			height: 92px;
			border: 1px solid $color-base-accent;
			border-radius: $border-radius-small;
			margin: 0px 5px;
			&:first-child{
				margin-left: 0;
			}
			&:last-child{
				margin-right: 0;
			}
		}
		&__file{
			width: 100%;
			height: 100%;
			background-position: center !important;
			background-repeat: no-repeat !important;
			background-size: 100% !important;
		}
	}
}
.uploaded-file{
	 &__wrapper{
		display: flex;
		align-items: center;
		padding: 15px 0;
	 }
	 &__icon{
		 margin-right: 20px;
	 }
	 &__name{
				font: $font-regular;
				font-size: $fontsize-smaller;
				color: #000;
			}
		&__size{
			font: $font-regular;
			font-size: $fontsize-smaller;
			color: $color-text-gray;
		}
}

.comment {
	&__field {
		word-wrap: break-word;
		border: 1px solid $color-border-gray;
		width: 100%;
		min-height: 126px;
		padding: 10px 10px 20px 60px;
		border-radius: $border-radius-small;
		color: $color-text-gray;
		resize: none;
		@media (max-width: 420px) {
			padding: 10px 10px 20px 42px;
			min-height: 62px;
			font-size: 11px;
		}
	}
}
.points-offer {
	flex-grow: 1;
	display: grid;
	grid-template-columns: 1fr 1fr;
	grid-template-areas:
		"title cap"
		"value cap";
	@media (max-width: 420px) {
		grid-template-areas:
			"title title"
			"value value"
			"cap cap";
	}
	&__cap {
		display: flex;
		grid-area: title;
	}
	&__icon {
		margin-left: 10px;
	}
	&__value {
		grid-area: value;
		@media (max-width: 420px) {
			margin-bottom: 10px;
		}
	}
}
.pointsblank {
	&__comment {
		margin-top: 20px;
	}
	&__list {
		list-style: none;
		font-size: 14px;
	}
	&__item {
		display: grid;
		grid-template-columns: 30px 1fr 1fr;
		grid-template-areas: "mark content cap";
		&:not(:last-child) {
			margin-bottom: 16px;
		}
		@media (max-width: 768px) {
			padding: 10px 20px 15px;
			border: 1px solid #ececec;
			border-radius: 10px;
			box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
		}
		@media (max-width: 420px) {
			padding: 10px 10px 15px 20px;
			grid-template-columns: 30px 1fr;
			grid-template-areas:
				"mark content"
				"cap cap";
		}
	}
	&__content {
		grid-area: content;
	}
	&__cap {
		color: $color-base-dark;
		margin-bottom: 6px;
	}
	&__text {
		color: $color-text-dark;
	}
	&__mark {
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
			content: "";
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
				content: "—";
				display: flex;
				justify-content: center;
				align-items: center;
				font-size: 10px;
				font-weight: 700;
			}
		}
	}
	&__delivery-cap {
		grid-area: cap;
		align-self: center;
		justify-self: center;
		background-color: $color-pale-green;
		color: $color-base-dark;
		border-radius: 32px;
		padding: 4px 20px;
		font-size: 12px;
		text-align: center;
	}
}
</style>