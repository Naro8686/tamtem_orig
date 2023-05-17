<template lang="pug">
.regions-wrap(v-click-outside="outClose")
	.container
		.regions-inner
			button.m-menu-close(@click="outClose")
				<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
					<line x1="16" y1="1.41421" x2="2.41421" y2="15" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
					<line x1="1" y1="-1" x2="20.2132" y2="-1" transform="matrix(0.707107 0.707107 0.707107 -0.707107 2 0)" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
				</svg>
			header.m-menu-header
				h2.m-menu-header__title Регионы
				a(href="#").m-menu-header__link 
					span Все регионы
					feather(type="chevron-right")
			div.regions-inner__countries.regions__col
				ul.regions-body
					li.regions-body-item
						button(
							@click="selectValue({id:null,name:'Россия'})"
						).regions-body-item__button Россия
						//- //- button(
						//- 	@click="showDisrictModal"
						//- 	v-else
						//- ).regions-body-item__button Россия
			//- transition(name="shift-left")
				section.sub-mob-modal(v-if="districtsModalVisible")
					button.m-menu-close(@click="outClose")
						<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<line x1="16" y1="1.41421" x2="2.41421" y2="15" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
							<line x1="1" y1="-1" x2="20.2132" y2="-1" transform="matrix(0.707107 0.707107 0.707107 -0.707107 2 0)" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
						</svg>
					button(@click.stop.prevent="closeDisrictModal").sub-mob-modal__back
						<svg width="31" height="16" viewBox="0 0 31 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M0.292891 7.29289C-0.0976334 7.68342 -0.0976334 8.31658 0.292891 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928932C7.68054 0.538408 7.04738 0.538408 6.65685 0.928932L0.292891 7.29289ZM31 7L0.999998 7V9L31 9V7Z" fill="#888888"/>
						</svg>

					header.m-menu-header
						h2.m-menu-header__title {{activeCategory.name}}
						a(href="#").m-menu-header__link 
							span Смотреть все товары этой категории
							feather(type="chevron-right")
					ul.categories-list
						li(v-for="sub in subCategories").categories-list__item
							button.category-label(
								@click.stop.prevent="selectCategory(sub)"
							) 
								span.category__name {{sub.name}}
			div.regions-inner__districts.regions__col
				ul.regions-body
					li.regions-body-item(
							v-for="item in regionsArray"
						)
						button(@click="selectValue(item)").regions-body-item__button {{item.name}}

</template>
<script>
import { mapGetters, mapState } from "vuex";
export default {
	data() {
		return {
			searchVal: "",
			districtsModalVisible: false
		};
	},
	beforeCreate() {
		// document.body.classList.add("body-overlay");
	},
	created() {
		window.addEventListener("click", event => {
			// this.clickHandler(event);
		});
	},
	destroyed() {
		// document.body.classList.remove("body-overlay");
	},
	methods: {
		clickHandler(event) {
			event.target.classList.contains("body-overlay") ?
				this.$emit("close") :
				"";
		},
		selectValue(item) {
			this.$emit("selectRegion", item);
		},
		outClose() {
			this.$emit("close")
		},
		showDisrictModal() {
			this.districtsModalVisible = true;
		},
		closeDisrictModal() {
			this.districtsModalVisible = false;
		},
	},
	computed: {
		...mapGetters("regions", ["getFilteredList"]),
		...mapState(["service"]),
		regionsArray() {
			return this.getFilteredList(this.searchVal);
		},
		isDesktop() {
			return this.service.width > 992;
		},
		isMobile() {
			return this.service.width <= 640;
		}
	}
};
</script>
<style lang="scss" scoped>
@import "~styleguide";
$lens-background-base64: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMi4xNjYiIGhlaWdodD0iMTIuMTY1IiB2aWV3Qm94PSIwIDAgMTIuMTY2IDEyLjE2NSI+ICAgIDxkZWZzPiAgICAgICAgPHN0eWxlPiAgICAgICAgICAgIC5jbHMtMXtmaWxsOm5vbmU7c3Ryb2tlOiMyZmMwNmU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO3N0cm9rZS13aWR0aDoxLjVweH0gICAgICAgIDwvc3R5bGU+ICAgIDwvZGVmcz4gICAgPGcgaWQ9Ikdyb3VwXzIiIGRhdGEtbmFtZT0iR3JvdXAgMiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLjc1IC43NSkiPiAgICAgICAgPGNpcmNsZSBpZD0iT3ZhbCIgY3g9IjQuNzQiIGN5PSI0Ljc0IiByPSI0Ljc0IiBjbGFzcz0iY2xzLTEiLz4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTTIuNTc4IDIuNTc4TDAgMCIgY2xhc3M9ImNscy0xIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg4LjA4OCA4LjA4OCkiLz4gICAgPC9nPjwvc3ZnPg==";

