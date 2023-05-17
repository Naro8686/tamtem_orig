<template lang="pug">
.custom-input-block
  textarea.custom-textarea(v-if="dataType == 'textarea'", v-on:input="$emit('input', $event.target.value)", :id="dataId", :type="dataType || 'input'", :name="dataName", :required="dataRequired", :placeholder="dataTitle", :value="dataValue", :disabled="disabled", :title="message")
  input.custom-input-input(v-else, v-on:input="$emit('input', $event.target.value)", :id="dataId", :type="dataType || 'input'", :name="dataName", :required="dataRequired", :placeholder="dataTitle", :value="dataValue", :disabled="disabled", :title="message")
  label.custom-input-label(:for="dataId") {{dataTitle}}
</template>

<script>
export default {
  props: {
    dataTitle: String,
    dataName: String,
    dataRequired: Boolean,
    dataValue: String,
    dataId: String,
    dataType: String,
    disabled: Boolean,
    message: String
  }
}
</script>
