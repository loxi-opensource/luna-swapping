<template>
  <view class="content">
    <view class="progess-wrap">
      <image
        class="loading-img"
        width="164rpx"
        src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNjQiIGhlaWdodD0iMTY0IiB2aWV3Qm94PSIwIDAgMTY0IDE2NCIgZmlsbD0ibm9uZSI+PGNpcmNsZSBjeD0iODIiIGN5PSI4MiIgcj0iODEuNSIgc3Ryb2tlPSJ1cmwoI3BhaW50MF9saW5lYXJfMV85ODcpIi8+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJwYWludDBfbGluZWFyXzFfOTg3IiB4MT0iODIiIHgyPSI4MiIgeTI9IjE2NCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPjxzdG9wIHN0b3AtY29sb3I9IiNCRThBRkYiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNCQ0JDQkMiIHN0b3Atb3BhY2l0eT0iMCIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjwvc3ZnPg=="
      ></image>
      <view class="progess-text-wrap">
        <text class="progess-text">{{
          t("draw-generate.countdown-title") +
          (showPredicateTime || t("draw-generate.default-countdown-value"))
        }}</text>
        <view class="percent-text">
          <view class="downloadedNumWrap">
            <text class="downloaded-number">{{ downloadedNumberText }}</text>
          </view>
          <text class="slash">/</text>
          <text class="total-number">{{ orderDrawNumber }}</text>
        </view>
      </view>
    </view>
    <view class="tips-wrap">
      <text>{{ t("draw-generate.tip") }}</text>
    </view>
    <view class="button-wrap">
      <button @tap="$debounceClick(backToHomepage)()">
        {{ t("draw-generate.create-btn") }}
      </button>
      <button class="save-button" @tap="$debounceClick(goToOrder)()">
        {{ t("draw-generate.view-created-images") }}
      </button>
    </view>
  </view>
</template>

<script setup>
import { ref, computed, onUnmounted } from "vue";
import { setTopNavBar2DarkTheme } from "../../utils/common";
import { pollTaskStatus } from "../../api/lunaDraw";
import { LUNA_OSS_BASE_URL } from "../../common/variable";
import { AblumStore, AblumType } from "../../store/album";
import { DraftStore } from "../../store/draft";
import { useStore } from "vuex";
import { onLoad, onUnload } from "@dcloudio/uni-app";
import { useI18n } from "vue-i18n";

const store = useStore();
const { t } = useI18n();
const resultImageList = ref([]);
const upTaskId = ref("");
const orderDrawNumber = ref(15);
const downloadedNumber = ref(0);
const showPredicateTime = ref(t("draw-generate.default-countdown-value"));

const pollingFinished = ref(true);
const drawingSuccess = ref(true);
const showLoading = ref(false);
const progressPercent = ref(0);
const intervalId = ref(null);

const timerId = ref(null);
const nextNum = ref(0);
const isLeaved = ref(false);

const userDraft = computed(() => DraftStore.getDraft(store));

const downloadedNumberText = computed(() => {
  if (orderDrawNumber.value < 10) {
    return downloadedNumber.value;
  }
  return downloadedNumber.value < 10
    ? "0" + downloadedNumber.value
    : downloadedNumber.value;
});

onLoad((option) => {
  setTopNavBar2DarkTheme();
  upTaskId.value = option.up_task_id;
  orderDrawNumber.value =
    userDraft.value.is_collection === 1
      ? userDraft.value.templates.length * userDraft.value.random_candidate_cnt
      : userDraft.value.templates.length;
  showPredicateTime.value =
    decodeURIComponent(option.show_predicate_time) === "undefined"
      ? t("draw-generate.default-countdown-value")
      : decodeURIComponent(option.show_predicate_time);
  pollingTaskStatus(upTaskId.value);
});

onUnmounted(() => {
  clearInterval(intervalId.value);
});
onUnload(() => {
  clearInterval(intervalId.value);
});

const addNumByTimer = (targetNum) => {
  nextNum.value = targetNum;
  clearTimeout(timerId.value);
  const delay = 200;
  const fn = () => {
    if (downloadedNumber.value === orderDrawNumber.value) {
      setTimeout(() => {
        if (isLeaved.value) return;
        isLeaved.value = true;
        AblumStore.setUpTaskId(store, upTaskId.value);
        uni.navigateTo({
          url: "/pages/user/photoAlbum?from=" + encodeURIComponent("generate"),
        });
      }, 500);
    } else if (downloadedNumber.value !== targetNum) {
      downloadedNumber.value++;
      timerId.value = setTimeout(fn, delay);
    }
  };
  timerId.value = setTimeout(fn, delay);
};

const backToHomepage = () => {
  uni.switchTab({
    url: "/pages/draw/portrait",
  });
};

const goToOrder = () => {
  uni.switchTab({
    url: "/pages/user/index",
  });
};

