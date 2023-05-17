<template lang="pug">
.custom-input-block
  input.custom-input-input.password__input(v-on:input="$emit('input', $event.target.value)", :id="dataId", :type="hide ? 'text' : 'password'", :name="dataName", :placeholder="dataTitle", required="true")
  span.hide-password(v-bind:class="{'show-pass' : hide}", @click="hidePass")
    svg(width="17", height="12", viewBox="0 0 17 12", fill="none", xmlns="http://www.w3.org/2000/svg")
      path(
        d="M8.5 1.54545C9.92084 1.54073 11.3142 1.93706 12.5198 2.68888C13.7255 3.4407 14.6945 4.51749 15.3155 5.79545C14.0405 8.39954 11.4364 10.0455 8.5 10.0455C5.56364 10.0455 2.95955 8.39954 1.68455 5.79545C2.30552 4.51749 3.27453 3.4407 4.48018 2.68888C5.68582 1.93706 7.07916 1.54073 8.5 1.54545ZM8.5 0C4.63636 0 1.33682 2.40318 0 5.79545C1.33682 9.18773 4.63636 11.5909 8.5 11.5909C12.3636 11.5909 15.6632 9.18773 17 5.79545C15.6632 2.40318 12.3636 0 8.5 0ZM8.5 3.86364C9.01235 3.86364 9.50372 4.06717 9.866 4.42945C10.2283 4.79174 10.4318 5.2831 10.4318 5.79545C10.4318 6.3078 10.2283 6.79917 9.866 7.16146C9.50372 7.52374 9.01235 7.72727 8.5 7.72727C7.98765 7.72727 7.49628 7.52374 7.134 7.16146C6.77171 6.79917 6.56818 6.3078 6.56818 5.79545C6.56818 5.2831 6.77171 4.79174 7.134 4.42945C7.49628 4.06717 7.98765 3.86364 8.5 3.86364ZM8.5 2.31818C6.58364 2.31818 5.02273 3.87909 5.02273 5.79545C5.02273 7.71182 6.58364 9.27273 8.5 9.27273C10.4164 9.27273 11.9773 7.71182 11.9773 5.79545C11.9773 3.87909 10.4164 2.31818 8.5 2.31818Z",
        fill="#888888"
      )
  label.custom-input-label(:for="dataId") {{dataTitle}}
  p.err-input(v-for="error in dataError") {{error}}
</template>

<script>
export default {
  props: {
    dataTitle: String,
    dataName: String,
    dataId: String,
    dataError: Array
  },
  data() {
    return { hide: false }
  },
  methods: {
    hidePass() {
      this.hide = !this.hide
    }
  }
}
</script>

<style>
.err-input {
  font-family: Montserrat;
  font-style: normal;
  font-weight: normal;
  font-size: 14px;
  line-height: 17px;
  color: #d74a5c;
  margin-top: 14px;
}
</style>
