<template lang="pug">
.modal_answer_waiting
  p.toptext Вы получите уведомление, если Заказчик примет ваше предложение
  button(@click="$root.$emit('closeForm', 'pendingPopUp')") Понятно
  .bottext
    p Остались вопросы?
    p
      | Свяжитесь со 
      a(href="tel:+79307202300") Службой Поддержки
</template>
<style>
  .modal_answer_waiting {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: white;
  }
</style>
