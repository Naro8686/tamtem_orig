<template functional>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    :width="props.width"
    :height="props.height"
    viewBox="0 0 33 27"
    :aria-labelledby="props.iconName"
  >
    <defs>
      <circle :id="props.iconName+`b`" cx="7.16" cy="21" r="2" />
      <filter
        :id="props.iconName+`a`"
        width="275%"
        height="275%"
        x="-87.5%"
        y="-62.5%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
      <circle :id="props.iconName+`d`" cx="19.84" cy="21" r="2" />
      <filter
        :id="props.iconName+`c`"
        width="275%"
        height="275%"
        x="-87.5%"
        y="-62.5%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
      <path
        :id="props.iconName+`f`"
        d="M13.5 17.5C6.049 17.489.011 11.451 0 4V0h3v4c0 5.799 4.701 10.5 10.5 10.5S24 9.799 24 4V0h3v4c-.011 7.451-6.049 13.489-13.5 13.5z"
      />
      <filter
        :id="props.iconName+`e`"
        width="125.9%"
        height="140%"
        x="-13%"
        y="-14.3%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
      <path :id="props.iconName+`h`" d="M.05 0h20.99v3H.05z" />
      <filter
        :id="props.iconName+`g`"
        width="138.1%"
        height="366.7%"
        x="-19.1%"
        y="-66.7%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
    </defs>
    <g fill="none" fill-rule="evenodd">
      <path stroke="#D8D8D8" d="M-14.5-128.5h536v332h-536z" />
      <g fill-rule="nonzero">
        <g transform="translate(2 1)">
          <use
            fill="#000"
            class="svg-colored"
            :filter="`url(#`+props.iconName+`a)`"
            :xlink:href="`#`+props.iconName+`b`"
          />
          <use fill="#FCFCFF" class="svg-colored" :xlink:href="`#`+props.iconName+`b`" />
        </g>
        <g transform="translate(2 1)">
          <use
            fill="#000"
            :filter="`url(#`+props.iconName+`c)`"
            :xlink:href="`#`+props.iconName+`d`"
          />
          <use fill="#FCFCFF" class="svg-colored" :xlink:href="`#`+props.iconName+`d`" />
        </g>
        <path fill="#FCFCFF" class="svg-colored" d="M32.45 1a3 3 0 0 1-3 3h-3.42V1h6.42z" />
        <g transform="translate(2 1)">
          <use
            fill="#000"
            :filter="`url(#`+props.iconName+`e`"
            :xlink:href="`#`+props.iconName+`f`"
          />
          <use fill="#FCFCFF" class="svg-colored" :xlink:href="`#`+props.iconName+`f`" />
        </g>
        <g transform="translate(2 1)">
          <use
            fill="#000"
            :filter="`url(#`+props.iconName+`g)`"
            :xlink:href="`#`+props.iconName+`h`"
          />
          <use fill="#FCFCFF" class="svg-colored" :xlink:href="`#`+props.iconName+`h`" />
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
      default: "food"
    },
    width: {
      type: [Number, String],
      default: 33
    },
    height: {
      type: [Number, String],
      default: 27
    },
    iconColor: {
      type: String,
      default: "#1A89FF"
    }
  }
};
</script>

<style lang="scss">
</style>
