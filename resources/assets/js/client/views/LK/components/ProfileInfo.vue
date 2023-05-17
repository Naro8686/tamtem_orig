<template lang="pug">
.name_card
  p.name_card_name {{ profile.profile.name }}
  img(v-if="profile.profile.photo", :src="profile.profile.photo", alt=" ")
  img(v-else, src="/images/logo.png")
  .name_card_info
    p.name_card_info_bold_text {{ profile.company.organization.name }}
    p.name_card_info_default_text {{ profile.company.organization.address }}
</template>

<script>
export default {
  props: {
    profile: Object
  },
  data() {
    return {}
  },
  computed: {},
  methods: {},
  mounted() {}
}
</script>
