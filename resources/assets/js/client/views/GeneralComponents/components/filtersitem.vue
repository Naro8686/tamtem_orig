<template lang="pug">
section.filters
	div.filters__container.container
		div.filters__inner
			//- categories
			div.filters__categories.categories
				button.categories__btn(
					@click.prevent="showCategories" 
					:class="{'opened': getCatMenuState.open && getCatMenuState.type == 'category'}") 
					span {{categoryName || 'Категории'}}
				
			//- regions
			div.filters__regions.regions
				button.regions__btn(
					@click.prevent="showRegions()" 
					:class="{'opened': getCatMenuState.open && getCatMenuState.type == 'region'}") 
					span {{ regionString || 'Регион'}}  
				
			//- search
			div.filters__search.search
				div.search__wrapper.input
					input.search__input(
						v-model="searchString"
						placeholder="Что вы хотите найти..."
						@keyup.enter="setSearch"
					)
			//- btn search
			button.filters__btn(@click="setSearch()") 
				span Найти
</template>
<script>
// import categoriesList from "../../GeneralComponents/components/categories";
import regionsList from "../../GeneralComponents/components/regions";
import {
	mapGetters,
	mapState,
	mapActions
} from "vuex";
import searchIconVue from "../../Icons/searchIcon.vue";
export default {
	components: {
		// categoriesList,
		regionsList
	},
	data() {
		return {
			isRegionsVisible: false,
			searchString: null,
			regionString: "",
			categoryString: "",
			type: ""
		};
	},
	watch: {
		getCurrentValue: {
			handler() {
				this.setValues();
			},
			deep: true
		}
	},
	methods: {
		...mapActions("categories", ["setCatMenu"]),
		...mapActions(["setNewVal"]),
		setValues() {
			this.searchString = this.getCurrentValue.search || null;

			if (this.getCurrentValue.category) {
				const categoryId = this.getCurrentValue.category;
				const category = this.getCategoryById(categoryId);
				this.categoryString = category ? category.name : null;
			} else {
				this.categoryString = null;
			}

			if (this.getCurrentValue.region) {
				const regionId = this.getCurrentValue.region;
				const region = this.getRegionById(regionId);

				this.regionString = region.name || null;
			} else {
				this.regionString = null;
			}
		},

		setRegion(region) {
			this.regionString = region.name;

			const currentVal = Object.assign({}, this.getCurrentValue);

			if (region.id) {
				this.$set(currentVal, "region", region.id);
			} else {
				this.$delete(currentVal, "region");
			}
			this.$set(currentVal, "page", 1);

			// вызов метода, объявленного через mapActions (вызывает action хранилища)
			this.setNewVal(currentVal);

			this.hideRegions();
			this.changeRoute();
		},
		setSearch() {
			const currentVal = Object.assign({}, this.getCurrentValue);

			if (this.searchString) {
				this.$set(currentVal, "search", this.searchString);
			} else {
				this.$delete(currentVal, "search");
			}

			this.$set(currentVal, "page", 1);

			// вызов метода, объявленного через mapActions (вызывает action хранилища)
			this.setNewVal(currentVal);
			this.changeRoute();
		},
		showCategories() {
			let open = !this.getCatMenuState.open;
			let menu = {
				open: open,
				type: 'category'
			}
			this.setCatMenu(menu);
			if(this.isMobile) {
				this.blockBodyScroll();
			}
		},
		showRegions() {
			// this.isRegionsVisible = !this.isRegionsVisible;
			let open = !this.getCatMenuState.open;
			let menu = {
				open: open,
				type: 'region'
			}
			this.setCatMenu(menu);
			if(this.isMobile) {
				this.blockBodyScroll();
			}
			
		},
		hideRegions() {
			this.isRegionsVisible = false;
			if(this.isMobile) {
				this.unblockBodyScroll();
			}
		},
		changeRoute() {
			const currentVal = Object.assign({}, this.getCurrentValue);
			if (this.type == 'buy' && this.isBidsListPage) {
				this.$router.push({
					name: "bids.list",
					query: currentVal
				})
			} else if (this.type == 'sell' && this.isSellsListPage) {
				this.$router.push({
					name: "sells.list",
					query: currentVal
				})
			} else {
				return ""
			}
		},
		blockBodyScroll() {
			document.body.classList.add("body-overflow")
		},
		unblockBodyScroll() {
			document.body.classList.remove("body-overflow")
		}
	},
	computed: {
		...mapState(["service"]),
		...mapGetters(["getDefaultValue", "getCurrentValue"]),
		...mapGetters("categories", ["getCategoryById", "getBidCatState", "getCatMenuState"]),
		...mapGetters("regions", ["getRegionById"]),
		isTablet() {
			return this.service.width > 768;
		},
		isDesktop() {
			return this.service.width > 992;
		},
		categoryName() {
			if (this.getCurrentValue.category) {
				const categoryId = this.getCurrentValue.category;
				const category = this.getCategoryById(categoryId);
				this.categoryString = category ? category.name : null;
			} else {
				this.categoryString = null;
			}
			return this.categoryString;
		},
		isBidsListPage() {
			return this.$route.name !== "bids.list";
		},
		isSellsListPage() {
			return this.$route.name !== "sells.list"
		},
		isMobile() {
			return this.service.width <= 640;
		}
	},
	created() {
		this.type = this.$route.meta.type
	}
};
</script>

