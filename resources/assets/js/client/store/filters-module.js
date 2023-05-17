const filtersModule = {
	state: {
		defaultValue: {
			page: 1,
			per_page: 12,
			type_deal: "buy",
			date_published: "desc",
		},
		currentValue: {
			page: 1,
			per_page: 12,
			type_deal: "buy",
			date_published: "desc",
		}
	},
	mutations: {
		setCurrentValue(state, payload) {
			const val = Object.assign({}, payload);
			state.currentValue = val;
		}
	},
	getters: {
		getDefaultValue: state => {
			return state.defaultValue;
		},
		getCurrentValue: state => {
			return state.currentValue;
		},
		getCurrentValuePropertyByKey: state => key => {
			return state.currentValue[key];
		}
	},
	actions: {
		setNewVal(context, payload) {
			context.commit("setCurrentValue", payload);
		}
	}
};
export default filtersModule;