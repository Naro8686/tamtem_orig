<template lang="pug">
router-link(
	v-if='show'
	:id="'bidCardShort-'+ bid.id"
	:to="{name: 'bids.detail', params: {id: bid.id}}"
)
	article.shortcard-buy
		header.shortcard-buy__header
			h3.shortcard-buy__title 
				span.shortcard-buy__cap Заказ:&nbsp;
				span.shortcard-buy__name {{bid.name}}
			//- .card-header-icons
				.line
					.card-infograph(
							title='Предложения'
						)
							feather(type='user')
							span.text-small {{bid.count_response_active_after_moderate || 0}}
					.card-infograph(title='Просмотры')
						feather(type='eye')
						span.text-small {{bid.count_views}}
				//.line
					.card-infograph
						Favorite(
							:bid='bid'
							@removeFavorite='removeFavorite'
						)
			p.shortcard-buy__fav(@click.stop.prevent="toFav")
				<svg width="15" height="21" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M7.50031 16.5L15.0006 21V3C15.0006 2.20435 14.6845 1.44129 14.1219 0.87868C13.5593 0.31607 12.7962 0 12.0005 0H3.00013C2.20444 0 1.44135 0.31607 0.878716 0.87868C0.316084 1.44129 0 2.20435 0 3V21L7.50031 16.5ZM1.50006 18.351L7.50031 14.751L13.5006 18.351V3C13.5006 2.60218 13.3425 2.22064 13.0612 1.93934C12.7799 1.65804 12.3983 1.5 12.0005 1.5H3.00013C2.60228 1.5 2.22074 1.65804 1.93942 1.93934C1.6581 2.22064 1.50006 2.60218 1.50006 3V18.351Z" fill="#888888"/>
				</svg>
		section.shortcard-buy__body
			div.shortcard-buy__info(v-if="bid.questions")
				span(v-if='bid.questions && bid.questions.dqh_type_deal') 
					| {{bid.questions.dqh_type_deal.header}}: {{bid.questions.dqh_type_deal.question}}
				span(v-if='bid.questions && bid.questions.dqh_volume') 
					| {{bid.questions.dqh_volume.header}}: {{bid.questions.dqh_volume.question}} {{bid.unit_for_all}}
				span(v-if='bid.questions && bid.questions.dqh_min_party') 
					| {{bid.questions.dqh_min_party.header}}: {{bid.questions.dqh_min_party.question}}
			p.shortcard-buy__description(v-else-if='bid.description') {{bid.description}}
		footer.shortcard-buy__footer
			div.shortcard-buy__location 
				feather(type='map-pin')
				span.text-small {{bid.cities[0].region_name}}
			div.shortcard-buy__budget(v-if="bid.date_published")
				span
				span {{priceFormat(bid.budget_all)}} ₽
	//-Budget.deal-budget(:type='bid.type_deal' :from='bid.budget_from' 					:to='bid.budget_to'				//-)
</template>
<script>
import nophoto from "./nophoto";
import Favorite from "./Favorite";
import Budget from "./Budget";

export default {
	components: {
		Favorite,
		Budget,
		nophoto
	},
	data() {
		return {
			show: true
		};
	},
	props: {
		bid: Object,
		index: Number,
		type: String // favorites edit
	},
	computed: {
		cardClass() {
			let cls = "card bid-card-short";
			cls += " m-" + this.bid.type_deal;
			if (this.type) cls += " m-" + this.type;
			return cls;
		},
		linkDetail() {
			if (this.type === "edit" && this.$root.isAuth) return "lk.deals.edit";
			else return "bids.detail";
		},
		shortDescr() {
			if (this.bid.description)
				return this.bid.description.replace(/^(.{50}[^\s]*).*/, "$1");
			return null;
		}
	},
	methods: {
		deleteBid(evt) {
			this.$store.dispatch("setLoading", true);
			axios
				.post("/api/v1/deals/" + this.bid.id + "/finish")
				.then(resp => {
					this.$parent.removeFromList(this.index);
				})
				.catch(error => {
					this.printErrorOnConsole(error);
				})
				.then(() => {
					this.$store.dispatch("setLoading", false);
				});

			evt.preventDefault();
			evt.stopPropagation();
			return false;
		},
		removeFavorite(id) {
			if (this.type === "favorites") {
				this.show = false;
			}
		}
	},
	mounted() {}
};
</script>

<style scoped lang="scss">
@import "../../../../../sass/mixins.scss";
@import "../../../../../sass/styleguide.scss";

$manage-bar-height: 4.5rem;

* {
	@include hover-focus {
		outline: none;
		text-decoration: none;
	}
}

.viewed {
	.shortcard-buy {
		background: #F9F9F9;
		border: 1px solid #D6D6D6;
	}
}

.shortcard-buy {
	border-radius: 4px;
	background: #F9F9F9;
	border: 1px solid #2FC06E;
	min-height: 213px;
	height: 100%;
	display: flex;
	flex-direction: column;
	overflow: hidden;
	color: #000000;
	&:hover {
		box-shadow: 0px 0px 14px 0 rgba(148, 198, 216, 0.2);
	}
	&__header {
		padding: 20px 12px 0;
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 50px;
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
		path {
			fill: #888888;
		}
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
		word-wrap: break-all;
		min-height: 43px;
		font: $font-regular;
		font-size: $fontsize-base;
		color: black;
		line-height: 19px;
		max-height: 80px;
		overflow: hidden;
		display: flex;
		flex-direction: column;
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
				font-weight: 400;
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
		height: 48px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		position: relative;
		&::before {
			content: "";
			position: absolute;
			top: 0;
			left: 12px;
			right: 12px;
			height: 1px;
			background: #fff;
		}
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
		// color: #b2b2b2;
	}
}
</style>