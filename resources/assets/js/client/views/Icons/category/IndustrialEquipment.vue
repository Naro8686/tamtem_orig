<template functional>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    :width="props.width"
    :height="props.height"
    viewBox="0 0 42 48"
    :aria-labelledby="props.iconName"
  >
    <defs>
      <path
        :id="props.iconName+`b`"
        d="M19 43.35c-10.493 0-19-8.507-19-19V0h30v5.72h-3V3H3v11.09c.53-.79 1-1.33 1.09-1.49A19 19 0 1 1 19 43.35zM3 21.66v2.71a16 16 0 1 0 3.43-9.9C5.47 15.67 3 19.27 3 21.66z"
      />
      <filter
        :id="props.iconName+`a`"
        width="121%"
        height="118.5%"
        x="-10.5%"
        y="-4.6%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
      <path :id="props.iconName+`d`" d="M0 10.2h3v14.17H0z" />
      <filter
        :id="props.iconName+`c`"
        width="366.7%"
        height="156.5%"
        x="-133.3%"
        y="-14.1%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
      <path
        :id="props.iconName+`f`"
        d="M19 35.41c-6.075 0-11-4.925-11-11s4.925-11 11-11 11 4.925 11 11a11 11 0 0 1-11 11zm0-19.08a8 8 0 1 0 0 16 8 8 0 0 0 0-16z"
      />
      <filter
        :id="props.iconName+`e`"
        width="136.4%"
        height="136.4%"
        x="-18.2%"
        y="-9.1%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
    </defs>
    <g fill="none" fill-rule="evenodd">
      <path stroke="#D8D8D8" d="M-418.5-248.5h536v332h-536z" />
      <g fill-rule="nonzero">
        <g transform="translate(2)">
          <use
            fill="#000"
            :filter="`url(#`+props.iconName+`a)`"
            :xlink:href="`#`+props.iconName+`b`"
          />
          <use fill="#FFF" class="svg-colored" :xlink:href="`#`+props.iconName+`b`" />
        </g>
        <g transform="translate(2)">
          <use
            fill="#000"
            :filter="`url(#`+props.iconName+`c)`"
            :xlink:href="`#`+props.iconName+`d`"
          />
          <use fill="#FFF" class="svg-colored" :xlink:href="`#`+props.iconName+`d`" />
        </g>
        <path
          fill="#FFF"
          class="svg-colored"
          d="M5 10.2H2V0h30.01v5.72h-3V3H5zM19.48 30.92a3 3 0 0 1 3 3h-3v-3zM19.48 14.85h3v3a3 3 0 0 1-3-3zM14.45 22.89a3 3 0 0 1-3 3v-3h3zM30.51 22.89v3h-3a3 3 0 0 1 3-3zM24.532 30.058a3 3 0 0 1 4.242 0l-2.121 2.122-2.121-2.122zM13.176 18.698l2.121-2.12 2.122 2.12a3 3 0 0 1-4.243 0zM15.299 27.939a3 3 0 0 1 0 4.242l-2.121-2.12 2.12-2.122zM26.664 16.579l2.122 2.121-2.122 2.121a3 3 0 0 1 0-4.242z"
        />
        <path
          fill="#FFF"
          class="svg-colored"
          d="M21 43.35c-10.493 0-19-8.507-19-19h3a16 16 0 1 0 7.92-13.8L11.41 8A19 19 0 1 1 21 43.35z"
        />
        <g transform="translate(2)">
          <use
            fill="#000"
            :filter="`url(#`+props.iconName+`e`"
            :xlink:href="`#`+props.iconName+`f`"
          />
          <use fill="#FFF" class="svg-colored" :xlink:href="`#`+props.iconName+`f`" />
        </g>
      </g>
    </g>
  </svg>
</template>

<script>
export default {
  props: {
    iconName: {
      type: String,
      default: "industrial_equipment"
    },
    width: {
      type: [Number, String],
      default: 42
    },
    height: {
      type: [Number, String],
      default: 48
    },
    iconColor: {
      type: String,
      default: "#1A89FF"
    }
  }
};
</script>

<style lang="css">
</style>
