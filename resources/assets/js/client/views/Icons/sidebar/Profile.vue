<template>
<svg xmlns="http://www.w3.org/2000/svg"
     :width="width"
     :height="height"
     viewBox="0 0 18 20"
     :aria-labelledby="iconName">
  
  <g fill="none"
     fill-rule="evenodd">
    <path stroke="#D8D8D8"
          d="M-26.5-65.5h833v95h-833z" />
    <g :stroke="iconColor"
       stroke-linecap="round"
       stroke-linejoin="round"
       stroke-width="2"
       transform="translate(1 1)">
      <path d="M16 18v-2a4 4 0 0 0-4-4H4a4 4 0 0 0-4 4v2" />
      <circle cx="8"
              cy="4"
              r="4" />
    </g>
  </g>
</svg>
</template>

<script>
export default {
  props: {
    iconName: {
      type: String,
      default: 'profile'
    },
    width: {
      type: [Number, String],
      default: 18
    },
    height: {
      type: [Number, String],
      default: 20
    },
    iconColor: {
      type: String,
      default: '#1A89FF'
    }
  }
}
</script>

<style scoped>
a:hover g {
  stroke: white
}
</style>
