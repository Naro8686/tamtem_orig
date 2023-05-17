<template lang="pug">
div(
	v-if='show'
	:class="cardClass"
	:id="'bidCardShort-'+ bid.id"
	@click="gotoDetailBid(bid.id)"
)
	//- .card-img-wrap(v-if="bid.type_deal == 'sell'")
		.card-img-head(v-if='bid.categories.length')
			img(
				v-if='bid.imagesDeals.length'
				:src='bid.imagesDeals[0].path' 
				alt=' '
			)
			img.img-nophoto(
				v-else
				src='/images/no-photo.svg' 
				alt=' '
		)
		.card-img-foot
			span &nbsp;
			.card-btn-favorite
				Favorite(
					:bid='bid'
					@removeFavorite='removeFavorite'
				)

	.card-body
		.card-manage-bar(v-if="type === 'edit' && $root.isAuth")
			b-btn(v-if="bid.type_deal=='sell'"
				class='m-edit'
				:to="{name: 'lk.deals.edit', params: {id: bid.id}}" 
				variant='link' 
			)
				span Открыть
			b-btn(
				v-else
				class='m-edit'
				variant='link disabled' 
			)
				span Открыть

			b-btn(
				class='m-remove'
				@click.prevent='deleteBid($event)' 
				variant='link' 
			)
				feather(type='x-square')
				span Удалить

		.d-flex.justify-content-between.align-items-center.mb-3(
			v-if="bid.type_deal == 'buy'"
		)
			h3.card-title Заказ:&nbsp; {{bid.name}}
			.card-btn-favorite.m-gray.text-right
				Favorite(
					:bid='bid'
					@removeFavorite='removeFavorite'
				)

		.d-flex.justify-content-between.align-items-center.mb-3
			.w-100.card-price
				Budget(
					:type='bid.type_deal'
					:from='bid.budget_from'
					:to='bid.budget_to'
				)

			.d-flex.align-items-center.flex-shrink-1
				.card-ifograph(
					title='Отклики'
				)
					feather(type='user')
					span {{bid.count_response_active_after_moderate}}
					span.new-responses.ml-2.mr-2(v-if="bid.count_unreaded_responses>0") +{{bid.count_unreaded_responses}}
				.card-ifograph.ml-3(title='Просмотры')
					feather(type='eye')
					span {{bid.count_views}}

		template(v-if="bid.type_deal == 'sell'")
			h3.card-title {{bid.name}}
		div.card-descr(v-if="bid.status!='moderation'")
			span(v-if='bid.questions && bid.questions.dqh_type_deal') {{bid.questions.dqh_type_deal.header}} : {{bid.questions.dqh_type_deal.question}}
			span(v-if='bid.questions && bid.questions.dqh_volume') {{bid.questions.dqh_volume.header}} : {{bid.questions.dqh_volume.question}} {{bid.unit_for_all}}
			span(v-if='bid.questions && bid.questions.dqh_min_party') {{bid.questions.dqh_min_party.header}} : {{bid.questions.dqh_min_party.question}}
		p.card-descr(v-else) {{bid.description}}

	.card-footer
		.card-ifograph
			feather(type='map-pin')
			span.text-small {{bid.cities[0].name}}
		//- .text-secondary.text-small.mr-3(v-if='bid.deadline_deal && dateFormat(bid.deadline_deal)') 
			|завершается {{dateFormat(bid.deadline_deal).toLowerCase()}}

		span.text-small(v-if="bid.date_published") {{dateFormat(bid.date_published.date)}}
			
</template>

<script>
import Favorite from "./Favorite";
import Budget from "./Budget";

