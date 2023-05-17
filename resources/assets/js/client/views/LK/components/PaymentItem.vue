<template lang="pug">
.payments_table_row
  p {{ dateFormat(item.created_at) }}
  p.middle_left {{ item.text }}
  p.footer_left Счет на оплату №{{ item.id }}
  p + {{ item.amount }}
</template>

<script>
export default {
  props: ["item"],
  data() {
    return {};
  },
  mounted() {},
};
</script>
