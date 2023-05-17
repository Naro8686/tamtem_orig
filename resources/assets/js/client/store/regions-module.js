import Axios from "axios";

const regionsModule = {
	namespaced: true,
	state: {
		regionsArray: []
	},
	mutations: {
		setRegions(state, payload) {
			state.regionsArray = payload;
		}
	},
	getters: {
		getRegionById: state => id => {
			return state.regionsArray.find(item => {
				return item.id == id;
			});
		},
		getFilteredList: state => (searchVal, elems) => {
			return state.regionsArray
				.filter(item => {
					return (
						item.name.toLowerCase().indexOf(searchVal.toLowerCase()) !== -1
					);
				})
				.slice(0, elems);
		}
	},
	actions: {
		async loadRegions(context) {
			const result = await Axios.get("/api/v1/kladr/regions/1");
			if (result.status === 200 && result.data.result) {
				context.commit("setRegions", result.data.data);
			}
		}
	}
};
export default regionsModule;