<style lang="scss" scoped>
@import "~styleguide";

$black-lens: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNS4yODYiIGhlaWdodD0iMTUuMjg2IiB2aWV3Qm94PSIwIDAgMTUuMjg2IDE1LjI4NiI+ICAgIDxkZWZzPiAgICAgICAgPHN0eWxlPiAgICAgICAgICAgIC5jbHMtMXtmaWxsOm5vbmU7c3Ryb2tlOiMwMDA7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwfSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iR3JvdXBfMiIgZGF0YS1uYW1lPSJHcm91cCAyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSguNSAuNSkiPiAgICAgICAgPGNpcmNsZSBpZD0iT3ZhbCIgY3g9IjYuMzQ5IiBjeT0iNi4zNDkiIHI9IjYuMzQ5IiBjbGFzcz0iY2xzLTEiLz4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTTMuNDUzIDMuNDUzTDAgMCIgY2xhc3M9ImNscy0xIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxMC44MzQgMTAuODM0KSIvPiAgICA8L2c+PC9zdmc+";
$white-lens: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMi4xNjYiIGhlaWdodD0iMTIuMTY1IiB2aWV3Qm94PSIwIDAgMTIuMTY2IDEyLjE2NSI+ICAgIDxkZWZzPiAgICAgICAgPHN0eWxlPiAgICAgICAgICAgIC5jbHMtMXtmaWxsOm5vbmU7c3Ryb2tlOiNmZmY7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO3N0cm9rZS13aWR0aDoxLjVweH0gICAgICAgIDwvc3R5bGU+ICAgIDwvZGVmcz4gICAgPGcgaWQ9Ikdyb3VwXzIiIGRhdGEtbmFtZT0iR3JvdXAgMiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLjc1IC43NSkiPiAgICAgICAgPGNpcmNsZSBpZD0iT3ZhbCIgY3g9IjQuNzQiIGN5PSI0Ljc0IiByPSI0Ljc0IiBjbGFzcz0iY2xzLTEiLz4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTTIuNTc4IDIuNTc4TDAgMCIgY2xhc3M9ImNscy0xIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg4LjA4OCA4LjA4OCkiLz4gICAgPC9nPjwvc3ZnPg==";

.fade-modal-enter {
	transform: translateY(-50px);
	opacity: 0;
}

.fade-modal-enter-active {
	transition: transform 0.5s, opacity 0.2s;
	transition-delay: 0.2s;
}

.fade-modal-leave-active {
	transition: 0.2s;
}

.fade-modal-leave-to {
	opacity: 0;
	transform: translateY(-100px);
}