const pollingTaskStatus = async (upTaskId) => {
  progressPercent.value = 0;
  showLoading.value = true;
  pollingFinished.value = false;
  drawingSuccess.value = false;

  intervalId.value = setInterval(async () => {
    if (progressPercent.value >= 100) {
      if (!drawingSuccess.value) {
        progressPercent.value = 99;
      } else {
        clearInterval(intervalId.value);
        if (!pollingFinished.value) {
          pollingFinished.value = true;
        }
      }
    } else {
      progressPercent.value += 20;
      const statusRes = await pollTaskStatus(upTaskId);

      if (statusRes.code !== 1) {
        let showMsg = true;
        if (statusRes?.msg.indexOf("访问的太频繁了") !== -1) {
          console.log(statusRes, "skip 500 toast");
          showMsg = false;
          return;
        }
        if (showMsg) {
          uni.showToast({
            title: statusRes.msg || t("api-toast.server-error"),
            icon: "none",
          });
        }
        clearInterval(intervalId.value);
        showLoading.value = false;
        return;
      }

      if (statusRes.data.upstream_resp.status === 0) {
        progressPercent.value += 10;
        const { messageList = [] } = statusRes?.data?.upstream_resp || {
          messageList: [],
        };
        const newDownloadedNum = messageList.reduce((pre, item) => {
          return item.receiveTime ? pre + 1 : pre;
        }, 0);
        addNumByTimer(newDownloadedNum);
        if (statusRes.data.show_predicate_time) {
          showPredicateTime.value = statusRes.data.show_predicate_time;
        }
      } else if (statusRes.data.upstream_resp.status === 1) {
        console.log("draw success", statusRes.data);
        progressPercent.value = 100;
        drawingSuccess.value = true;
        resultImageList.value = statusRes.data.upstream_resp.messageList.map(
          (item) => {
            return new AblumType.ImageItem({
              id: item.id,
              url: LUNA_OSS_BASE_URL + "/" + item.sourceFilePath,
              groupName: item.tagName,
            });
          }
        );
        addNumByTimer(orderDrawNumber.value);
        clearInterval(intervalId.value);
        pollingFinished.value = true;
      } else {
        // 处理失败情况
        !pollingFinished.value &&
          uni.showToast({
            title:
              statusRes.data.upstream_resp.errorMsg ||
              t("draw-generate.toast-generate-failed"),
            icon: "error",
          });
        pollingFinished.value = true;
        clearInterval(intervalId.value);
        showLoading.value = false;
      }
      // 删掉了重传逻辑 status == -2
    }
  }, 3000);
};
</script>

<style lang="scss" scoped>
@import "@/common/variable.scss";

.content {
  background: white;
  backdrop-filter: blur(19.5px);
  padding-top: 100px;
  height: 100%;
  min-height: 570px;
  box-sizing: border-box;
  background-image: $image-background-generate;
  background-size: cover;
  background-repeat: no-repeat;
}

.progess-wrap {
  display: flex;
  justify-content: center;
  position: relative;
  margin-bottom: 50px;
}

.progess-text {
  color: #777;
  text-align: center;
  font-size: 24rpx;
  margin-bottom: 4px;
}

.loading-img {
  animation: loading linear 2s infinite;
  width: 328rpx;
  height: 328rpx;
}

@keyframes loading {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

.progess-text-wrap {
  position: absolute;
  top: 50px;
  text-align: center;
}

.percent-text {
  display: flex;
  justify-content: center;
  align-items: center;
}

.downloadedNumWrap {
  display: inline-block;
  min-width: 64rpx;
  text-align: end;
}

.downloaded-number {
  color: #fd387f;
  text-align: center;
  font-size: 56rpx;
}

.slash {
  color: #777;
  font-size: 56rpx;
}

.total-number {
  color: #777;
  font-size: 56rpx;
  display: inline-block;
  min-width: 64rpx;
  text-align: center;
}

.tips-wrap {
  margin: 0 120rpx 90px;
  color: #777;
  text-align: center;
  font-size: 12px;
  line-height: 150%;
  /* 18px */
}

.button-wrap {
  display: flex;
  flex-direction: column;
  justify-content: center;
  margin: 0 108rpx;
  gap: 20px;
}

.button-wrap button {
  width: 100%;
  box-sizing: border-box;
  border-radius: 8px;
  line-height: 44px;
  color: #fff;
  text-align: center;
  font-size: 16px;
  background: var(--11, linear-gradient(171deg, #c465ff 1.8%, #8247ff 100%));
}

.button-wrap .save-button {
  background: rgba(196, 101, 255, 0.13);
  border: 1px #ae5bff solid;
  line-height: 42px;
}

.result-image-wrap {
  width: 100%;
  display: flex;
  justify-content: center;
}

.swiper {
  width: 322px;
  height: 400px;
  border-radius: 20rpx;
}

.swiper-item {
  border-radius: 20rpx;
}

.result-image {
  height: 840rpx;
  border-radius: 20rpx;
}

.share-wrap {
  display: flex;
  gap: 20rpx;
  justify-content: center;
  padding: 40rpx 20rpx;
}

.share-app-item {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.share-app-item text {
  font-size: 70rpx;
  border-radius: 20rpx;
  margin-bottom: 20rpx;
  padding: 10rpx;
  box-shadow: 0px 4px 4px 0px #00000040;
}

.share-app-item span {
  font-size: 28rpx;
}
</style>
