const profileModule = {
  state: {
    active_payment_subscriptions: [],
    company: {},
    permissions: {},
    profile: {},
    unreadMsg: 0,
    notifications: null
  },
  mutations: {
    setProfile(state, profile) {
      if (profile) {
        Object.assign(state, profile);
      } else {
        state.active_payment_subscriptions = [];
        state.company = {};
        state.permissions = {};
        state.profile = {};
        state.unreadMsg = 0;
      }
    },
    setNotifications(state, notifications) {
      state.notifications = Object.assign({}, notifications);
    }
  },
  getters: {
    getProfile: state => {
      return state.profile;
    },
    getProfileState: state => {
      return state;
    }
  },
  actions: {
    async setNotificationReaded({},payload) {
      return await axios.post("/api/v1/user/markreaded", payload);
    },
    updateNotifications({ dispatch, state }, payload) {
      dispatch("setNotificationReaded", payload).then(response => {        
        if (response.data.result == true) {
          dispatch("getNotifications").then(result => {
            if (result.data.result == true) {
              dispatch("setNotifications", result.data.data);
            }
          });
        }
      });
    },
    async getNotifications() {
      return await axios.get("/api/v1/user/countunreaded");
    },
    setNotifications(context, payload) {
      context.commit("setNotifications", payload);
    },
    async setProfile({ dispatch, commit }, payload) {
      if (payload) {
        dispatch("getNotifications").then(result => {
          if (result.data.result == true) {
            dispatch("setNotifications", result.data.data);
          }
        });
      }
      commit("setProfile", payload);
    }
  }
};
export default profileModule;
