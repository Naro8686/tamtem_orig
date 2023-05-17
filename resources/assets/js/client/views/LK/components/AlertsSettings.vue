<template lang="pug">
.content
  .alerts
    .alerts__title
      | E-mail уведомления
    .alerts__description
      | Укажите уведомления, которые вы бы хотели получать на E-mail
    CustomCheckbox(:profile="profile", dataId="newOrders", dataName="newOrders", typeCheck="newOrders", dataTitle="Новые заказы на сайте")
</template>

<script>
import CustomCheckbox from "../../GeneralComponents/components/CustomCheckbox"

export default {
  components: {
    CustomCheckbox
  },
  methods: {},
  props: {
    profile: Object
  }
}
</script>
