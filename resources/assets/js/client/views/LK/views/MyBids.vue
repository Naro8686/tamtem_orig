<template lang="pug">
.container.my-bids
	header.my-bids__types
		ul.my-bids__types-list
			li
				button.my-bids__types-btn(
					@click="changeType('buy')"
					:class="{'active' : typeDeal == 'buy'}"
				) Заказы
			li
				button.my-bids__types-btn(
					@click="changeType('sell')"
					:class="{'active' : typeDeal == 'sell'}"
				) Объявления
	.mb-4
		b-tabs(
			v-model='tabInd'
			content-class='mt-4'
			@input="changeTab"
		)
			b-tab(
				title='Активные'
			)
				BidsList(
					v-if='tabInd == 0'
					type='actived'
					:typeDeal='typeDeal'
					@loadingSet='loadingSet'
				)

			b-tab(
				title='На модерации'
			)
				BidsList(
					v-if='tabInd == 1'
					type='moderate'
					:typeDeal='typeDeal'
					@loadingSet='loadingSet'
				)

			b-tab(
				title='Завершённые'
			)
				BidsList(
					v-if='tabInd == 2'
					type='finished'
					:typeDeal='typeDeal'
					@loadingSet='loadingSet'
				)

</template>

<script>
import BidsList from "../components/BidsList";
export default {
	components: {
		BidsList
	},
	data() {
		return {
			tabInd: 0,
			tabs: ["actived", "moderated", "finished"],
			typeDeal: "buy"
		};
	},
	computed: {},
	methods: {
		loadingSet(val) {
			this.$parent.loading = val;
		},
		changeTab() {
			this.setHash(this.tabs[this.tabInd]);
		},
		setHash(hash) {
			this.$router.replace({
				hash
			}).catch(() => {});
		},
		changeType(type) {
			this.typeDeal = type
		},
		init() {
			const name = this.$router.history.current.hash
				.replace("#", "")
				.split("&")[0];

			const ind = this.tabs.indexOf(name);

			this.$nextTick(() => {
				if (ind >= 0) {
					this.tabInd = ind;
				} else {
					this.tabInd = 0;
				}
				this.setHash(this.tabs[this.tabInd]);
			});

		}
	},
	created() {
		this.init();
	}
};
</script>

<style lang="scss" scoped>
@import "~styleguide";

.my-bids {
	& /deep/ .nav-tabs {
		// border-bottom: 0.1rem solid $color-base-dark;
		border-bottom: none;
	}
	& /deep/ .nav-item {
		&:not(:last-child) {
			margin-right: 15px;
		}
	}
	& /deep/ .nav-tabs .nav-link {
		color: $color-base-accent !important;
		height: 50px;
		display: flex;
		align-items: center;
		border: 1px solid $color-base-gray;
		border-radius: 32px;
		padding: 5px 20px;
		&:hover {
			color: $color-hover-button !important;
			border-color: $color-gray-dark;
		}
	}

	& /deep/ .nav-tabs .nav-link.active {
		color: $color-base-dark !important;
	}
	
	&__types {
		margin-bottom: 20px;
		&-list {
			display: flex;
			list-style: none;
		}
		&-btn {
			padding: 10px 20px;
			height: 50px;
			display: flex;
			align-items: center;
			color: #ffffff;
			background-color: $color-base-accent;
			margin-right: 15px;
			&:hover {
				background-color: $color-hover-button;
			}
			&.active {
				background-color: $color-base-dark;
			}
		}
		li:last-child {
			.my-bids__types-btn {
				margin-right: 0;
			}
		}
	}
}
</style>
