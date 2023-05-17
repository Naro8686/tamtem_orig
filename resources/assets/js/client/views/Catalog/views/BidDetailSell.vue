<template lang="pug">
section.bid-detail(v-if='data.id')
	.bids-search__intro.bids-intro
		.container
			.bids-intro__inner
				h1.bids-intro__title Биржа оптовых #[br] продаж tamtem.ru
				div.bids-intro__picture
					img(src="/images/bidlist/intro-pic.png")
		.container
			.bids-intro__filters
				filtersItem
	article.bid.bid--buy
		header.bid__header.bid-header
			.container
				.row
					.col.bid-header__box
						.bid-header__goback
							Goback(:cls="$route.name.replace('.','-')")
						.bid-header__title
							h1 Объявление:&nbsp; #[br] {{data.name}}
						.bid-header__viewed(title="Просмотров")
							feather(type='eye')
							span {{data.count_views}}
		section.bid__body.bid-body
			div.bid-body__backing
				.container
					.row
						.col.bid-body__box.bid-body__box--sell
							section.bid-body__content
								div.bid-body__description {{data.description}}
								div.bid-body__uploaded(v-if="isAuth && data.files && data.files.length>0")
									div(v-for="file in data.files").uploaded-file
										a.uploaded-file__wrapper(:href="file.path")
											.uploaded-file__icon
												icon-file-load(variant="loaded")
											span.uploaded-file__name {{file.name}} 
							section.bid-body__card.bidcard.bidcard--transparent(v-if="!isAuth")
								div.bidcard__enter(v-if="!isAuth")
									p Впервые на сайте?
									a(href="/signup" @click.prevent="registrationClick()").to-reg Зарегистрироваться
									span Войдите, чтобы продолжить сотрудничество
									a(href="/login" @click.prevent="signinClick()").to-enter Войти
							usercontacts(
								:canCreateDialog="true" 
								:userdata="data.userdata" 
								v-if="isAuth"
							)
		footer.bid__footer.bid-footer
			.container
				.row
					.col.bid-footer__box
						.bid-footer__copy.bid-copylink
							.divider
							//-a(href="javascript:void(0);" @click="linkCopy()").bid-copylink__link
								span.bid-copylink__icon
									icon-link
								span.bid-copylink__txt Копировать ссылку на заказ
						.bid-footer__tags.tags
							p.bid-category {{data.categories[0].name}}
						.bid-footer__row
							.bid-footer__favorite.favorite
								Favorite(:bid='data')
						.bid-footer__expiration(v-if="data.date_published") Добавлена {{data.date_published.date|dateTransform}}
		section.bid__map.bid-map
			.container
				.row
					.col
						.bid-map__control
							p(
								href='javascript:void(0)'
								class='bid-map__show'
							)
								feather(type='map-pin')
								b {{data.cities[0].region_name}}

</template>

