<template lang="pug">
div(v-if="type == 'buy' && to")
  template(v-if="from && to && type == 'buy'")
    p.response_small_text Бюджет {{ priceFormat(from) }} - {{ priceFormat(to) }} &#8381;
  template(v-else)
    p.response_small_text Бюджет До {{priceFormat(to)}} &#8381;
</template>

<script>
export default {
  components: {},
  data() {
    return {}
  },
  props: {
    type: String,
    from: [Number, String],
    to: [Number, String]
  },
  computed: {},
  methods: {},
  mounted() {}
}
</script>
