import Axios from "axios";

const categoriesModule = {
	namespaced: true,
	state: {
		categoriesArray: [],
		flatCategoriesArray: [],
		bidCategory: {
			open: false,
			id: null,
			name: "Все категории"
		},
		catMenu: {
			open: false,
			type: ""
		}
	},
	mutations: {
		setCategories(state, payload) {
			state.categoriesArray = [...payload];
		},
		setFlatArray(state, payload) {
			state.flatCategoriesArray = [...payload];
		},
		bidCategoryAct(state, payload) {
			const val = Object.assign({}, payload);
			state.bidCategory = val;
		},
		setCatMenu(state, payload) {
			const val = Object.assign({}, payload);
			state.catMenu = val;
		}
	},
	getters: {
		getSubCategoriesByParentId: state => id => {
			const result = state.categoriesArray.find(item => {
				return item.id === id;
			});
			return result ? result.items : [];
		},
		getCategoryById: state => id => {
			return state.flatCategoriesArray.find(item => {
				return item.id == id;
			});
		},
		getBidCatState(state) {
			return state.bidCategory
		},
		getCatMenuState(state) {
			return state.catMenu;
		}
	},
	actions: {
		bidCategoryAct(context, payload) {
			context.commit("bidCategoryAct", payload);
		},
		setCatMenu(context, payload) {
			context.commit("setCatMenu", payload);
		},
		setFlatCategoriesArray(context, payload) {
			const categories = payload;
			const result = categories.reduce((res, item) => {
				let temp = {
					...item
				};
				delete temp.items;
				return [temp, ...res, ...(item.items ? item.items : "")];
			}, []);
			context.commit("setFlatArray", result);
		},
		async loadCategories({
			commit,
			dispatch
		}) {
			const result = await Axios.get("/api/v1/category/tree");
			if (result.status === 200 && result.data.result) {
				commit("setCategories", result.data.data);
				dispatch("setFlatCategoriesArray", result.data.data);
			}
		}
	}
};
export default categoriesModule;