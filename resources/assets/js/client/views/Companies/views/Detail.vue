<template lang="pug">
.companies-detail
	LkCompany(:profile='profile')

</template>

<script>
import LkCompany from "../../LK/views/LkCompany";
import { mapGetters, mapState } from "vuex";

export default {
  components: {
    LkCompany
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
    }),

  },
  methods: {
    getCompany() {
      if (this.$root.isAuth && this.$route.params.id === this.user.profile.id) {
        // redirect to myLK if this my profile
        this.$router.replace({ name: "lk.company" });
      } else {
        axios
          .get("/api/v1/user/profile/" + this.$route.params.id)
          .then(resp => {
            this.profile = resp.data.data;
            this.setTitle(this.profile.company.organization.name);
          })
          .catch(error => {
            this.printErrorOnConsole(error);
            this.$router.replace({ name: "page404" });
          });
      }
    }
  },
  mounted() {
    this.getCompany();
  }
};
</script>