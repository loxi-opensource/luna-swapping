<template>
  <view class="content">
    <image class="mask-img" mode="widthFix" :src="maskImgSrc"></image>
    <view class="main-wrap">
      <image
        class="logo"
        src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMTUiIGhlaWdodD0iMjkiIHZpZXdCb3g9IjAgMCAxMTUgMjkiIGZpbGw9Im5vbmUiPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNLjU0IDI4LjM3MmMuMzk1LjM3Mi44NjMuNTU3IDEuNDAyLjU1N0gyMS4yNWMuNTQgMCAuOTg5LS4xODUgMS4zNDktLjU1N2EyLjAxIDIuMDEgMCAwIDAgLjU5My0xLjQ0NyAyLjAxIDIuMDEgMCAwIDAtLjU5My0xLjQ0OGMtLjM2LS40NDUtLjgxLS42NjgtMS4zNS0uNjY4bC0xNy4zNjQuMDU2VjIuMDA1QTEuODMgMS44MyAwIDAgMCAzLjI5LjYxMSAxLjcyMyAxLjcyMyAwIDAgMCAxLjk0MiAwQzEuNDAyIDAgLjkzNS4yMDQuNTQuNjEyLjE4Ljk4NCAwIDEuNDQ4IDAgMi4wMDR2MjQuOTJjMCAuNTU4LjE4IDEuMDQuNTQgMS40NDh6bTExMi45MzguMTI2Yy40MzItLjM3Mi42NDctLjg1NC42NDctMS40NDhWMTQuNzQ1YzAtMy44OTctMS4zNDktNy4yMzgtNC4wNDYtMTAuMDIyLTIuNjk4LTIuNzg0LTUuOTM1LTQuMTc2LTkuNzEyLTQuMTc2LTMuODEzIDAtNy4wNjkgMS4zOTItOS43NjYgNC4xNzYtMi42OTggMi43ODQtNC4wNDcgNi4xMjUtNC4wNDcgMTAuMDIyIDAgMy45MzUgMS4zNDkgNy4yOTQgNC4wNDcgMTAuMDc4IDIuNjk4IDIuNzg0IDUuOTUzIDQuMTc2IDkuNzY2IDQuMTc2IDMuODEzIDAgNy4wODYtMS41MjIgOS44Mi00LjU2NiAwIDEuNzQ1LjAxOCAyLjcxLjA1NCAyLjg5NS4yMTUgMS4xMTQuODI3IDEuNjcgMS44MzQgMS42Ny41NCAwIDEuMDA3LS4xNjYgMS40MDMtLjV6bS02LjE1MS0yMC44OGMxLjkwNiAxLjk2OCAyLjg2IDQuMzQzIDIuODYgNy4xMjdzLS45NTQgNS4xNi0yLjg2IDcuMTI3Yy0xLjkwNiAxLjk2Ny00LjIwOSAyLjk1MS02LjkwNiAyLjk1MS0yLjczNCAwLTUuMDU0LS45ODQtNi45Ni0yLjk1LTEuOTA3LTEuOTY4LTIuODYtNC4zNDQtMi44Ni03LjEyOHMuOTUzLTUuMTYgMi44Ni03LjEyNmMxLjkwNi0yLjAwNSA0LjIyNi0zLjAwNyA2Ljk2LTMuMDA3IDIuNjk3IDAgNSAxLjAwMiA2LjkwNiAzLjAwN3ptLTUwLjQzIDcuMjk1YzAgMy4xMTUtMS4zOTUgNy4wNzItNC4wOTMgOS44NTUtMi42OTggMi43ODQtNS45MzUgNC4xNzYtOS43MTIgNC4xNzYtMy44MTMgMC03LjA2OC0xLjM5Mi05Ljc2Ni00LjE3Ni0yLjY5OC0yLjc4NC00LjA0Ny01LjkyLTQuMDQ3LTkuODU1bC0uMDAxLS4zMjJjLS4wMDUtMS4xNy0uMDIyLTUuNDU5LS4wNTMtMTEuOTkxIDAtLjU1Ny4xOTgtMS4wNC41OTQtMS40NDguNDMxLS40NDYuOTE3LS42NjggMS40NTctLjY2OHMxLjAwNy4yMDQgMS40MDIuNjEyYy4zOTYuMzcxLjU5NC44MTcuNTk0IDEuMzM2bC0uMDU0IDEyLjQ4MWMwIDIuODIxLjk1MyA0Ljk5MyAyLjg2IDYuOTYgMS45MDYgMS45NjcgNC4yMjYgMi45NTEgNi45NiAyLjk1MSAyLjY5OCAwIDUtLjk4NCA2LjkwNi0yLjk1IDEuOTA3LTEuOTY4IDIuOTg3LTMuNDQ4IDMuNS02Ljk2bC4xMzMtLjkyNWMuNS0zLjUyLjg3OC02LjE4NyAzLjMyLTguNzA4IDIuNjYyLTIuNzg0IDUuOS00LjE3NiA5LjcxMi00LjE3NiAzLjc3NyAwIDcuMDE1IDEuMzkyIDkuNzEzIDQuMTc2IDIuNjk3IDIuNzg0IDQuMDQ2IDYuMTI1IDQuMDQ2IDEwLjAyMmwuMDU0IDExLjU4MWMwIC41NTctLjIxNiAxLjA1OC0uNjQ3IDEuNTAzLS4zOTYuNDA5LS44NjQuNjEzLTEuNDAzLjYxMy0uNTQgMC0xLjAwNy0uMTg2LTEuNDAzLS41NTdhMS44MyAxLjgzIDAgMCAxLS41OTMtMS4zOTJsLjA1My0xMS43NDhjMC0yLjc4NC0uOTUzLTUuMTYtMi44Ni03LjEyNy0xLjkwNi0yLjAwNC00LjIwOC0zLjAwNi02LjkwNi0zLjAwNi0yLjczMyAwLTUuMTMuNzgtNy4wMzYgMi43ODQtMS45OTUgMi4wOTctMi43MyAzLjg0NS0yLjczIDYuOTZ6IiBmaWxsPSIjZmZmIi8+PHBhdGggZD0iTTYxLjgyNCAxOS40MzVhLjUuNSAwIDAgMSAuNzUzLS40M2wxLjMzNS43ODZhMy42MzUgMy42MzUgMCAwIDAgMy42OTMgMGwxLjMzNC0uNzg3YS41LjUgMCAxIDEgLjUwOC44NjFsLS44MTUuNDhhNS42NTcgNS42NTcgMCAwIDEtNS43NDcgMGwtLjgxNS0uNDhhLjUuNSAwIDAgMS0uMjQ2LS40M3oiIGZpbGw9IiNmZmYiLz48L3N2Zz4="
      ></image>
      <view class="title">{{ t("draw-bootstrap.title") }}</view>
    </view>
  </view>