.filters {
	&__inner {
		position: relative;
		display: flex;
		align-items: center;
		border-radius: 15px;
		background-color: #fff;
		height: 58px;
		@media (max-width: 640px) {
			flex-wrap: wrap;
			height: auto;
			background-color: transparent;
		}
	}
	&__categories {
		height: 100%;
		width: 30%;
		@media (max-width: 640px) {
			width: 100%;
			height: 31px;
			flex-shrink: 0;
			margin-bottom: 10px;
			background-color: #fff;
			border-radius: 4px;
		}
	}
	&__regions {
		width: 30%;
		height: 100%;
		@media (max-width: 640px) {
			width: 100%;
			height: 31px;
			margin-bottom: 10px;
			background-color: #fff;
			border-radius: 4px;
		}
	}
	&__search {
		// width: 40%;
		flex-grow: 2;
		@media (max-width: 640px) {
			width: calc(100% - 36px);
			height: 31px;
			background-color: #fff;
			border-radius: 4px;
		}
	}
	&__btn {
		outline: none;
		min-width: 114px;
		height: 100%;
		background-color: $color-base-accent;
		color: #fff;
		font: $font-medium;
		font-size: $fontsize-base;
		text-align: center;
		border-top-right-radius: 15px;
		border-bottom-right-radius: 15px;
		position: relative;

		&:hover {
			background: $color-hover-button;
			border-color: $color-hover-button;
		}

		@media (max-width: 768px) {

		}
		@media (max-width: 640px) {
			width: 36px;
			height: 31px;
			min-width: 36px;
			span {
				display: none;
			}
			background-image: url(/images/icon-right-arrow.svg);
			background-repeat: no-repeat;
			background-position: center;
			border-radius: 4px;
		}
	}
	&--mobile {
		display: flex;
		margin-top: 21px;
		width: 100%;
		justify-content: space-between;

		&--button {
			outline: none;
			min-width: 137px;
			width: 40%;
			max-width: 240px;
			height: 30px;
			border: $border-btn-accent;
			border-radius: 35px;
			font: $font-regular;
			font-size: $fontsize-base;
			text-align: left;
			color: #000;
			padding: 0 17px;
			display: flex;
			align-items: center;
			justify-content: space-between;

			span {
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

			&.opened {
				&:after {
					transform: rotate(135deg);
					transition: transform 0.7s;
					margin-top: 5px;
				}
			}

			&:after {
				content: "\221F";
				transform: rotate(-45deg);
				float: right;
				font-weight: 500;
				transition: 0.7s;
				margin-top: -5px;
			}
		}
	}
}

.categories {
	&__btn {
		outline: none;
		width: 100%;
		height: 100%;
		font: $font-regular;
		font-size: $fontsize-base;
		text-align: left;
		color: #888;
		padding: 0 15px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-right: 1px solid #888888;
		@media (max-width: 640px) {
			border: none;
		}
		span {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		&.opened {
			&::after {
				transform: rotate(135deg);
				margin-top: 5px;
				transition: transform 0.7s;
			}
		}

		&::after {
			content: "\221F";
			transform: rotate(-45deg);
			float: right;
			font-weight: 500;
			transition: 0.7s;
			margin-top: -2px;
		}
	}
}
.regions {

	&__btn {
		width: 100%;
		height: 100%;
		border: none;
		padding: 0 15px;
		outline: none;
		font: $font-regular;
		font-size: $fontsize-base;
		text-align: left;
		color: #888;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-right: 1px solid #888888;

		span {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		&.opened {
			&::after {
				transform: rotate(135deg);
				transition: transform 0.7s;
				margin-top: 5px;
			}
		}

		&::after {
			content: "\221F";
			transform: rotate(-45deg);
			float: right;
			font-weight: 500;
			transition: 0.7s;
			margin-top: -2px;
		}

		@media (max-width: 640px) {
			border: none;
		}
	}
}
.search {
	display: flex;
	margin-left: 11px;
	@media (max-width: 640px) {
		margin-left: 0;
	}
	&__wrapper {
		width: 100%;
		position: relative;
		height: 100%;

		&.regions {
			max-width: 207px;

			@media (max-width: 992px) {
				// position: initial;
			}
		}

		@media (max-width: 768px) {
			
		}
	}

	&__input {
		width: 100%;
		height: 100%;
		padding-left: 15px;
		padding-right: 5px;
		font: $font-regular;
		font-size: $fontsize-base;
		outline: none;
		color: #000;

		@media (max-width: 768px) {
			
		}
		@media (max-width: 640px) {
			border: none;
		}
	}

}

// переход развертывания меню категорий
.roll-enter {
	height: 0;
}

.roll-enter-active {
	transition: height 1s;
	transition-delay: .3s;
	transition-timing-function: ease-in;
}

.roll-enter-to {
	height: 100vh;
}

.roll-leave {
	height: 100vh;
}

.roll-leave-active {
	transition-delay: .2s;
	transition: height .6s;
	transition-timing-function: ease-in-out;
}

.roll-leave-to {
	height: 0;
}
</style>