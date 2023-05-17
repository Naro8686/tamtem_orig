<template lang="pug">
div(v-if='data.id')
	bid-detail-buy(v-if="data.type_deal=='buy'" :bidData="data" @leaveDeal="leaveDeal" @reloadDeal="getDeal" @updateResponseId="setResponseId")
	bid-detail-sell(v-if="data.type_deal=='sell'" :bidData="data" @leaveDeal="leaveDeal" @reloadDeal="getDeal" @updateResponseId="setResponseId")

</template>
<script>
import viewedbids from "../../../mixins/viewedbids.mixin";
import bidDetailBuy from "../views/BidDetailBuy";
import bidDetailSell from "../views/BidDetailSell";

export default {
	mixins: [viewedbids],
	components: {
		bidDetailBuy,
		bidDetailSell
	},
	data() {
		return {
			data: {},
			type: ''
		};
	},
	methods: {
		setResponseId(payload) {
			this.data.responseId = payload;
		},
		leaveDeal() {
			this.yandexReachGoal("send_button_cancel_in_modal_window");
			this.googleReachGoal("send_button_cancel_in_modal_window");

			axios
				.post(`/api/v1/deals/user/responses/${this.data.responseId}/finish`)
				.then(result => {
				if (result.data.result == true) {
					this.yandexReachGoal("send_button_cancel_on_zakaz");
					this.googleReachGoal("send_button_cancel_on_zakaz");

					this.getDeal();
				} else {
					this.$store.dispatch("setSnackbar", {
						color: "error",
						text: `Произошла ошибка, попробуйте позднее`,
						toggle: true
					});
				}
				})
				.catch(err => {
					this.$store.dispatch("setSnackbar", {
						color: "error",
						text: `Произошла ошибка, попробуйте позднее`,
						toggle: true
					});
				});
		},
		dataTransform(data) {
			return Object.assign({},
				{
					id: data.id,
					name: data.name,
					type_deal: data.type_deal,
					description: data.description,
					count_views: data.count_views,
					categories: data.categories,
					cities: data.cities,
					favourited: data.favourited,
					date_published: data.date_published,
					deadline_deal: data.deadline_deal,
					userdata: this.setUserData(data.owner, data.organization),
					company: data.organization,
					owner: data.owner,
					budget_from: data.budget_from,
					budget_to: data.budget_to,
					budget_all: data.budget_all,
					responseId: data.response_id,
					budget_with_nds: data.budget_with_nds,
					unit_for_unit: data.unit_for_unit,
					unit_for_all: data.unit_for_all,
					date_of_event: data.date_of_event,
					commission: data.commission,
					files: data.files,
					files_my_response: data.files_my_response,
					responsesCount: data.count_response_active_after_moderate,
					isResponsedBefore: !!data.my_answers,
					winner_id: this.getWinner(data.winner_id, this.getMembers(data.members)),
					// relatedBids: this.loadRelatedBids(
					//   data.cities[0].id,
					//   data.categories[0].id,
					//   data.type_deal,
					//   data.id
					// ),
					answers: this.getQuestions(data.questions),
					questions: this.setAnswers(data.my_answers, data.questions),
					members: this.getMembers(data.members),
					is_shipping_included: data.is_shipping_included || 0,
					trading_status: data.trading_status,
					price_offer: data.price_offer || null,
					notice: data.notice || "",
					winnerIdReal: data.winner_id
				}
			);
		},
		dataTransformSell(data) {
			return Object.assign({}, {
				id: data.id,
				type_deal: data.type_deal,
				name: data.name,
				description: data.description,
				count_views: data.count_views,
				categories: data.categories,
				cities: data.cities,
				date_published: data.date_published,
				files: data.files,
				userdata: this.setUserData(data.owner, data.organization),
			})
		},
		setUserData(owner, organization) {
			return {
				user: owner,
				company: organization ? organization : null
			};
		},
		getWinner(org_id, membersArray) {
			let arr1 = membersArray.filter(item => {
				return (
				item.organization_with_user &&
				item.organization_with_user.id == org_id
				);
			});
			// let arr2 = membersArray.filter(item => {
			//   return (
			//     item.trading_status=='waiting_payment'
			//   );
			// });
			return arr1.length ? arr1[0].id : null;
		},
		getMembers(obj) {
			if (obj) {
				let items = this.getMembersData(obj);

				let filteredItems = items.sort((prev, next) => {
					return prev.is_readed_owner_deal - next.is_readed_owner_deal;
				});
				return filteredItems;
			} else {
				return [];
			}
		},
		getMembersData(obj) {
			let items = Object.values(obj);
			let res = items.filter(item => {
				if (item.organization_with_user) {
					let temp = this.setUserData(
						item.organization_with_user.owner,
						item.organization_with_user
					);
					item["userdata"] = temp;
				}
				return item;
			});
			return Object.values(obj);
		},
		setAnswers(answersArray, questionsArray) {
			// если есть массив с ответами - забираем из него нужные данные
			if (answersArray) {
				return Object.assign(
				{},
				...Object.keys(answersArray).map(item => {
					return {
					[item]: answersArray[item].is_agree
					};
				})
				);
				// если нет - формируемм массив с нулевыми ответами на основании массива с вопросами
			} else {
				return Object.assign(
				{},
				...Object.keys(questionsArray).map(item => {
					return {
					[item]: 0
					};
				})
				);
			}
		},
		getQuestions(questionsArray) {
			return Object.assign({}, ...Object.keys(questionsArray).map(item => {
				return {
					[item]: {
						name: questionsArray[item].slug,
						header: questionsArray[item].header,
						value: questionsArray[item].question,
						id: questionsArray[item].question_id
					}
				};})
			);
		},
		loadRelatedBids(cityId, categoryId, typeDeal, dealId) {
			let result = null;
			axios
				.get("/api/v1/filter/deals", {
					params: {
						"cities[]": cityId,
						"categories[]": categoryId,
						type_deal: typeDeal
					}
				})
				.then(resp => {
					result = resp.data.data.items.filter(item => {
						return item.id != dealId;
					});
				})
				.catch(error => {
					this.printErrorOnConsole(error);
				})
				.then(() => {
					return result;
				});
		},
		getDeal() {
			this.data = {};
			this.$store.dispatch("setLoading", true);
			axios
				.get("/api/v1/deals/" + this.$route.params.id)
				.then(resp => {
					if (!this.data.date_published) {
						this.data.date_published = this.data.date_create;
					}
					// console.log(resp);

					if(resp.data.data.type_deal == 'buy') {
						this.data = this.dataTransform(resp.data.data);
					}
					else if(resp.data.data.type_deal == 'sell') {
						this.data = this.dataTransformSell(resp.data.data);
					}

					this.setTitle(this.data.name);
					this.haslocalStorage ? this.setBidToArray(this.$route.params.id) : "";
				})
				.catch(error => {
					this.printErrorOnConsole(error);
					this.$router.replace({ name: "page404" });
				})
				.then(() => {
					this.$store.dispatch("setLoading", false);
				});
		}
	},
	watch: {
		"$route.query"() {
			this.data = {};
			this.getDeal();
		},
		"$root.isAuth": {
			handler(newVal) {
				if (!!newVal) {
				this.getDeal();
				}
			},
			deep: true
		}
	},
	mounted() {
		this.getDeal();
	},
	created() {
		this.type = this.data.type_deal
	}
};
</script>
<style lang="scss" scope>
.bid-detail {
  position: relative;
}
</style>