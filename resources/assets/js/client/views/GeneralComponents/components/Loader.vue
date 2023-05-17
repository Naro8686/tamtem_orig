<template functional lang="pug">
.loader
	v-progress-circular(
		:size="86"
		color="#1a89fe"
		indeterminate
	)
</div>
</template>

<script>
export default {
  name: "Loader"
};
</script>

<style lang="scss" scoped>
@import "~styleguide";

.loader {
  position: fixed;
  z-index: 9;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}
</style>