</template>

<script setup>
import { osName } from "../../context.js";
import { useStore } from "vuex";
import { onLoad } from "@dcloudio/uni-app";
import { useI18n } from "vue-i18n";
const store = useStore();
const { t } = useI18n();
const maskImgSrc =
  "https://luna-frontend-static-resource.oss-cn-shenzhen.aliyuncs.com/bootstrap-bg.png";

const getOsName = () => {
  uni.getSystemInfo({
    success: (res) => {
      osName.value = res.osName;
    },
  });
};

const preloadData = async () => {
  // 获取首页数据：玩法列表
  store.dispatch("fetchStrategyList");
  store.dispatch("fetchPortrait");
  store.dispatch("fetchSwap");
  // store.dispatch("fetchFaceSwap");
  // store.dispatch("fetchGallary");
};

getOsName();
preloadData();

onLoad(() => {
  uni.setNavigationBarColor({
    frontColor: "#ffffff",
    backgroundColor: "#C465FF",
  });

  setTimeout(() => {
    uni.switchTab({
      url: "/pages/draw/portrait",
    });
  }, 2000);
});
</script>

<style>
.content {
  height: 100%;
  background: linear-gradient(136deg, #c465ff 0%, #874aff 100%);
  background: linear-gradient(180deg, #c465ff 0%, #874aff 100%);
  position: relative;
}

.mask-img {
  position: absolute;
  top: 20rpx;
  width: 100%;
  height: 1250rpx;
}

.logo {
  width: 228rpx;
  height: 58rpx;
  margin-bottom: 19rpx;
}

.main-wrap {
  width: 100%;
  display: flex;
  flex-direction: column;
  position: absolute;
  align-items: center;
  left: 50%;
  transform: translate(-50%, 0);
  bottom: 204rpx;
  z-index: 2;
}

.title {
  width: 228rpx;
  white-space: nowrap;
  color: rgba(255, 255, 255, 0.67);
  text-align: center;
  font-size: 12px;
  line-height: 36rpx;
  letter-spacing: 34rpx;
}
</style>
