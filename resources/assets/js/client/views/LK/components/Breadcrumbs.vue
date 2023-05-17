<template lang="pug">
ul.breadcrumbs
  li
    a(
      v-for="(breadcrumb, idx) in breadcrumbList",
      :key="idx",
      @click="routeTo(idx)",
      :class="{ linked: !!breadcrumb.link }"
    ) {{ breadcrumb.name }}
</template>

<script>
export default {
  name: "Breadcrumb",
  data() {
    return {
      breadcrumbList: []
    };
  },
  mounted() {
    this.updateList();
  },
  watch: {
    $route() {
      this.updateList();
    }
  },
  methods: {
    routeTo(pRouteTo) {
      if (this.breadcrumbList[pRouteTo].link)
        this.$router.push(this.breadcrumbList[pRouteTo].link);
    },
    updateList() {
      this.breadcrumbList = this.$route.meta.breadcrumb;
    }
  }
};
</script>
<style scoped>
	.breadcrumbs {
		margin-left: 75px;
		padding-top: 15px;
		padding-bottom: 15px;
}
li {
	float: none;
}
li a::after {
		content: "/";
		margin-left: 5px;
		margin-right: 5px;
}
li a:last-child {
	cursor: unset;
}
li a:last-child::after {
	content: "";
}
</style>
