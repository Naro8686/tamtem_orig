<template lang="pug">
.modal_payment_waiting
  p.toptext Вас выбрали на роль Поставщика, для получения контактов пополните баланс на сумму 300₽. Оплатить нужно в течении 72 часов
  div(@click="$root.$emit('closeForm', 'chosenPopUp')")
    router-link(:to="{name: 'lk.wallet'}", tag='button') Пополнить баланс
  .bottext
    p Остались вопросы?
    p
      | Свяжитесь со 
      a(href="tel:+79307202300") Службой Поддержки
</template>
<style>
  .modal_payment_waiting {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: white;
  }
</style>
