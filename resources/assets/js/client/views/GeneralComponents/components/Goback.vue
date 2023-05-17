<template lang="pug">
.goback(v-if='isBack' :class='cls')
	a(@click='$router.go(-1)' href='javascript:void(0);')
		feather(type='arrow-left')
		

</template>

<script>
export default {
  data() {
    return {};
  },
  props: {
    cls: String
  },
  computed: {
    isBack() {
      return window.history.length > 1;
    }
  }
};
</script>

<style lang="scss" scoped>
$border-radius-small: 6px;
$old-primary: #0071bc;

.goback {
  position: absolute;
  left: -7rem;
  top: 0;
  opacity: 0.5;

  &.bids-list {
    top: 4rem;
  }
  &.register-form {
    left: 15rem;
  }
  &.companies-detail {
    left: 15rem;
  }
  &.users-profile {
    left: 15rem;
  }
  a {
    display: flex;
    width: 4.2rem;
    height: 3.5rem;
    line-height: 4rem;
    border: solid 0.2rem $old-primary;
    text-align: center;
    color: $old-primary;
    border-radius: $border-radius-small;
    align-items: center;
    justify-content: center;

    :hover,
    :focus {
      color: $old-primary;
    }
    .feather {
      width: 2.4rem;
      height: 2.4rem;
      svg {
        width: 100%;
        height: 100%;
      }
    }
  }
}
</style>