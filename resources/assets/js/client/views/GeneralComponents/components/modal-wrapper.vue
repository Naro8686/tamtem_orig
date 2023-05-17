<template lang="pug">
    section.wrapper(:class="containerClass")
        .wrapper-content
            button.hide(@click="$emit('close')") x
            .wrapper-slot
              transition(name="fade" mode="out-in")
              slot
</template>
<script>
export default {
  props: {
    overlayClass: {
      type: String,
      default: "body-overlay"
    },
    hasOverlay: {
      type: Boolean,
      default: true
    },
    closeOnOverlayClick: {
      type: Boolean,
      default: true
    },
    containerClass: {
      type: String,
      default: "modal-form"
    }
  },
  created() {
    if (this.hasOverlay) {
      document.body.classList.add(this.overlayClass);
    }
    if (this.hasOverlay && this.closeOnOverlayClick) {
      const overlayContainer = document.getElementsByClassName(
        this.overlayClass
      )[0];
      if (overlayContainer) {
        overlayContainer.addEventListener("click", event => {
          if (
            event.target.classList.contains(this.overlayClass) ||
            event.target.classList.contains(this.containerClass)
          ) {
            this.$emit("overlayClick");
            this.$emit("close");
          }
        });
      }
    }
  },
  destroyed() {
    if (this.hasOverlay) {
      document.body.classList.remove(this.overlayClass);
    }
  }
};
</script>
<style lang="scss" scoped>
@import "~styleguide";

.wrapper {
  z-index: 10;
  display: flex;
  justify-content: center;
  position: fixed;
  left: 0;
  right: 0;
  overflow-y: auto;
  max-height: 100%;
  top: 0;
  bottom: 0;
  padding: 50px 0;
	@media(max-width: 425px) {
		padding: 0;
	}
  &-content {
    display: flex;
    flex-direction: column;
    max-width: 460px;
    height: max-content;
    width: 100%;
    border-radius: $border-radius-standart;
    padding: 10px;
    background-color: #fff;
    padding-bottom: 10px;
		@media(max-width: 425px) {
			border-radius: 0;
			min-height: 100vh;
		}
  }
  &-slot {
    padding: 50px 0;
    margin: 0 auto;
    width: 280px;
  }
}
.hide {
  outline: none;
  display: flex;
  align-self: flex-end;
  align-items: center;
  justify-content: center;
  font: $font-light;
  font-size: $fontsize-base;
  font-weight: 300;
  background: $color-button-hide;
  border-radius: $border-radius-small;
  width: 24px;
  height: 24px;
  color: #fff;
  &::before,
  &::after {
    display: none;
  }
  &:hover {
    background-color: #27d066;
  }
}
</style>