export default {
	components: {
		Favorite,
		Budget
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
		}
	},
	methods: {
		gotoDetailBid(id) {
			// console.log('pusheeee')
			if (id && this.bid.date_published) {
				// console.log('push')
				this.$router.push({
					name: "bids.detail",
					params: {
						id: id
					}
				});
			}
		},
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
@import "../../../../../sass/base.scss";
@import "~styleguide";

$manage-bar-height: 4.5rem;

.new-responses {
	color: #fb336a;
	background-color: #feeef3;
	font-family: Montserrat;
	font-size: 12px;
	font-weight: normal;
	font-stretch: normal;
	font-style: normal;
	border-radius: 50%;
	padding: 3px 5px;
	box-shadow: 0 0 7px 0 #fb336a;
}

.bid-card-short {
	font: $font-regular;
	font-size: 14px;
	text-decoration: none;
	color: inherit;
	overflow: hidden;
	transition: $transition-card;
	box-shadow: $box-shadow;
	height: 100%;
	cursor: pointer;
	&.viewed {
		background-color: #f4f5f7;
	}

	&.m-edit {
		&.m-buy, &.m-sell {
			.card-body {
				margin-top: $manage-bar-height;
			}
		}

		.card-img-wrap {
			.card-img-foot {
				background: none;
			}
		}
	}

	.card-body {
		position: relative;
		padding-top: 1rem;
		padding-bottom: 0;
		min-height: 13rem;

		.card-ifograph {
			/deep/ .feather {
				color: $color-green-elements;
			}
		}
	}

	@include hover-focus {
		box-shadow: $box-shadow-fx;
		transform: translateY(-0.2rem);

		.card-btn-favorite {
			opacity: 1;
		}
	}

	&:visited {
		i.feather {
			&--eye {}
		}
	}

	.card-img-wrap {
		overflow: hidden;
		position: relative;
		height: 17rem;
		line-height: 17rem;
		text-align: center;
		font-size: 0;

		@include media-breakpoint-down(sm) {
			height: 14rem;
			line-height: 14rem;
		}

		img {
			@include imgCenter();

			&.img-nophoto {
				max-width: 7rem;
			}
		}

		.card-img-foot,
		.card-img-head {
			position: absolute;
			z-index: 2;
			left: 0;
			padding: 1rem;
			width: 100%;
			line-height: 1;
			font-size: $font-size-base;
		}

		.card-img-head {
			top: 0;
		}

		.card-img-foot {
			height: 7rem;
			display: flex;
			align-items: flex-end;
			justify-content: space-between;
			bottom: 0;
			background: linear-gradient(to bottom,
					rgba(0, 0, 0, 0) 0%,
					rgba(0, 0, 0, 0.1) 50%,
					rgba(0, 0, 0, 0.3) 100%);
		}
	}

	&>a {
		color: inherit;
		text-decoration: none;
	}

	.card-manage-bar {
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		position: absolute;
		left: 0;
		right: 0;
		top: -$manage-bar-height;
		z-index: 2;

		.btn {
			height: $manage-bar-height;
			line-height: $manage-bar-height;
			padding: 0 1.5rem;
			width: 100%;
			color: $white;
			// background: rgba($secondary, 0.3);
			background: $color-base-dark;
			border-radius: 0;
			transition: background 0.2s ease;
			text-decoration: none;

			@include hover-focus {
				background: rgba($color-base-dark, 0.6);
			}

			&.m-edit {
				// background: rgba($primary, 0.3);
				background: $color-base-accent;

				@include hover-focus {
					background: rgba($color-base-accent, 0.6);
				}
			}

			&.m-remove {}

			i.feather {
				color: inherit;
				width: 2.5rem;
				height: 2.5rem;
				margin-right: 1.5rem;
			}
		}
	}

	.badge {
		padding: 0;
		width: 3.5rem;
		height: 3.5rem;
		line-height: 3.5rem;
	}

	.tile {
		margin: 0;
	}

	.card-img-label {
		top: -9rem;
		position: absolute;
		font-size: 1.6rem;
		text-align: center;
		white-space: nowrap;
		color: #fff;
		width: 18rem;
		height: 18rem;
		line-height: 25rem;
		border-radius: 7rem;
		/* transform: rotate(-45deg); */

		&.m-text-lg {
			font-size: 2.6rem;
		}

		&.m-left {
			left: -9rem;
			padding-left: 7rem;
		}

		&.m-right {
			right: -9rem;
			padding-right: 7rem;
		}

		&.m-success {
			background: rgba($success, 0.85);
		}

		&.m-danger {
			background: rgba($danger, 0.85);
		}

		&.m-primary {
			background: rgba($primary, 0.85);
		}

		&>* {
			line-height: 1.4;
			display: inline-block;
			vertical-align: middle;
		}
	}

	.card-price {
		font-size: 1.7rem;
		white-space: nowrap;
		font-weight: 700;
		color: $color-base-dark;
	}

	.card-btn-favorite {
		&.m-gray {
			/deep/ .btn {
				color: $color-base-dark;
			}
		}

		/deep/ .btn {
			color: $white;

			&.active {
				i.feather {
					svg {
						fill: currentColor;
					}
				}
			}

			i.feather {
				width: 2.5rem;
				height: 2.5rem;

				svg {
					stroke-width: 0.2rem;
				}
			}
		}
	}

	.card-title {
		color: $color-text-dark;
		font-weight: 500;
		font-size: 1.7rem;
		margin: 0 0 0.5rem;
		line-height: 1.4;
		overflow: hidden;
		max-height: 4.7rem;
	}

	.card-descr {
		font-size: 1.4rem;
		color: $color-text-dark;
		line-height: 1.6;
		overflow: hidden;
		max-height: 84px;
		display: flex;
		flex-direction: column;
	}

	.card-footer {
		position: relative;
		background: none;
		border: none;
		font-size: 1.4rem;
		color: $color-base-dark;
		display: flex;
		align-items: flex-end;
		justify-content: space-between;
	}

	i.feather {
		color: $color-base-dark;
		vertical-align: middle;

		&--eye {}

		&--map-pin {
			color: $color-base-dark;
		}
	}

	&-views {
		text-align: right;
		white-space: nowrap;

		&>* {
			vertical-align: middle;
		}

		i.feather {
			margin-right: 0.5rem;
		}
	}

	.buttons-control {
		position: relative;
		width: 100%;
		padding-right: 1rem;
		display: flex;
		align-items: center;
		justify-content: space-between;

		.btn {
			width: 100%;

			@include media-breakpoint-down(md) {
				padding-top: 1.2rem;
				padding-bottom: 1.2rem;
			}
		}
	}
}
</style>