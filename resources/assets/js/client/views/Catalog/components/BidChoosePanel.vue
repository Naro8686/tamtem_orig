<template lang="pug">
		section.choosepanel(v-addHeights="{'questions':questions}")
			.choosepanel__title(v-if="hasMembers && !finished ") Выберите только одного поставщика
			.choosepanel__title(v-if="finished ") Вы выбрали поставщика
			.choosepanel__conditions.conditions
				//- .conditions__item
				//- 	p.conditions__cap Регион
				//- 	p.conditions__value Расстояние до поставщика
				.conditions__item
					p.conditions__cap Предложение  
					p.conditions__value Доставка включена в стоимость
				.conditions__item(:class="questions[question].name" v-for="question in sortSequence")
					p.conditions__cap {{questions[question].header}}
					p.conditions__value(v-if="questions[question].name=='dqh_specification'")
						template
							div(v-html="questions[question].value")
							a.uploaded-file__wrapper(v-if="files && files.length>0" :href="files[0].path")
								.uploaded-file__icon
									icon-file-load(variant="loaded")
								span.uploaded-file__name {{files[0].name}} 
					p.conditions__value(v-else) {{questions[question].value}} {{ questions[question].name=='dqh_volume' ? unit_for_all : '' }}
			.choosepanel__vendors.vendors(v-if="hasMembers" v-slidescroll="{'buttonLeft':'vendors__control--left','buttonRight':'vendors__control--right', 'scrollElem':'vendors__list'}")
				.vendors__control.vendors__control--left(v-if="!winner_id")
					svg(xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 46" width="20" height="20")
						path(fill="#27d066" d="M11.003 40.094c-1.338 1.352-1.338 3.541 0 4.893 1.336 1.35 3.506 1.352 4.844 0l19.15-19.539c1.338-1.352 1.338-3.543 0-4.895L15.847 1.014c-1.338-1.352-3.506-1.352-4.844 0s-1.338 3.541-.002 4.893L26.706 23 11.003 40.094z").vendors__arrow
				.vendors__list
					.vendors__item(v-for="member,i in winner.length>0 ? winner : members")
						.vendors__head
							p.vendors__name Поставщик №{{i+1}}
							div.vendors__btn(v-if="!finished")
								input(type="radio" :value="i" :checked="member.id==activeMemberId" name="vend" :id="addId(i)")
								label(:for="addId(i)" @click="$emit('setActive',member.id)")
						//- .vendors__field.location
						//- 	p(title="").location__region Республика Северная Осетия (Алания)
						//- 	p.location__distance 100 км
						.vendors__field.cost 
							span.cost__price {{member.price_offer}}  ₽
							div.cost__icon(v-if="member.is_shipping_included==1")
								deliverycar
						.vendors__field(
							:class="questions[item].name" 
							v-for="item in sortSequence"
						)
							bid-point-mark(:flag="member.answers[questions[item].name].is_agree==1")
					.winner__item(v-if="finished")
						usercontacts(:userdata="winner[0].userdata")
				.vendors__control.vendors__control--right(v-if="!winner_id")
					svg(xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 46" width="20" height="20")
						path(fill="#27d066" d="M11.003 40.094c-1.338 1.352-1.338 3.541 0 4.893 1.336 1.35 3.506 1.352 4.844 0l19.15-19.539c1.338-1.352 1.338-3.543 0-4.895L15.847 1.014c-1.338-1.352-3.506-1.352-4.844 0s-1.338 3.541-.002 4.893L26.706 23 11.003 40.094z").vendors__arrow
			.none-members(v-else)
				p.none-members--text На данный момент нет ни одного отклика
			section.notice-section(v-if="hasMembers")
				.conditions__item(v-if="members.filter((item)=>{return item.id===activeMemberId})[0].notice")
					p.conditions__cap Комментарий от поставщика	
					p.conditions__value {{members.filter((item)=>{return item.id===activeMemberId})[0].notice}}	
				.conditions__files
					p.conditions__cap Файлы от поставщика	
					.filelist-file
						.filelist-file__wrapper(v-for="item,i in members.filter((item)=>{return item.id===activeMemberId})[0].files" :key="i")
							.filelist-file__file(:style="addImage(item)" @click.prevent="viewImage(item)")
			modal-wrapper.wide-modal(v-if="viewDetailImage" @close="viewDetailImage=false")
				img(:src="viewingImage")
</template>
<script>
import BidPointMark from "./BidPointMark";
import iconFileLoad from "../../Icons/iconFileLoad";
import usercontacts from "./usercontacts";
import modalWrapper from '../../GeneralComponents/components/modal-wrapper.vue'
export default {
	components: {
		BidPointMark,
		usercontacts,
		iconFileLoad,
		modalWrapper
	},
	props: {
		activeMemberId: {
			type: [String, Number]
		},
		files:{
			type: [Object,Array]
		},
		members: {
			type: [Array, Object]
		},
		questions: {
			type: [Array, Object]
		},
		unit_for_all: {
			type: String
		},
		finished: {
			type: [String, Boolean]
		},
		winner_id: {
			type: [String, Number]
		}
	},
	directives: {
		addHeights: {
			inserted(el, bindings) {
				const container = el;

				function setKeys(obj) {
					return Object.keys(obj);
				}
				function getItems(arr) {
					arr.map(item => {
						let elements = container.querySelectorAll(`.${item}`);
						if (elements.length) {
							startCalculating(elements);
						}
					});
				}
				function getMax(arr) {
					let max = 0;
					for (let i = 0; i < arr.length; ++i) {
						if (arr[i].offsetHeight > max) {
							max = arr[i].offsetHeight;
						}
					}
					return max;
				}
				function startCalculating(arr) {
					let max = getMax(arr);
					arr.forEach(item => {
						item.style.height = `${max}px`;
					});
				}

				const keys = setKeys(bindings.value.questions);
				getItems(keys);
			}
		},
		slidescroll: {
			inserted(el, bindings) {
				const container = el;
				const scrollLeft = container.querySelector(
					`.${bindings.value.buttonLeft}`
				);
				const scrollRight = container.querySelector(
					`.${bindings.value.buttonRight}`
				);
				const scrollContainer = container.querySelector(
					`.${bindings.value.scrollElem}`
				);

				if (scrollLeft) {
					scrollLeft.addEventListener("click", () => {
						scroll("left");
					});
				}

				if (scrollRight) {
					scrollRight.addEventListener("click", () => {
						scroll("right");
					});
				}

				function scroll(direction) {
					switch (direction) {
						case "left":
							scrollContainer.scrollLeft -= 150;
							break;
						case "right":
							scrollContainer.scrollLeft += 150;
							break;
						default:
							break;
					}
				}
			}
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
	computed: {
		hasMembers() {
			return this.members.length > 0;
		},
		winner() {
			return this.members.filter(item => {
				return item.id == this.winner_id;
			});
		}
	},
	
	methods: {
	viewImage(fileData){
		this.viewingImage = fileData.file_full_path;
		this.viewDetailImage = true;
	},	
	addImage(file){		
			return `background: url(${file.file_full_path})`
		},
	addId(id) {
			return "vend" + id;
		}
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
			}
		}
	}
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
			margin: 0px 18px;
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

.winner__item {
	position: absolute;
	right: 0;
	.contact {
		max-width: 250px;
	}
}
.notice-section {
	grid-column: 1 / 6;
}
.none-members {
	// да, именно так
	grid-column: 13 / 10;
	&--text {
		text-align: center;
		color: $color-base-dark;
		box-shadow: $box-shadow-standart;
		border-radius: 32px;
		padding: 10px 20px;
	}
}
.choosepanel {
	display: grid;
	grid-column-gap: 60px;
	grid-template-columns: repeat(12, 1fr);
	padding-bottom: 30px;
	&__title {
		grid-column: 1 / 6;
		height: 65px;
		display: block;
		font-size: 18px;
    font-weight: 500;
		color: #000000;
	}
	&__conditions {
		grid-column: 1 / 6;
	}
	&__vendors {
		grid-column: 6 / 13;
		grid-row: 1 / 3;
		width: 100%;
	}
}
.conditions {
	font-size: 14px;
	width: 100%;
	color: #000000;
	&__item {
		// height: 65px;
		// overflow: hidden;
		padding: 10px 0;
		border-bottom: 1px solid $color-profile-border-line;
	}
	&__cap {
		font-weight: 500;
		font-size: 16px;
		margin-bottom: 2px;
	}
	&__value {
		overflow-x: hidden;
	}
	&__files{
		margin-top: 10px;
	}
}
.vendors {
	position: relative;
	&__list {
		display: flex;
		overflow-x: scroll;
		padding: 0 2px;
		scrollbar-width: none;
		scroll-behavior: smooth;
	}
	.vendors__list::-webkit-scrollbar {
		height: 0px;
	}
	&__item {
		width: 142px;
		flex-shrink: 0;
		&:not(:last-child) {
			margin-right: 13px;
		}
		background-color: $color-base-accent;
		border-radius: 10px;
		box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
	}
	&__control {
		z-index: 2;
		width: 30px;
		height: 30px;
		position: absolute;
		bottom: -40px;
		right: 0;
		background-color: #f1f1f1;
		display: flex;
		align-items: center;
		justify-content: center;
		&--left {
			right: 35px;
			svg {
				transform: rotate(180deg);
			}
		}
	}
	&__head {
		color: $color-base-light;
		font: $font-semibold;
		font-size: 14px;
		height: 65px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-around;
	}
	&__field {
		height: 65px;
		overflow: hidden;
		background-color: $color-base-light;
		padding: 10px 0;
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		&::after {
			content: "";
			position: absolute;
			background-color: $color-profile-border-line;
			width: 58%;
			height: 1px;
			bottom: 0;
			left: 50%;
			margin-left: -29%;
		}
	}
	.location {
		flex-direction: column;
		align-items: center;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
		&__region {
			padding: 0 10px;
			text-align: center;
			width: 90%;
			overflow-y: scroll;
		}
	}
	.cost {
		position: relative;
		&__price {
			margin-bottom: 5px;
		}
		&__icon {
			position: absolute;
			left: 50%;
			margin-left: -15px;
			top: 40px;
			& /deep/ svg {
				width: 30px;
				height: 17px;
			}
		}
		&--delivery {
			.cost__icon {
				display: block;
			}
		}
	}
	&__btn {
		input {
			display: none;
		}
		label {
			position: relative;
			width: 18px;
			height: 18px;
		}
		label::before {
			content: "";
			position: absolute;
			top: 3px;
			left: 0;
			width: 18px;
			height: 18px;
			border-radius: 50%;
			border: 2px solid $color-base-light;
			cursor: pointer;
		}
		input:checked + label::after {
			content: "";
			position: absolute;
			width: 10px;
			height: 10px;
			top: 7px;
			left: 4px;
			background-color: $color-base-light;
			border-radius: 50%;
		}
	}
}
</style>