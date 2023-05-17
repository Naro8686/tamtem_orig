<template lang="pug">
.my-bids-list
	ul.row.row-small.list-unstyled(v-if="dealList && dealList.items && dealList.items.length")
		li.col-md-6.mb-4(
			v-for='(bid, ind) in dealList.items' 
			:key='bid.id'
		)
			BidCardShort(
				:bid='bid'
				:index='ind'
				type='edit'
			)
	p(v-else) Ничего не найдено

	.text-center.mb-5(v-if='dealList && !!dealList.hasMore')
		b-button(
			variant='outline-primary'
			@click='getMore()'
		) 
			span(v-if='isLoadingMore') Загрузка...
			span(v-else) Загрузить ещe

</template>

<script>
import BidCardShort from "../../GeneralComponents/components/BidCardShort";
import BidCardShortSell from "../../GeneralComponents/components/BidCardShortSell";

export default {
	components: {
		BidCardShort,
		BidCardShortSell
	},
	props: {
		type: String,
		typeDeal: String
	},
	data() {
		return {
			isLoadingMore: false,
			dealList: null,
			nextPage: 2,
			// typeDeal: "buy"
		};
	},
	computed: {
		requestLink() {
			let link = `status=active&type_deal=${this.typeDeal}`;
			switch (this.type) {
				case "active":
					link = `status=active&type_deal=${this.typeDeal}`;
					break;
				case "moderate":
					link = `status=moderation&type_deal=${this.typeDeal}`;
					break;
				case "finished":
					link = `status=archive&finish=1&type_deal=${this.typeDeal}`;
					break;
				case "blocked":
					link = `status=banned&finish=1&type_deal=${this.typeDeal}`;
					break;
			}
			return link;
		},
	},
	watch: {
		typeDeal() {
			this.load()
		}
	},
	methods: {
		load() {
			this.$emit("loadingSet", true);
			axios
				.get("/api/v1/deals/user/list?page=1&" + this.requestLink)
				.then(resp => {
					this.dealList = resp.data.data;
				})
				.catch(error => {
					this.printErrorOnConsole(error);
				})
				.then(() => {
					this.$emit("loadingSet", false);
				});
		},
		getMore() {
			this.isLoadingMore = true;
			this.$emit("loadingSet", true);
			axios
				.get(
					"/api/v1/deals/user/list?page=" +
					this.nextPage +
					"&" +
					this.requestLink
				)
				.then(resp => {
					this.dealList.items = this.dealList.items.concat(
						resp.data.data.items
					);
					this.dealList.hasMore = resp.data.data.hasMore;
					this.nextPage++;
				})
				.catch(error => {
					this.printErrorOnConsole(error);
				})
				.then(() => {
					this.$emit("loadingSet", false);
					this.isLoadingMore = false;
				});
		},
		removeFromList(index) {
			this.dealList.count = this.dealList.count--;
			this.dealList.items.splice(index, 1);
		},
	},
	created() {
		this.load();
	}
};
</script>

<style lang="scss" scoped>

@import "~styleguide";
.my-bids-list {
  &-filter {
    margin-bottom: 1.5rem;
    .btn {
      padding-left: 2.5rem;
      padding-right: 2.5rem;
      border-color: $color-gray-dark;
      color: $color-gray-dark;
      background: $color-base-light;

      &.active {
        background: $color-base-light;
        border-color: $color-base-dark;
        color: $color-base-dark;
      }

      & + .btn {
        margin-left: 1.5rem;
      }
    }
  }
}
</style>