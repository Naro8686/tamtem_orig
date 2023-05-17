<template lang="pug">
section.catalog-menu
	transition(name="roll")
		categories-list(
			@close="hideCategories"
			@selectCategory="setCategory"
			v-if="getCatMenuState.open && getCatMenuState.type == 'category'"
		)
	transition(name="roll")
		regions-list(
			@close="hideRegions"
			@selectRegion="setRegion"
			v-if="getCatMenuState.open && getCatMenuState.type == 'region'"
		)
		//- div.catalog-menu__inner()
			
			

		//- v-if="getCatMenuState.type == 'category'"
</template>

<script>
import categoriesList from "../../GeneralComponents/components/categories";
import regionsList from "../../GeneralComponents/components/regions";
import {
	mapGetters,
	mapState,
	mapActions
} from "vuex";
export default {
	components: {
		categoriesList,
		regionsList
	},
	data() {
		return {
			isCategoriesVisible: false
		}
	},
	computed: {
		...mapState(["service"]),
		...mapGetters(["getDefaultValue", "getCurrentValue"]),
		...mapGetters("categories", ["getCategoryById", "getBidCatState", "getCatMenuState"]),
		isMobile() {
			return this.service.width <= 640;
		}
	},
	methods: {
		...mapActions("categories", ["bidCategoryAct", "setCatMenu"]),
		...mapActions(["setNewVal"]),
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
		setCategory(category) {
			this.categoryString = category.name;

			const currentVal = Object.assign({}, this.getCurrentValue);
			if (category.id) {
				this.$set(currentVal, "category", category.id);
			} else {
				this.$delete(currentVal, "category");
			}
			let bidcatstate = {
				open: true,
				name: category.name,
				id: category.id,
			}
			if(category.name == "Все категории") {
				bidcatstate.open = false
			}
			if(category.parent_id) {
				bidcatstate.pId = category.parent_id;
				bidcatstate.currSub = category.id;
			}
			this.$set(currentVal, "page", 1);

			// вызов метода, объявленного через mapActions (вызывает action хранилища)
			this.setNewVal(currentVal);

			this.bidCategoryAct(bidcatstate);
			
			this.hideCategories();

			this.changeRoute();
			
			let lastBidCatState = this.getBidCatState;
			if(localStorage) {
				localStorage.setItem(
					"bidCatState",
					JSON.stringify(lastBidCatState)
				);
			}

		},
		showCategories() {
			this.isCategoriesVisible = !this.isCategoriesVisible;
			if(this.isCategoriesVisible) {
				if(this.isMobile) {
					this.blockBodyScroll();
				}
			}
		},
		hideCategories() {
			// this.isCategoriesVisible = false;
			let cat = {
				open: false,
				type: 'category'
			}
			this.setCatMenu(cat);
			if(this.isMobile) {
				this.unblockBodyScroll();
			}
		},
		hideRegions() {
			let cat = {
				open: false,
				type: 'region'
			}
			this.setCatMenu(cat);
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
	}
}
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";

.catalog-menu {
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
}
</style>