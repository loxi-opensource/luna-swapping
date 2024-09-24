<script setup lang="ts">
//定义属性来接受父组件传递的loading text
const props = defineProps<{
  loadingText: string;
}>();
// props改变时，重新渲染
const loadintTextReactive = ref(props.loadingText);
watch(
  () => props.loadingText,
  () => {
    loadintTextReactive.value = props.loadingText;
    console.log("loading text changed");
  }
);
</script>

<template>
  <div>
    <svg
      xmlns="http://www.w3.org/2000/svg"
      version="1.1"
      width="100%"
      height="100%"
      class="rounded-lg"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        role="img"
        width="100%"
        height="100%"
        aria-labelledby="loading-aria"
        preserveAspectRatio="none"
      >
        <title id="loading-aria">Loading...</title>
        <rect
          x="0"
          y="0"
          width="100%"
          height="100%"
          clip-path="url(#clip-path)"
          style="fill: url('#fill')"
        ></rect>
        <defs>
          <linearGradient id="fill">
            <stop offset="0.599964" stop-color="#ddc7ff" stop-opacity="1">
              <animate
                attributeName="offset"
                values="-2; -2; 1"
                keyTimes="0; 0.25; 1"
                dur="2s"
                repeatCount="indefinite"
              ></animate>
            </stop>
            <stop offset="1.59996" stop-color="#e3d1ff" stop-opacity="1">
              <animate
                attributeName="offset"
                values="-1; -1; 2"
                keyTimes="0; 0.25; 1"
                dur="2s"
                repeatCount="indefinite"
              ></animate>
            </stop>
            <stop offset="2.59996" stop-color="#ddc7ff" stop-opacity="1">
              <animate
                attributeName="offset"
                values="0; 0; 3"
                keyTimes="0; 0.25; 1"
                dur="2s"
                repeatCount="indefinite"
              ></animate>
            </stop>
          </linearGradient>
        </defs>
      </svg>
      <defs>
        <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" style="stop-color: #db13c0; stop-opacity: 0.6" />
          <stop offset="100%" style="stop-color: #f67024; stop-opacity: 0.4" />
        </linearGradient>
      </defs>
      <text
        x="50%"
        y="40%"
        dominant-baseline="middle"
        text-anchor="middle"
        font-size="30"
        font-weight="600"
        font-family="PingFang SC"
        fill="url(#gradient1)"
      >
        Luna AI
      </text>
      <text
        x="50%"
        y="60%"
        dominant-baseline="middle"
        text-anchor="middle"
        font-size="16"
        font-family="PingFang SC"
        fill="rgba(255, 255, 255, 0.7)"
      >
        {{ loadintTextReactive }}
      </text>
    </svg>
  </div>
</template>

<style scoped lang="scss"></style>
