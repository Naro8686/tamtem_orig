<template functional>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    :width="props.width"
    :height="props.height"
    viewBox="0 0 35 42"
    :aria-labelledby="props.iconName"
  >
    <defs>
      <path
        :id="props.iconName+`b`"
        d="M21.19 0H9.93A10 10 0 0 0 0 9.93v17.51a10 10 0 0 0 9.93 9.94h11.26c5.486-.006 9.93-4.454 9.93-9.94V9.93C31.114 4.448 26.672.006 21.19 0zm0 34.38H9.93A6.94 6.94 0 0 1 3 27.44V9.93A6.94 6.94 0 0 1 9.93 3h11.26a6.94 6.94 0 0 1 6.93 6.93v17.51a6.94 6.94 0 0 1-6.93 6.94z"
      />
      <filter
        :id="props.iconName+`a`"
        width="125.7%"
        height="121.4%"
        x="-12.9%"
        y="-5.4%"
        filterUnits="objectBoundingBox"
      >
        <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
      <path
        :id="props.iconName+`d`"
        d="M15.56 29.65a9.5 9.5 0 1 1 9.5-9.5 9.52 9.52 0 0 1-9.5 9.5zm0-16a6.5 6.5 0 1 0 6.5 6.5 6.51 6.51 0 0 0-6.5-6.5z"
      />
      <filter
        :id="props.iconName+`c`"
        width="131.6%"
        height="131.6%"
        x="-15.8%"
        y="-15.8%"
        filterUnits="objectBoundingBox"
      >
        <feOffset in="SourceAlpha" result="shadowOffsetOuter1" />
        <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1" />
        <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
      </filter>
      <circle :id="props.iconName+`f`" cx="8.5" cy="8" r="2" />
      <filter
        :id="props.iconName+`e`"
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
    </defs>
    <g fill="none" fill-rule="evenodd">
      <path stroke="#D8D8D8" d="M-269.5-121.5h536v332h-536z" />
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
      </g>
      <g transform="translate(2)">
        <use fill="#000" :filter="`url(#`+props.iconName+`e`" :xlink:href="`#`+props.iconName+`f`" />
        <use fill="#FFF" class="svg-colored" :xlink:href="`#`+props.iconName+`f`" />
      </g>
    </g>
  </svg>
</template>

<script>
export default {
  props: {
    iconName: {
      type: String,
      default: "household_aplience"
    },
    width: {
      type: [Number, String],
      default: 35
    },
    height: {
      type: [Number, String],
      default: 42
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
