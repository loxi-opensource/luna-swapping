<template>
  <view class="content">
    <view class="image-group-wrapper">
      <view
        class="image-list-wrapper"
        v-for="group in groupList"
        :key="group.id"
        :class="!group.id && 'popular-list-wrapper'"
      >
        <view>
          <view class="top-wrapper">
            <text>{{ group.name }}</text>
            <text @tap="handleMoreClick(group)">更多 >></text>
          </view>
          <view class="bottom-wrapper">
            <view
              class="image-wrapper"
              v-for="(el, index) in group.templates"
              :key="index"
            >
              <image
                :src="el.image_url"
                mode="aspectFill"
                @tap="handleMoreClick(group)"
                :lazy-load="true"
              ></image>
            </view>
          </view>
        </view>
      </view>
    </view>
  </view>
</template>

<script setup>
import { useStore } from "vuex";
import { computed } from "vue";
import { DraftStore } from "../../store/draft";
import { onShareAppMessage } from "@dcloudio/uni-app";

const store = useStore();
store.dispatch("fetchSwap");
const groupList = computed(() => {
  return store.state.swap;
});
const handleMoreClick = (group) => {
  DraftStore.resetTemplates(store);
  // todo 根据玩法信息动态设置
  DraftStore.setStrategyId(store, 2);
  DraftStore.setIsCollection(store, 0);
  DraftStore.setRandomCandidateCnt(store, 0);

  store.commit("setBrowseringGroupDetail", {
    groupId: group.id,
    groupName: group.name,
    templateList: group.templates,
  });
  uni.navigateTo({
    url: `/pages/swap/templateList`,
  });
};

onShareAppMessage(() => {
  return {
    title: "Luna AI写真",
    path: "/pages/swap/index",
  };
});
</script>

<style lang="scss" scoped>
@import "@/common/variable.scss";

.content {
  display: flex;
  height: 100vh;
  // background: url('../../static/page-bg.jpg') repeat;
  background-size: 100%;
  flex-flow: column;
  overflow: hidden;
  box-sizing: border-box;
}

.header-wrapper {
  height: 92rpx;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 8px 32rpx 18px;

  image {
    width: 48rpx;
    height: 48rpx;
    border-radius: 4px;
  }

  .logo-icon {
    width: 80px;
    height: 20px;
  }

  .left-panel-wrapper {
    display: flex;
    align-items: center;
  }

  .right-panel-wrapper {
    display: flex;
    align-items: center;
  }
}

.search-wrapper {
  padding: 16px 32rpx 0px;
  color: #fff;

  .input-box {
    background-color: #382f48;
    text-align: start;
    // line-height: 48px; 会出现挡住所文字的情况
    padding: 0 28rpx;
    font-size: 14px;
    border-radius: 8px;
    border: none;
    margin-bottom: 24rpx;
    box-sizing: border-box;
    height: 30px;
  }
}

.image-group-wrapper {
  overflow: auto;
  //padding-bottom: 100px;
}

.image-list-wrapper {
  margin-bottom: 8px;

  .top-wrapper {
    padding: 0 32rpx 0;
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;

    text:first-child {
      color: #111;
      text-align: center;
      font-size: 16px;
      font-weight: 600;
      line-height: 16px; /* 100% */
      letter-spacing: 1px;
    }

    text:last-child {
      color: #a8a8a8;
      text-align: center;
      font-size: 12px;
      font-weight: 500;
      line-height: 16px; /* 133.333% */
      letter-spacing: 1px;
    }
  }

  .bottom-wrapper {
    padding: 0 32rpx 8px;
    display: flex;
    gap: 24rpx;
    overflow: auto;

    image {
      height: 320rpx;
      width: 240rpx;
      border-radius: 8px;
      overflow: hidden;
      background: $image-skeleton-background-pink-font-size-14;
    }
  }

  .popular-image-list-wrapper {
    margin-top: 10px;

    .popular-bottom-wrapper {
      padding: 0 0 8px;
      display: flex;
      gap: 24rpx;
      overflow: auto;

      image {
        height: calc(100vw - 64rpx);
        width: calc(100vw - 64rpx);
        margin: 0 32rpx;
        border-radius: 8px;
        background: $image-skeleton-background-pink-font-size-14;
      }
    }
  }

  .swiper {
    width: 100%;
    height: calc(100vw - 64rpx);

    .swiper-item-wrapper {
      position: relative;

      .title {
        position: absolute;
        right: 72rpx;
        bottom: 46px;
        color: #fff;
        font-size: 33px;
        font-weight: 600;
      }

      .desc {
        position: absolute;
        right: 72rpx;
        bottom: 32px;
        color: rgba(255, 255, 255, 0.79);
        font-size: 12px;
        font-weight: 500;
      }

      .tips {
        position: absolute;
        left: 56rpx;
        top: 14px;
        color: rgba(255, 255, 255, 0.66);
        font-size: 12px;
        font-weight: 500;
      }

      .tips-banner {
        position: absolute;
        right: 56rpx;
        top: 0px;
        line-height: 60rpx;
        color: rgba(255, 255, 255, 0.66);
        font-size: 12px;
        font-weight: 500;
      }

      image {
        position: relative;
      }

      .banner {
        position: absolute;
        top: 0;
        left: 0;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        width: 182rpx;
        height: 54rpx;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ff3f6d;
        border-radius: 0 0 16px 0;
      }
    }
  }
}

.popular-list-wrapper {
  .bottom-wrapper {
    image {
      height: 480rpx;
      width: 480rpx;
    }
  }
}
</style>
