<template lang="pug">
.categories(v-click-outside="outClose")
	.container
		.categories__inner
			button.m-menu-close(@click="outClose")
				<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
					<line x1="16" y1="1.41421" x2="2.41421" y2="15" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
					<line x1="1" y1="-1" x2="20.2132" y2="-1" transform="matrix(0.707107 0.707107 0.707107 -0.707107 2 0)" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
				</svg>
			header.m-menu-header
				h2.m-menu-header__title Категории
				a(href="#").m-menu-header__link 
					span Смотреть все категории
					feather(type="chevron-right")
			div.categories__list
				ul.categories-list
					li.categories-list__item
						button(
							@click.stop="selectCategory({id: null, name: 'Все категории'})"
						).category-label
							span.category__name Все категории
					li.categories-list__item(v-for="category in categoriesArray")
						button.category-label(
							:class="{'active': activeCategory.id==category.id}"
							@mouseover.stop.prevent="categoryHover(category)"
							@click.stop.prevent="selectCategory(category)"
							v-if="!isMobile"
						)
							span.category__name {{category.name}}
						button.category-label(
							v-else
							@click.stop.prevent="categoryHover(category)"
						)
							span.category__name {{category.name}}
			transition(name="shift-left")
				section.sub-mob-modal(v-if="subMobileShow")
					button.m-menu-close(@click="outClose")
						<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
							<line x1="16" y1="1.41421" x2="2.41421" y2="15" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
							<line x1="1" y1="-1" x2="20.2132" y2="-1" transform="matrix(0.707107 0.707107 0.707107 -0.707107 2 0)" stroke="#888888" stroke-width="2" stroke-linecap="round"/>
						</svg>
					button(@click.stop.prevent="closeSubMobile").sub-mob-modal__back
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

			div.categories__sublist
				transition(name="fade")
					ul.subcategories-list
						li.subcategories-item
							a.subcategories-name(
								href="-1"
								@click.stop.prevent="selectCategory({id:-1,name:''})"
								v-if="subCategories.length != 0"
							) Все подкатегории
						li(v-for="item in subCategories").subcategories-item
							a.subcategories-name(
								:class="{'active' : item.id == getBidCatState.currSub}"
								:href="item.id" 
								@click.stop.prevent="selectCategory(item)"
							) {{item.name}}
</template>

<script>
import { mapGetters, mapState } from "vuex";
// import subcategories from "./subcategories";
export default {
	components: {
		// subcategories,
	},
	data() {
		return {
			activeCategory: {
				id: null,
				name: null
			},
			subMobileShow: false
		};
	},
	beforeCreate() {
		// document.body.classList.add("body-overlay--transparent");
	},
	created() {
		window.addEventListener("click", event => {
			this.clickHandler(event);
		});

		const currentCategoryId = this.getCurrentValuePropertyByKey("category");
		if (currentCategoryId) {
			const parentId = this.getCategoryById(currentCategoryId).parent_id;
			this.activeCategory = this.getCategoryById(parentId || currentCategoryId);
		}
	},
	destroyed() {
		document.body.classList.remove("body-overlay--transparent");
	},
	methods: {
		selectCategory(category) {
			const result = category.id == -1 ? this.activeCategory : category;
			this.$emit("selectCategory", result);
		},
		clickHandler(event) {
			event.target.classList.contains("body-overlay--transparent") ?
				this.$emit("close") :
				"";
		},
		outClose() {
			this.$emit("close")
		},
		categoryClick(category) {
			if (this.activeCategory.id != category.id) {
				this.activeCategory = category;
			} else {
				this.activeCategory = {
					id: null,
					name: null
				};
			}
		},
		categoryHover(category) {
			this.activeCategory = category;

			if (this.isMobile) {
				this.subMobileShow = true;
			}
		},
		closeSubMobile() {
			this.subMobileShow = false;
		},
	},
	computed: {
		...mapState(["service"]),
		...mapState("categories", ["categoriesArray"]),
		...mapGetters("categories", [
			"getSubCategoriesByParentId",
			"getCategoryById",
			"getBidCatState"
		]),
		...mapGetters(["getCurrentValuePropertyByKey"]),
		subCategories() {
			return this.getSubCategoriesByParentId(this.activeCategory.id);
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

<style scoped lang="scss">
@import "~styleguide";
@import "~mixins";

.subcategories-wrap {
	margin: 0;
	display: flex;
}

.categories {
	
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
	
	&__inner {
		display: flex;
		position: relative;
		@media (max-width: 640px) {
			flex-direction: column;
			margin: 0 -15px;
		}
	}
	&__list {
		position: relative;
		flex-shrink: 0;
		padding-top: 21px;
		width: 30%;
		&::before {
			content: "";
			position: absolute;
			width: 300%;
			height: 100%;
			background: #F7F7F7;
			right: 0;
			top: 0;
		}
		@media (max-width: 992px) {
			width: 50%;
		}
		@media (max-width: 640px) {
			width: 100%;
			height: calc(100vh - 166px - 100px);
			padding-top: 0;
			&::before {
				display: none;
			}
		}
	}
	&__sublist {
		flex-grow: 2;
		padding-top: 21px;
		@media (max-width: 640px) {
			display: none;
		}
	}
	&-title {
		font: $font-regular;
		font-size: 20px;
		color: #000;
		max-width: 90%;
	}
}

.categories-list {
	list-style: none;
	margin: 0;
	overflow-y: auto;
	width: 100%;
	border-right: 1px solid $color-base-gray;
	height: auto;
	max-height: 690px;
	background: #F7F7F7;
	position: relative;
	@media (max-width: 640px) {
		height: 100%;
		background: #fff;
	}
	&__item {
		padding: 0;
		@media (max-width: 640px) {
			border-bottom: 1px solid #E6E6E6;
		}
	}
}
.sub-mob-modal {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: #fff;
	&__back {
		position: absolute;
		left: 20px;
		top: 20px;
	}
}
.category-label {
	display: flex;
	width: 100%;
	align-items: center;
	text-decoration: none;
	text-align: left;
	transition: color 0.2s ease, background 0.2s ease;
	min-height: 52px;
	padding: 5px 16px;

	&:hover,
	&:focus {
		outline: none;
		font-weight: 500;
	}

	&:hover {
		.category__icon-plus {
			opacity: 0;
		}

		.category__icon-minus {
			opacity: 1;
		}
	}

	&.active {
		background: #ffffff;
		.category__name {
			color: #2FC06E;
			font-weight: 600;
		}
	}
}

.category__name {
	font-weight: normal;
	font-size: 14px;
	line-height: 17px;
	color: #222222;
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

.subcategories {
	&-list {
		width: 100%;
		list-style-type: none;
		max-height: 80vh;
		height: auto;
		overflow-y: auto;
		column-count: 2;
		@media (max-width: 992px) {
			column-count: 1;
		}
		@media (max-width: 640px) {

		}
	}

	&-item {
		display: flex;
		align-items: center;
		padding-left: 60px;
		padding-right: 20px;
		min-height: 52px;
		padding-top: 5px;
		padding-bottom: 5px;
		
	}

	&-name {
		font-weight: 600;
		font-size: 14px;
		line-height: 17px;
		color: #222222;
		cursor: pointer;
		text-decoration: none;
		position: relative;
		&:hover {
			color: #888888;
		}
		&.active {
			color: #888888;
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