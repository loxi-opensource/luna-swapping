<template>
  <view @tap="$debounceClick(goToConcat)()">
    <movable-area class="movable-area">
      <movable-view class="movable-view" :x="x" :y="y" direction="all">
        <image :src="customerServiceIcon"></image>
      </movable-view>
    </movable-area>
  </view>
</template>

<script>
import { customerServiceIcon } from "../common/svgBase64.js";

export default {
  name: "customerServiceButton",
  data() {
    return {
      x: 313,
      y: 549,
      customerServiceIcon,
    };
  },
  methods: {
    goToConcat() {
      uni.navigateTo({
        url: "/pages/user/contact",
      });
    },
  },
};
</script>

<style lang="scss" scoped>
$all_width: 110rpx;
$all_height: 110rpx;

.movable-area {
  height: 100vh;
  width: 750rpx;
  top: 0;
  position: fixed;
  pointer-events: none; //此处要加，鼠标事件可以渗透

  .movable-view {
    display: flex;
    justify-content: center;
    align-items: center;
    width: $all_width;
    height: $all_height;
    background-color: white;
    border-radius: 50%;
    border: 1rpx solid rgba(209, 111, 255, 1);
    box-shadow: 0px 7px 6px -1px rgba(114, 84, 177, 0.25);
    pointer-events: auto; //恢复鼠标事件

    image {
      width: 68rpx;
      height: 68rpx;
    }
  }
}
</style>
