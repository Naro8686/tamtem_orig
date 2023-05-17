const favouritesModule = {
    namespaced: true,
    state: {
        count: 0
    },
    mutations: {
        setCount(state, count) {
            state.count = count
        }
    },
    getters: {
        getFavouritesCount: state => {
            return state.count;
        }
    },
    actions: {
        async setCount(context) {
            axios.get("/api/v1/user/favourites/count").then(result => {
                context.commit("setCount", result.data.data);
            });
        }
    }
};
export default favouritesModule;