.regions {
	&-wrap {
		background-color: white;
		position: static;
		font: $font-regular;
		font-size: 13px;
		color: $color-text-bright;
		width: 100%;
		box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
		overflow: hidden;
		@media (max-width: 640px) {
			position: fixed;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			z-index: 15;
		}
	}

	&-inner {
		display: flex;
		@media (max-width: 640px) {
			flex-direction: column;
		}
		&__countries {
			position: relative;
			flex-shrink: 0;
			padding-top: 21px;
			margin-left: 30%;
			width: 20%;
			@media (max-width: 992px) {
				width: 50%;
				margin-left: 0;
			}
			@media (max-width: 640px) {
				width: 100%;
				padding-top: 0;
				&::before {
					display: none;
				}
			}
			&::before {
				content: "";
				position: absolute;
				width: 300%;
				height: 100%;
				background: #F7F7F7;
				right: 0;
				top: 0;
			}
			.regions-body-item__button {
				font-weight: normal;
				font-size: 14px;
				line-height: 17px;
				color: #222222;
				&:hover,
				&:active {
					background: #ffffff;
					color: #2FC06E;
					font-weight: 600;
				}
				@media (max-width: 640px) {
					padding-left: 0;
				}
			}
		}
		&__districts {
			width: 30%;
			flex-grow: 2;
			padding-top: 21px;
			@media (max-width: 640px) {
				width: 100%;
				padding-top: 0;
				// display: none;
			}
			.regions-body-item__button {
				font-weight: 600;
				font-size: 14px;
				line-height: 17px;
				color: #222222;
				display: flex;
				align-items: center;
				padding-left: 60px;
				padding-right: 20px;
				min-height: 52px;
				padding-top: 5px;
				padding-bottom: 5px;
				&:hover,
				&:active {
					color: #888888;
				}
				@media (max-width: 640px) {
					padding-left: 0;
				}
			}
		}
	}
	&-body {
		list-style-type: none;
		transition: height, 0.2s 0.5s;
		overflow-y: auto;
		max-height: 690px;
		position: relative;
		@media (max-width: 640px) {
			max-height: calc(100vh - 166px - 43px - 100px);
		}
		&-item {

			&__button {
				min-height: 43px;
				width: 100%;
				text-align: left;
				padding-left: 15px;
				outline: none;
				transition: background-color, 0.2s, 0.5s;
				
			}
		}
	}
}
.m-menu {
	&-close {
		position: absolute;
		outline: none;
		right: 24px;
		top: 18px;
	}
	&-header {
		padding: 75px 0 40px;
		height: 166px;
		@media (min-width: 641px) {
			display: none;
		}
		&__title {
			font-weight: 800;
			font-size: 22px;
			line-height: 28px;
			color: #2C3444;
			margin-bottom: 5px;
			text-align: center;
			max-width: 80%;
			margin-left: auto;
			margin-right: auto;
		}
		&__link {
			font-size: 10px;
			line-height: 14px;
			color: #0079B2;
			display: flex;
			align-items: center;
			justify-content: center;
			& /deep/ svg {
				width: 18px;
			}
		}
	}
}
.shift-left-enter {
	transform: translateX(-100%);
}

.shift-left-enter-active {
	transition: transform 1s;
	// transition-delay: .3s;
	transition-timing-function: ease-in;
}

.shift-left-enter-to {
	transform: translateX(0);
}

.shift-left-leave {
	transform: translateX(0);
}

.shift-left-leave-active {
	// transition-delay: .2s;
	transition: transform .6s;
	transition-timing-function: ease-in-out;
}

.shift-left-leave-to {
	transform: translateX(-100%);
}
</style>