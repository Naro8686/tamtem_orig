<template lang="pug">
router-link(
	v-if='show'
	:id="'bidCardShort-'+ bid.id"
	:to="{name: 'sells.detail', params: {id: bid.id}}"
)
	article.shortcard-sell
		header.shortcard-sell__header
			h3.shortcard-sell__title 
				span.shortcard-sell__cap Объявление:&nbsp;
				span.shortcard-sell__name {{bid.name}}
			template(v-if='this.$root.isAuth')
				p.shortcard-sell__fav(@click.stop.prevent="toggleFav()")
					<svg width="15" height="21" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M7.50031 16.5L15.0006 21V3C15.0006 2.20435 14.6845 1.44129 14.1219 0.87868C13.5593 0.31607 12.7962 0 12.0005 0H3.00013C2.20444 0 1.44135 0.31607 0.878716 0.87868C0.316084 1.44129 0 2.20435 0 3V21L7.50031 16.5ZM1.50006 18.351L7.50031 14.751L13.5006 18.351V3C13.5006 2.60218 13.3425 2.22064 13.0612 1.93934C12.7799 1.65804 12.3983 1.5 12.0005 1.5H3.00013C2.60228 1.5 2.22074 1.65804 1.93942 1.93934C1.6581 2.22064 1.50006 2.60218 1.50006 3V18.351Z" :fill="bid.favourited ? '#2acc5a' : '#888888'"/>
					</svg>

		section.shortcard-sell__body
			p.shortcard-sell__description {{bid.description}}
			
		footer.shortcard-sell__footer
			div.shortcard-sell__location 
					feather(type='map-pin')
					span.text-small {{bid.cities[0].region_name}}
			div.shortcard-sell__date(v-if="bid.date_published")
				span
				span {{bid.date_published.date|dateTransform}}

</template>

<script>
import { mapActions } from 'vuex';

export default {
	data() {
		return {
			show: true
		};
	},
	filters: {
		dateTransform(rawData) {
			const data = rawData.split(" ")[0];
			if (data) {
				const [year, month, day] = data.split("-");
				return `${day}.${month}.${year}`;
			} else {
				const [year, month, day] = rawData.split("-");
				return `${day}.${month}.${year}`;
			}
		}
	},
	props: {
		bid: Object,
		index: Number,
		type: String // favorites edit
	},
	methods: {
		...mapActions("favourites", ["setCount"]),
		toggleFav() {
			if (this.bid.favourited) {
				axios
						.post('/api/v1/user/favourites/delete', {
							deal_id: this.bid.id
						})
						.then(resp => {
							this.bid.favourited = false;
						})
						.catch(error => {
							this.printErrorOnConsole(error);
						})
						.finally(() => {
							this.setCount();
						});
			} else {
				axios
						.post('/api/v1/user/favourites/store', {
							deal_id: this.bid.id
						})
						.then(resp => {
							this.bid.favourited = true;
						})
						.catch(error => {
							this.printErrorOnConsole(error);
						})
						.finally(() => {
							this.setCount();
						});
			}
		}
	}
}
</script>

<style scoped lang="scss">
@import "~styleguide";
@import "~mixins";
a {
	text-decoration: none;
}
.viewed {
//   background-color: $color-viewed-bid;
	.shortcard-sell__footer {
		background: #fff;
	}
}
.shortcard-sell {
	border-radius: 7px;
	border: solid 1px #dfdfdf;
	min-height: 213px;
	height: 100%;
	display: flex;
	flex-direction: column;
	overflow: hidden;
	color: #2E3647;
	&:hover {
		box-shadow: 0px 0px 14px 0 rgba(148, 198, 216, 0.2);
	}
	&__header {
		padding: 20px 12px 0;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	&__title {
		font-weight: 600;
		font-size: 16px;
		line-height: 20px;
		margin: 0;
	}
	&__name {
		font-size: inherit;
		font-weight: 600;
		line-height: inherit;
		color: #065FD4;
	}
	&__cap {
		// color: #92999f;
		font-weight: 600;
		font-size: inherit;
		line-height: inherit;
	}
	&__fav {
		position: relative;
		z-index: 2;
	}
	&__body {
		padding: 12px;
		flex-grow: 2;
		display: flex;
		flex-direction: column;
	}
	&__description {
		font-style: normal;
		font-weight: normal;
		font-size: 15px;
		line-height: 22px;
		margin-bottom: 22px;
		overflow: hidden;
		height: 80px;
		@media(max-width: 640px) {
			font-size: 14px;
			line-height: 22px;
		}
	}
	&__info {
		display: flex;
		justify-content: space-between;
		margin-top: auto;
	}
	&__location {
		display: flex;
		align-items: center;
		.feather {
			color: #2E3647;
		}
		.text-small {
			font-weight: 500;
			font-size: 14px;
			line-height: 17px;
			@media(max-width: 640px) {
				font-size: 12px;
				line-height: 15px;
			}
		}
	}
	&__icons {
		display: flex;
	}
	&__icon {
		display: flex;
		align-items: center;
		&:not(:last-child) {
			margin-right: 8px;
		}
		.feather {
			color: #2acc5a;
		}
		.text-small {
			margin-left: 4px;
			font-size: 13px;
			line-height: 16px;
		}
	}
	&__footer {
		margin-top: auto;
		padding: 5px 12px;
		background-color: #E6E6E6;
		height: 48px;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	&__date {
		font-size: 12px;
		font-weight: 600;
		line-height: 1.25;
		text-align: center;
		@media(max-width: 640px) {
			font-size: 12px;
			line-height: 15px;
		}
	}
}
</style>

