<template lang="pug">
section.container.content-lk
	.row(v-if='$root.profile')
		.col-lg-3.d-none.d-lg-block
			LkNavigation

		.col-md-12.col-lg-9.col-content.position-relative
			router-view(
				@profileUpdateEmit='loadProfile' 
				:profile='profile'
			)

			Loader(v-if='this.loading')

	div.position-relative(v-else)
		router-view(
			@profileUpdateEmit='loadProfile'
			:profile='profile'
		)

		Loader(v-if='this.loading')

</template>

<script>
import LkNavigation from "./components/LkNavigation";
import { mapGetters, mapActions } from "vuex";

export default {
	components: {
		LkNavigation
	},
	data() {
		return {
			proAccount: true,
			loading: false,
			supplier: true,
			profile: null,
			isAccessOpen: false,
			snackbar: {
				toggle: false,
				timeout: 3000,
				color: "",
				text: ""
			},
			err: null,
			errMedia: {},
			paylink: null
		};
	},
	mounted() {
		this.loadProfile();
	},
	methods: {
		...mapActions(["setSnackbar"]),
		async loadProfile() {
			this.profile = this.getProfileState;
			// this.profile = await Api.loadProfile()
			this.$root.profile = this.profile;
		},
		getBillPay(data) {
			this.loading = true;
			axios
				.post("/api/v1/paymentservice/get/score", {
					params: {
						unique_id: this.profile.profile.unique_id,
						summ: parseInt(data.price, 10)
					}
				})
				.then(resp => {
					if (resp.data.result && resp.data.result === true) {
						this.paylink = resp.data.link;
						// this.scoreNumber = resp.data.scoreNumber
						this.setSnackbar({
							color: "success",
							text: "Счёт сформирован",
							toggle: true
						});

						if (window.isProdMode) {
							window.ym(76387882, "reachGoal", "create_schet"); // Выставить счет
						}
						window.open(this.paylink, "_blank").focus();
						this.loading = false;
					} else {
						this.printErrorOnConsole(
						"Произошла ошибка, попробуйте позднее",
						"warning"
						);
						this.setSnackbar({
							color: "success",
							text: "Произошла ошибка, попробуйте позднее",
							toggle: true
						});
					}
				})
				.catch(error => {
					this.printErrorOnConsole(error);
					this.loading = false;
				});
		}
	},
	computed: {
		...mapGetters(["getProfileState"])
	},
	beforeRouteEnter(to, from, next) {
		next(vm => {
		//if (vm.$root.profile.company.organization.status == 'approve' || !vm.$root.profile) {
		next();
		//} else {
		//next({name: 'map.categories'});
		// }
		});
	}
};
</script>
<style lang="scss" scoped>
@import "~styleguide";
.col-content /deep/ .card {
  border-radius: 10px;
  box-shadow: $box-shadow-standart;
  border: none;
}
</style>