import Axios from "axios";
import Cookies from "js-cookie";
const userModule = {
  state: {
    profile: null,
    token: null
  },
  mutations: {
    setProfile(state, profile) {
      if (profile) {
        Object.assign(state.profile, profile);
      } else {
        state.profile.active_payment_subscriptions = [];
        state.profile.company = {};
        state.profile.permissions = {};
        state.profile.profile = {};
        state.profile.unreadMsg = 0;
      }
    },
    setToken(state, token) {
      if (token) {
        state.token = token;
      } else {
        state.token = null;
      }
    }
  },
  actions: {
    // записать профиль в хранилище
    async setProfile(context, payload) {
      context.commit("setProfile", payload);
    },
    // записать токен в хранилище
    async setToken(context, payload) {
      context.commit("setToken", payload);
    },
    // получить токен по данным входа
    async sendLoginData({ dispatch }, payload) {
      const res = await Axios.post("/api/v1/user/login", payload);
      if (res.data.result) {
        Cookies.set("api_token", res.data.data.api_token);
        await dispatch("setToken", res.data.data.api_token);
        await dispatch("getProfile", res.data.data.api_token);
      }
      return res.data;
    },
    // получить количество новых сообщений
    async getCountUnreadedMsg() {
      const result = await axios.get("/api/v1/dialogs/messages/countunreaded");
      console.log(result);
      return result.data ? result.data.data : 0;
    },
    // запрос профиля
    async getProfile({ dispatch }, payload) {
      Axios.defaults.headers = {
        Authorization: `Bearer ${payload}`
      };
      const result = await Axios.get("/api/v1/user/profile");
      if (result.data.result) {
        dispatch("setProfile", result.data.data);
      }
      return result.data;
    },
    // отправить данные для регистрациии
    async sendRegistrationData({ context }, payload) {
      const result = await Axios.post("/api/v1/register/user", payload);
      return result.data;
    },
    // отправить письмо подтверждения повторно
    async resendLetter({ context }, payload) {
      const result = await Axios.post(
        "/api/v1/register/repeateregistermail",
        payload
      );
      return result.data;
    },
    // сброс пароля
    async passwordReset({ context }, payload) {
      const result = await Axios.post("/api/v1/user/passwordreset", payload);
      return result.data;
    },
    // попытка авторизации при загрузке
    async login({ dispatch }) {
      const token = Cookies.get("api_token");
      if (token) {
        await dispatch("setToken", token);
        await dispatch("getProfile", token);
      }
    },
    // выход
    async logout(context) {
      Cookies.remove("api_token");
      context.commit("setToken", null);
      context.commit("setProfile", null);
    }
  }
};
export default userModule;
