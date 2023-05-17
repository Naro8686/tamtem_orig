const serviceModule = {
	state: {
		loading: false,
		snackbar: {
			color: "undefined",
			text: "",
			toggle: false,
			timeout: 600
		},
		modalMsg: {
			toggle: false,
			content: "",
			btns: [],
			size: "md"
		},
		width: window ? window.innerWidth : 0,
		typeOfPage: "",
	},
	mutations: {
		setLoading(state, loading) {
			state.loading = loading;
		},
		setSnackbar(state, snackbar) {
			Object.assign(state.snackbar, snackbar);
		},
		setModalMsg(state, modal) {
			Object.assign(state.modalMsg, modal);
		},
		setWidth(state, payload) {
			state.width = payload;
		}
	},
	getters: {
		getModalMsgState: state => {
			return state.modalMsg;
		},
		getSnackbarState: state => {
			return state.snackbar;
		},
		getLoadingState: state => {
			return state.loading;
		}
	},
	actions: {
		setLoading(context, payload) {
			context.commit("setLoading", payload);
		},
		setSnackbar(context, payload) {
			context.commit("setSnackbar", payload);
		},
		setModalMsg(context, payload) {
			context.commit("setModalMsg", payload);
		},
		setWindowWidth(context) {
			if (window) {
				window.addEventListener("resize", () => {
					context.commit("setWidth", window.innerWidth);
				});
			}
		}
	}
};
export default serviceModule;