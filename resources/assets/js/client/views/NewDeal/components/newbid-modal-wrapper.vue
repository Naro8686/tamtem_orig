<template lang="pug">
section.my-modal__wrapper
    .my-modal__container
        slot(name="modal-header")
            .my-modal__header
        slot
            .my-modal__body
        slot(name="modal-footer")
            .my-modal__footer        
</template>
<script>
export default {
  created() {
    // document.body.classList.add('body-overlay')
  },
  destroyed() {
    // document.body.classList.remove('body-overlay')
  }
};
</script>
<style lang="scss" scoped>
.my-modal {
  &__wrapper {
    position: fixed;
    z-index: 10;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.5);
    height: 100vh;
    width: 100vw;
  }
  &__container {
    position: relative;
    margin: 5vh auto;
    height: 95vh;
    overflow: scroll;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  &__item {
  }
  &__header {
  }
  &__body {
  }
  &__footer {
  }
}
</style>