<script>
import { Validator } from "vee-validate";
import viewedbids from "../../../mixins/viewedbids.mixin";
import Goback from "../../GeneralComponents/components/Goback";
import Retreat from "../../GeneralComponents/components/retreatBid";
import Favorite from "../../GeneralComponents/components/Favorite";
import BidChoosePanel from "../components/BidChoosePanel";
import BidChoosePanelMobile from "../components/bidChoosePanelMobile";
import usercontacts from "../components/usercontacts";
import modalConfirm from "../components/modalConfirm"
import iconFileLoad from "../../Icons/iconFileLoad";
import iconLink from "../../Icons/iconLink";
import iconTick from "../../Icons/iconTick";
import iconInformation from "../../Icons/iconInformation";
import iconCardpayment	 from "../../Icons/iconCardpayment";
import iconDogovor	 from "../../Icons/iconDogovor";
import { mapActions, mapState } from "vuex";
export default {
	mixins: [viewedbids],
	components: {
		Goback,
		iconFileLoad,
		iconLink,
		iconTick,
		iconInformation,
		iconCardpayment,
		iconDogovor,
		Favorite,
		BidChoosePanel,
		BidChoosePanelMobile,
		usercontacts,
		Retreat,
		modalConfirm,
	},
	props: {
		bidData: {
			type: Object,
			required: true
		}
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
	data() {
		return {
			viewDetailImage: false,
			viewingImage: null,
			maxUploadedFilesCount: 3,
			files:[],
			paymentSlug: "payment_once_deal_buy_get_contacts",
			data: {},
			windowWidth: window.innerWidth,
			map: null,
			service: {},
			startingBidState: 2,
			activeMemberId: null,
			activeMember: null,
			isLinkCopied: false,
			// length of all items with checkboxes
			checkboxesArrayLength: 8,

		};
	},
	computed: {
		isAuth() {
			return this.$root.isAuth;
		},
	},
	methods: {
		signinClick() {
			this.yandexReachGoal("vhod_glavnaya");
			this.googleReachGoal("vhod_glavnaya");
			this.$root.$emit("showForm", "authorizationForm");
		},
		registrationClick() {
			this.$root.$emit("showForm", "registrationForm");
		},
	},
	mounted() {
		this.data = this.bidData;
	}
}
</script>

<style scoped lang="scss">
@import "~styleguide";
@import "~mixins";
.filelist{
	display: flex;
	&-file{	
		&__wrapper{
			position: relative;
			width: 92px;
			height: 92px;
			border: 1px solid $color-base-accent;
			border-radius: $border-radius-small;
			margin: 0px 18px;
			&:first-child{
				margin-left: 0;
			}
			&:last-child{
				margin-right: 0;
			}
		}
		&__file{
			width: 100%;
			height: 100%;
			background-position: center !important;
			background-repeat: no-repeat !important;
			background-size: 100% !important;
		}
		&__remove-button{
			position: absolute;
			right: -10px;
			top: -10px;
			background:#fff;
			font-weight: 600;
			color:	$color-text-gray;
			box-shadow: $box-shadow-standart;
			padding: 0 6px;
			border-radius: 50%;
		}
	}
}

.uploaded-file {
	&:not(:last-child) {
		margin-bottom: 10px;
	}
	&__wrapper{
		display: flex;
		align-items: center;
		// padding: 15px 0;
	}
	&__icon{
		margin-right: 20px;
	}
	&__name{
		font: $font-regular;
		font-size: $fontsize-smaller;
		color: #000;
	}
	&__size{
		font: $font-regular;
		font-size: $fontsize-smaller;
		color: $color-text-gray;
	}
}
.errors {
	display: block;
	margin-top: -1rem;
	font-size: 12px;
	color: $danger;
	margin-bottom: 0.2rem;
	&.cmnt {
		margin-top: 0;
	}
	input,
	textarea {
		border-color: #ff564b !important;
	}
}

.bid-detail {
	position: relative;
	font-family: $font-family;
	font-size: 14px;
	font-weight: 400;

	& /deep/ i.feather {
		vertical-align: middle;
		width: 2.5rem;
		height: 2.5rem;
		&--map-pin {
			width: 1.6rem;
			height: 1.6rem;
		}
	}
	.map#map {
		height: 25rem;
	}
}
.bid {
	&__body {
		margin-top: 30px;
		@media (max-width: 768px) {
			margin: 0;
		}
	}
	&__footer {
		margin-top: 20px;
	}
}
.favorite {
	display: flex;
	align-items: center;
	& /deep/ .btn-favorite {
		color: $color-base-light;
		&.active {
			svg {
				fill: $color-base-light;
			}
		}
	}
}
.bid-header {
	background-color: $color-base-accent;
	color: $color-base-light;
	padding-top: 38px;
	padding-bottom: 30px;
	&__box {
		display: grid;
		grid-template-columns: 46px 1fr 1fr 1fr auto auto;
		grid-column-gap: 10px;
		grid-row-gap: 10px;
		grid-template-areas:
			"goback title title responses eye star";
		@media (max-width: 768px) {
			grid-template-columns: 46px 1fr 1fr 1fr auto auto auto;
			grid-template-areas:
				"goback title title title . responses eye";
		}
		@media (max-width: 420px) {
			grid-row-gap: 0;
			grid-template-columns: repeat(6, 1fr);
			grid-template-rows: 25px auto;
			grid-template-areas:
				"goback . . . eye responses"
				"title title title title title title";
		}
	}
	&__goback {
		position: relative;
		grid-area: goback;
		& /deep/ .goback {
			@include media-breakpoint-down(xxl) {
				position: relative;
				left: 0;
				float: left;
				margin-right: 1.5rem;
			}
			opacity: 1;
			a {
				border: 2px solid $color-base-light;
				color: $color-base-light;
				height: 30px;
				width: 36px;
				:hover,
				:focus {
					color: $color-base-light;
				}
				@media (max-width: 420px) {
					height: 25px;
					width: 31px;
				}
			}
		}
		&::after {
			content: "";
			display: block;
			clear: both;
		}
	}
	&__title {
		grid-area: title;
		h1 {
			margin: 0;
			font: $font-medium;
			font-size: 32px;
			line-height: 27px;
			// word-break: break-all;
			-moz-hyphens: auto;
			-webkit-hyphens: auto;
			-ms-hyphens: auto;
			hyphens: auto;
			@media (max-width: 768px) {
				font-size: 26px;
			}
			@media (max-width: 420px) {
				// font-size: 20px;
				font-size: 18px;
				text-align: center;
				margin: 5px 0;
			}
		}
		br {
			@media (min-width: 421px) {
				display: none;
			}
		}
	}
	&__budget {
		grid-area: budget;
		font-size: 17px;
		font-weight: 600;
		@media (max-width: 420px) {
			justify-self: center;
			font-size: 16px;
			margin-top: 15px;
		}
	}
	&__period {
		grid-area: period;
		font-size: 14px;
		font-weight: 500;
		@media (max-width: 420px) {
			justify-self: center;
		}
	}
	&__responses {
		grid-area: responses;
		justify-self: end;
		display: flex;
		align-items: center;
		padding-left: 12px;
		span {
			margin-left: 7px;
		}
		@media (max-width: 420px) {
			align-self: center;
			justify-self: end;
			font-size: 12px;
			& /deep/ svg {
				width: 19px;
				height: 17px;
			}
		}
	}
	&__viewed {
		grid-area: eye;
		justify-self: end;
		display: flex;
		align-items: center;
		padding-left: 12px;
		span {
			margin-left: 7px;
		}
		@media (max-width: 420px) {
			align-self: center;
			justify-self: end;
			font-size: 12px;
			& /deep/ i.feather {
				width: 26px;
				height: 21px;
			}
		}
	}
	&__favorite {
		grid-area: star;
		padding-left: 12px;
		@media (max-width: 768px) {
			display: none;
		}
	}
	&__accept {
		grid-area: accept;
		align-self: center;
		justify-self: end;
		@media (max-width: 420px) {
			justify-self: center;
			margin-top: 20px;
		}
	}
	&__vendors {
		grid-area: vendors;
		margin-top: 25px;
	}
	.accept {
		&__btn {
			display: flex;
			justify-content: center;
			align-items: center;
			max-width: 226px;
			padding: 11px 20px;
			font: $font-semibold;
			font-size: 14px;
			color: $color-base-accent;
			background-color: $color-base-light;
			border-radius: 32px;
			@media (max-width: 420px) {
				font-size: 12px;
			}
			&:hover {
				color: $color-pale-green;
			}
		}
	}
	.vendors {
		&__list {
			display: flex;
			list-style: none;
			flex-wrap: wrap;
			overflow-x: scroll;
			scrollbar-width: none;
			@media (max-width: 420px) {
				flex-wrap: nowrap;
			}
		}
		.vendors__list::-webkit-scrollbar {
			height: 0px;
		}
		&__item {
			margin-right: 10px;
			margin-bottom: 10px;
			@media (max-width: 420px) {
				margin-right: 3px;
				flex-shrink: 0;
			}
		}
		&__btn {
			width: 96px;
			display: flex;
			align-items: center;
			justify-content: center;
			color: $color-base-light;
			font-size: 10px;
			border-radius: 15px;
			border: 1px solid $color-base-light;
			padding: 4px 0;

			&:hover {
				color: $color-base-light;
			}
			&--active {
				background-color: $color-base-light;
				color: $color-base-dark;
				border-color: $color-base-light;
				&:hover {
					color: $color-base-accent;
				}
			}
		}
	}
}
.bid-body {
	@media (max-width: 768px) {
		background-color: $color-base-accent;
	}
	&__backing {
		@media (max-width: 768px) {
			background-color: $color-base-light;
			border-radius: 32px;
			padding: 20px 0 40px;
		}
	}
	&__box {
		display: grid;
		&--sell {
			grid-template-columns: repeat(6, 1fr);
			grid-template-areas: "conts conts conts . card card";
			@media (max-width: 768px) {
				row-gap: 30px;
				grid-template-areas:
					"conts conts conts conts conts conts"
					"card card card card card card";
			}
		}
	}
	&__content {
		grid-area: conts;
		width: 100%;
	}
	&__upload {
		margin-top: 20px;
	}
	&__uploaded {
		padding: 15px 0;
	}
	&__card {
		width: 309px;
		grid-area: card;
		justify-self: end;
		align-self: start;
	}
	/deep/ &__contact {
		grid-area: card;
		justify-self: end;
		align-self: start;
		width: 309px;
		display: flex;
		flex-direction: column;
		align-items: center;
		@media (max-width: 768px) {
			margin-bottom: 70px;
			grid-area: card;
			width: 100%;
			display: grid;
		}
	}
	.upload {
		input {
			display: none;
		}
		label {
			cursor: pointer;
		}
		&__text {
			@media (max-width: 420px) {
				display: none;
			}
		}
		&__icon {
			border: 2px dotted $color-border-gray;
			border-radius: 50%;
			padding: 9px;
			margin-right: 20px;
			position: relative;
			font-style: normal;
			&::before {
				content: "+";
				font-size: 20px;
				position: absolute;
				right: 0;
				bottom: -7px;
				color: $color-base-accent;
			}
		}
		@media (max-width: 420px) {
			text-align: center;
		}
	}
	.bidcard {
		display: flex;
		flex-direction: column;
		align-items: center;
		@media (max-width: 768px) {
			width: 100%;
			justify-self: center;
			margin-bottom: 100px;
			margin-top: 20px;
		}
		&__enter {
			text-align: center;
			color: #000;
			background: $color-base-gray-light;
			border: 1px solid $color-border-gray;
			border-radius: 10px;
			padding: 38px 20px 56px;
			display: flex;
			flex-direction: column;
			align-items: center;
			p {
				text-align: center;
				margin-bottom: 35px;
			}
			.to-reg {
				@include flex-centerizer;
				@include w-h(198px, 43px);
				background: $color-pale-green;
				color: $color-base-accent;
				border-radius: 32px;
				margin-bottom: 40px;
				text-decoration: none;
				font-weight: 500;
			}
			span {
				display: inline-block;
				color: $color-text-gray;
				text-align: center;
				margin-bottom: 4px;
			}
			.to-enter{
				color: $color-base-accent;
				text-decoration: none;
				font-weight: 500;
			}
		}
		&__box {
			position: relative;
			display: flex;
			flex-direction: column;
			align-items: center;
			@media (max-width: 768px) {
				width: 100%;
			}
		}
		&__title {
			font-size: 17px;
			color: $color-base-dark;
			align-self: center;
			margin-bottom: 20px;
			text-align: center;
			@media (max-width: 768px) {
				grid-area: title;
				margin-bottom: 0;
				align-self: start;
				text-align: left;
			}
		}
		&__desc {
			font-size: 14px;
			line-height: 19px;
			color: $black;
			margin-top: 10px;
			margin-bottom: 20px;
			text-align: center;
			@media (max-width: 768px) {
				grid-area: desc;
				margin: 0;
				align-self: start;
				text-align: left;
			}
			@media (max-width: 420px) {
				font-size: 12px;
				margin-bottom: 10px;
			}
		}
		&__price {
			font: $font-semibold;
			font-size: 14px;
			background-color: $color-pale-green;
			color: $color-base-dark;
			border-radius: $border-radius-standart;
			display: flex;
			justify-content: center;
			align-items: center;
			width: 100%;
			min-height: 32px;
			margin-bottom: 15px;
			@media (max-width: 768px) {
				grid-area: price;
				align-self: start;
				margin: 0;
			}
		}
		&__btn {
			background-color: $color-base-accent;
			color: $color-base-light;
			border-radius: 32px;
			font-size: 14px;
			min-width: 164px;
			min-height: 43px;
			margin: 20px 0;
			display: flex;
			justify-content: center;
			align-items: center;
			&--grey {
				color: #000000;
				background-color: #ececec;
				font-weight: 500;
			}
			@media (max-width: 768px) {
				margin: 0;
				position: absolute;
				bottom: -95px;
				width: 188px;
				left: 50%;
				margin-left: -94px;
			}
		}
		&__cap {
			font-size: 11px;
			line-height: 14px;
			color: $black;
			text-align: center;
			@media (max-width: 768px) {
				position: absolute;
				bottom: -128px;
				width: 200px;
				left: 50%;
				margin-left: -100px;
			}
		}
		&__card-title {
			align-self: flex-end;
			font-size: 10px;
			color: $black;
			margin-bottom: 15px;
			@media (max-width: 768px) {
				font-size: 13px;
				color: $color-base-dark;
				margin: 0;
				grid-area: tarif;
				justify-self: end;
				align-self: start;
			}
		}
		&--sticky{
			position: sticky;
			top: 10px;
		}
		&--transparent {
			border: none;
			box-shadow: none;
			padding: 0;
		}
		&--greyed {
			background-color: #f6f6f6;
			border: solid 1px #ececec;
			padding: 45px 21px 55px 22px;
			border-radius: 10px;
			position: relative;
			width: 100%;
			@media (max-width: 768px) {
				padding: 20px 20px;
				background-color: transparent;
			}
			.bidcard__box-name {
				position: absolute;
				top: 10px;
				right: 10px;
				font-size: 10px;
				color: inherit;
				text-decoration: none;
			}
			.bidcard__pic {
				margin-bottom: 48px;
				border: 1px solid #ececec;
				background: #fff;
				border-radius: 6px;
				height: 60px;
				width: 80%;
				@media (max-width: 768px) {
					display: none;
				}
				img {
					transform: translate(-31px, -48px);
				}
			}
			.bidcard__title {
				color: $color-text-gray;
				font-size: 14px;
				font-weight: 400;
				text-align: center;
				margin-bottom: 10px;
				margin-top: 15px;
			}
			.bidcard__desc {
				color: $color-text-gray;
			}
			.bidcard__cap {
				font-size: 14px;
				line-height: 19px;
				color: $color-text-gray;
				margin-bottom: 12px;
				margin-top: 66px;
			}
			.bidcard__btn {
				width: 90%;
				@media (max-width: 768px) {
					width: auto;
					padding: 0 15px;
				}
			}
		}
	}
	&__description {
		width: 100%;
	}
}
.bid-footer {
	@media (max-width: 768px) {
		background-color: $color-base-accent;
		margin-top: 0;
		padding-top: 20px;
		padding-bottom: 20px;
		color: $color-base-light;
	}
	&__box {
		display: grid;
		grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
		grid-row-gap: 10px;
		grid-template-areas:
			"copy copy copy copy copy copy"
			"expiration expiration expiration tags tags tags"
			"date date . . . .";
		@media (max-width: 768px) {
			grid-template-columns: repeat(7, 1fr);
			grid-column-gap: 10px;
			grid-template-areas:
				"expiration expiration expiration copy copy copy copy"
				"date date date tags tags tags tags";
		}
		@media (max-width: 420px) {
			grid-template-columns: 1fr 1fr;
			grid-row-gap: 15px;
			grid-template-areas:
				"expiration expiration"
				"date date"
				"copy copy"
				"tags tags";
		}
	}
	&__copy {
		grid-area: copy;
		@media (max-width: 768px) {
			justify-self: end;
		}
		@media (max-width: 420px) {
			justify-self: start;
		}
	}
	&__tags {
		grid-area: tags;
		justify-self: end;
		@media (max-width: 420px) {
			justify-self: center;
		}
	}
	&__row {
		display: flex;
		align-items: center;
		grid-area: date;
		align-self: center;
	}
	&__date {
		grid-area: date;
		align-self: center;
	}
	&__expiration {
		grid-area: expiration;
		align-self: center;
	}
	&__favorite {
		margin-left: 20px;
		@media (min-width: 769px) {
			display: none;
		}
	}
	.tags {
		display: flex;
		p {
			&:not(:last-child) {
				margin-right: 20px;
				@media (max-width: 420px) {
					margin-right: 10px;
				}
			}
		}
		.bid-category {
			border: 1px solid $color-base-accent;
			border-radius: 32px;
			color: $color-text-dark;
			font: $font-regular;
			font-size: 14px;
			padding: 6px 18px;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-wrap: wrap;
			text-align: center;
			@media (max-width: 768px) {
				color: $color-base-dark;
				border-color: $color-base-light;
				background-color: $color-base-light;
			}
			@media (max-width: 420px) {
				font-size: 11px;
				padding: 5px 6px;
			}
		}
	}
}
.bid-map {
	margin-top: 20px;
	&__show {
		text-decoration: none;
		&:hover,
		&:focus {
			text-decoration: none;
		}
	}
	&__control {
		position: relative;
		padding-bottom: 20px;
		p {
			color: $color-text-dark;
			display: flex;
			align-items: center;
			i {
				color: $color-base-accent;
			}
			b {
				font-size: 14px;
				line-height: 19px;
				font-weight: 500;
				padding: 0 15px;
			}
		}
	}
}
.bid-copylink {
	margin: 15px 0;
	display: grid;
	gap: 10px;
	grid-template-columns: 1fr auto;
	.divider {
		height: 1px;
		background-color: #ececec;
		align-self: end;
		@media (max-width: 768px) {
			display: none;
		}
	}
	&__link {
		text-decoration: underline;
		color: $color-base-accent;
		font-weight: 500;
		align-self: end;
		@media (max-width: 768px) {
			color: #fff;
		}
	}
	&__txt {
		@media (max-width: 768px) {
			text-decoration: underline;
		}
	}
	&__icon {
		margin-right: 3px;
		@media (max-width: 768px) {
			& /deep/ path {
				fill: #ffffff;
			}
		}
	}
}
.copy-link-message {
	position: fixed;
	left: 0;
	right: 0;
	bottom: 3%;
	width: 100%;
	font-size: 12px;
	background-color: #d4f5de;
	border: 1px solid $color-base-accent;
	border-radius: 10px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 5px 30px;
	min-height: 43px;
	&__pic {
		@include w-h(21px);
		@include flex-centerizer;
		background-color: $color-base-accent;
		border-radius: 50%;
		flex-shrink: 0;
		margin-right: 15px;
		& /deep/ svg {
			width: 50%;
			height: auto;
		}
	}
	&__close {
		border: none;
		background: none;
		color: $color-base-accent;
		cursor: pointer;
	}
}
.bids-intro {
	background: url(/images/bidlist/intro-bg.png) no-repeat;
	background-size: cover;
	padding-top: 155px;
	padding-bottom: 80px;

	&__inner {
		display: flex;
		align-items: center;
		margin-bottom: 30px;
		position: relative;
		// padding-top: 50px;
	}

	&__title {
		font-style: normal;
		font-weight: 800;
		font-size: 54px;
		line-height: 60px;
		margin-right: 88px;
		flex-shrink: 0;
		position: relative;
		@media screen and (max-width:1100px) {
			margin-right: 60px;
		}
		@media screen and (max-width: 1024px) {
			font-size: 46px;
			line-height: 56px;
		}
		@media (max-width: 640px) {
			font-size: 22px;
			line-height: 28px;
			margin-right: 10px;
		}
		@media (max-width: 425px) {
			z-index: 2;
		}
		@media (max-width: 320px) {
			font-size: 20px;
		}
	}
	&__picture {
		width: 25%;
		// flex-shrink: 2;
		img {
			width: 100%;
			height: auto;
		}
		@media screen and (max-width: 1024px) {
			// display: none;
		}
		@media (max-width: 640px) {
			margin-left: auto;
		}
		@media (max-width: 425px) {
			width: 30%;
			position: absolute;
			right: 0;
			top: 50%;
			transform: translateY(-50%);
		}
		@media (max-width: 320px) {
			width: 25%;
		}
	}
	&__filters {
		width: 100%;
		max-width: 1196px;
	}
}
</style>