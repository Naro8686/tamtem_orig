<template lang="pug">
	section.cr-ctgs
		.container.cr-ctgs__container
			div.cr-ctgs__inner
				ul.cr-ctgs__arrows
					li.cr-ctgs__arrow.cr-ctgs__arrow--left(ref="leftTrg" @click="moveLeft")
						span.cr-ctgs__arrow-icon
							<svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M7.69231 14.6667L0.581055 7.55557L7.69231 0.444458L8.58105 1.33319L2.35855 7.55557L8.58105 13.778L7.69231 14.6667Z" fill="#888888"/>
							</svg>
					li.cr-ctgs__arrow.cr-ctgs__arrow--right(ref="rightTrg" @click="moveRight")
						span.cr-ctgs__arrow-icon
							<svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1.80672 14.6667L8.91797 7.55557L1.80672 0.444458L0.917969 1.33319L7.14047 7.55557L0.917969 13.778L1.80672 14.6667Z" fill="#888888"/>
							</svg>
				div.cr-ctgs__box(ref="scrollElem")
					ul.cr-ctgs__list
						li.cr-ctgs__item(v-for='category in categoriesArray')
							button(@click.prevent="categoryClick(category)").cr-ctgs__link {{category.name}}
</template>

<script>
import { mapGetters, mapState, mapActions } from "vuex";
export default {
	data() {
		return {
		}
	},
	computed: {
		...mapGetters(["getDefaultValue", "getCurrentValue"]),
		...mapState("categories", ["categoriesArray"]),
		isBidsListPage() {
			return this.$route.name !== "bids.list";
		},
		isSellsListPage() {
			return this.$route.name !== "sells.list"
		}
	},
	methods: {
		...mapActions("categories", ["bidCategoryAct"]),
		...mapActions(["setNewVal"]),
		ctgOpen() {
			// this.toggle();
		},
		moveLeft() {
			this.$refs.scrollElem.scrollLeft -= 230;
		},
		moveRight() {
			this.$refs.scrollElem.scrollLeft += 230;
		},
		categoryClick(category) {
			let data = {
				open: true,
				name: category.name,
				id: category.id
			}
			if(category.name == "Все категории") {
				data.open = false
			}
			const result = category.id == -1 ? this.activeCategory : category;

			this.setCategory(result);

			this.bidCategoryAct(data);
		},
		setCategory(category) {
			const currentVal = Object.assign({}, this.getCurrentValue);
			if (category.id) {
				this.$set(currentVal, "category", category.id);
			} else {
				this.$delete(currentVal, "category");
			}
			this.$set(currentVal, "page", 1);

			// вызов метода, объявленного через mapActions (вызывает action хранилища)
			this.setNewVal(currentVal);
			this.changeRoute();
			
			let lastBidCatState = this.getBidCatState;
			if(localStorage) {
				localStorage.setItem(
					"bidCatState",
					JSON.stringify(lastBidCatState)
				);
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
		}
	},
	created() {
	}
}
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
.cr-ctgs {
	&__inner {
		position: relative;
		height: 52px;
		display: flex;
		align-items: center;
	}
	&__arrows {
		list-style: none;
		position: absolute;
		display: flex;
		justify-content: space-between;
		width: 100%;
		left: 0;
		@media(max-width: 768px) {
			display: none;
		}
	}
	&__arrow {
		@include w-h(50px);
		position: relative;
		display: flex;
		justify-content: center;
		align-items: center;
		cursor: pointer;
		&::before {
			@include w-h(35px);
			content: "";
			position: absolute;
			background: #E2EBFD;
			z-index: -1;
			transform: rotate(45deg);
			
		}
		&--left {
			margin-left: -70px;
			@media (max-width: 1200px) {
				margin-left: 0;
			}
		}
		
	}
	&__box {
		@media (max-width: 1200px) {
			margin: 0px 90px;
		}
		@media(max-width: 768px) {
			margin: 0;
			margin-right: 80px;
		}
		overflow: hidden;
		position: relative;
		margin-right: 90px;
		overflow-x: scroll;
		scrollbar-width: none;
		scroll-behavior: smooth;
		&::-webkit-scrollbar {
			height: 0px;
		}
		
		
	}
	&__list {
		list-style: none;
		display: flex;
		position: relative;
		
	}
	&__item {
		flex-shrink: 0;
		&:not(:last-child) {
			margin-right: 25px;
		}
	}
	&__link {
		font-style: normal;
		font-weight: normal;
		font-size: 16px;
		line-height: 20px;
		color: #000000;
		&:hover {
			color: rgba(0, 0, 0, 0.4);
		}
	}
}
</style>