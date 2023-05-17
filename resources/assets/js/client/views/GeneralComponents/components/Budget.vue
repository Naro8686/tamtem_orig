<template lang="pug">
.budget(
	v-if='from || to'
)
	template(
		v-if="type == 'buy'"
	)
		template(v-if="from && to")
			span {{priceFormat(from)}}
			span &nbsp;&ndash;&nbsp;
			span {{priceFormat(to)}}
			span.budget-rubl &nbsp;&#8381;

		template(v-else)
			template(v-if="to")
				span До&nbsp;
				span {{priceFormat(to)}}
				span.budget-rubl &nbsp;&#8381;

	//- template(v-else)
	//- 	span {{priceFormat(to)}}
	//- 	span.budget-rubl &nbsp;&#8381;
			
</template>

<script>
export default {
  components: {},
  data() {
    return {};
  },
  props: {
    type: String,
    from: [Number,String],
    to: [Number,String]
  },
  computed: {},
  methods: {},
  mounted() {}
};
</script>

<style scoped lang="scss">
@import "../../../../../sass/styleguide.scss";

.budget {
  white-space: nowrap;
  span {
    color: $color-base-dark;
    font: $font-bold;
    font-size: 16px;
  }
}
</style>