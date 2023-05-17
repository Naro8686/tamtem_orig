<template functional lang="pug">
aside.aside
	slot

</template>
<style lang="scss" scope>
@import "../../../../../sass/styleguide.scss";

.aside {
  background: #fff;
  border: 1px solid $light;
  border-radius: $border-radius-small;
  padding: 2.5rem 0;
}
</style>