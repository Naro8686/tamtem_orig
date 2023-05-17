var mixins = {
  data() {
    return {
      profile: null,
      loadError: false
    };
  },

  created() {
    axios.interceptors.response.use(
      config => {
        return config;
      },
      error => {
        if (error.response.status == 401) {
          window.location = "/login";
        }

        if (error.response.status == 403) {
          this.showError("Действие запрещено. Недостаточно прав.");
        }
        if (error.response.status == 405) {
          this.showError("Метод не найден");
        }

        if (error.response.status == 404) {
          this.goToNotFound();
        }

        if (error.response.status == 419) {
          this.showError("Сессия истекла.");
        }

        if (error.response.status == 422) {
          this.messageFormError();
        }

        return Promise.reject(error);
      }
    );

    axios
      .get("/admin/api/profile")
      .then(resp => {
        if (resp.data.user && resp.data.permissions) {
          this.profile = resp.data;
        } else {
          this.loadError = true;
        }
        this.$mount("#app");
      })
      .catch(error => {
        this.loadError = true;
        this.$mount("#app");
      });
  }
};

export default mixins;
