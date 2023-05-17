<template lang="pug">
.users-profile
	LkProfile(:profile='profile')
</template>

<script>
import LkProfile from "../../LK/views/LkProfile";
import { mapGetters, mapState } from "vuex";

export default {
  components: {
    LkProfile
  },
  props: {},
  data() {
    return {
      profile: null
    };
  },
  computed: {
    ...mapState({
      user: (state) => {return state.profile}
    })
  },
  methods: {
    getProfile() {
      if (this.$root.isAuth && this.$route.params.id === this.user.profile.id) {
        // redirect to myLK if this my profile
        this.$router.replace({ name: "lk.profile" });
      } else {
        axios
          .get("/api/v1/user/profile/" + this.$route.params.id)
          .then(resp => {
            this.profile = resp.data.data;
            this.setTitle(this.profile.profile.name);
          })
          .catch(error => {
            this.printErrorOnConsole(error);
            this.$router.replace({ name: "page404" });
          });
      }
    }
  },
  mounted() {
    this.getProfile();
  }
};
</script>
<style lang="scss" scoped>
@import "~styleguide";

.container.users /deep/ .companies-detail {
  max-width: 700px;
  margin: 0 auto;
}
.users-profile /deep/ .card {
  border-radius: 10px;
  box-shadow: $box-shadow-standart;
  border: none;
}
</style>