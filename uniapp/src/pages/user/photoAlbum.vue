<template>
  <view class="content" :class="getTheme()">
    <view class="tips">
      <text>{{ t("user-photo-album.tip") }}</text>
    </view>
    <view class="toolBar">
      <text></text>
      <text
        class="downloadBtn"
        @tap="$debounceClick(onSaveImages)(imageList.map((el) => el.url))"
        >{{ t("user-photo-album.download-btn") }}
      </text>
    </view>
    <view v-if="photoAlbumType != 'gallary'" class="material-wrap">
      <view v-for="(item, i) in groupList" :key="i" class="groupItem">
        <view class="groupTitle">
          <text>{{ item.groupName }}</text>
        </view>
        <view
          class="groupImgWrap"
          :class="{ 'material-wrap-two-lines': imageList.length > 3 }"
        >
          <view
            v-for="(item, index) in item.imageList"
            :key="index"
            class="material-item"
            @tap="$debounceClick(onClickImage)(item)"
          >
            <image
              :src="item.url"
              mode="aspectFill"
              class="material-item-img"
              :lazy-load="true"
            ></image>
          </view>
        </view>
      </view>
    </view>
    <view
      v-else
      class="material-wrap"
      :class="{ 'material-wrap-two-lines': imageList.length > 3 }"
    >
      <view
        v-for="(img, index) in imageList"
        :key="index"
        class="material-item"
        @tap="$debounceClick(onClickImage)(img)"
      >
        <image
          :src="item.url"
          mode="aspectFill"
          class="material-item-img"
          :lazy-load="true"
        ></image>
      </view>
    </view>
  </view>
</template>

<script setup>
import { ref } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { pollTaskStatus } from "../../api/lunaDraw";
import { requestAuthThenSaveImages } from "../../utils/common";
import { LUNA_OSS_BASE_URL } from "../../common/variable";
import { AblumStore, AblumType } from "../../store/album";
import { onLoad, onUnload } from "@dcloudio/uni-app";

const store = useStore();
const { t } = useI18n();

const imageList = ref([]);
const sortedImageList = ref([]);
const groupList = ref([]);
const fromPage = ref("");
const photoAlbumType = ref("");

// 图片排序函数
const sortPhoto = (originArr) => {
  return originArr.reduce((pre, item) => {
    const group = pre.find((el) => el.groupName === item.groupName);
    if (group) {
      group.imageList.push(item);
    } else {
      pre.push({
        groupName: item.groupName,
        imageList: [item],
      });
    }
    return pre;
  }, []);
};

onLoad(async (option) => {
  fromPage.value = option.from;

  const currUptaskId = AblumStore.getUpTaskId(store);
  if (currUptaskId) {
    const res = await pollTaskStatus(currUptaskId);
    if (res.code === 1) {
      const imageListData = res.data.upstream_resp.messageList.map((item) => {
        return new AblumType.ImageItem({
          id: item.id,
          url: LUNA_OSS_BASE_URL + "/" + item.sourceFilePath,
          groupName: item.tagName,
        });
      });
      imageList.value = imageListData;
      groupList.value = sortPhoto(imageListData);
      sortedImageList.value = groupList.value
        .map((item) => item.imageList)
        .flat();
      AblumStore.setImageList(store, sortedImageList.value);
    }
  }
});

onUnload(() => {
  // 当从生成页跳转过来时强制返回订单页
  if (fromPage.value === "generate") {
    uni.reLaunch({
      url: "/pages/user/index",
    });
  }
});

const getTheme = () => {
  return photoAlbumType.value === "gallary" ? "gallaryTheme" : "portraitTheme";
};

const onClickImage = (image) => {
  const idx =
    sortedImageList.value.findIndex((item) => item.id === image.id) || 0;
  AblumStore.setPreviewImageIndex(store, idx);
  uni.navigateTo({
    url: "/pages/draw/detail",
  });
};

const onSaveImages = (images) => {
  requestAuthThenSaveImages(images);
};
</script>

<style lang="scss" scoped>
@import "@/common/variable.scss";

.gallaryTheme {
  background-color: #111;

  .tips {
    color: rgba(255, 255, 255, 0.16);
  }

  .downloadBtn {
    color: #fff;
  }

  .material-item {
    width: 100%;
  }

  .material-item-img {
    height: 384rpx;
    background: $image-skeleton-background-black-font-size-14;
  }

  .material-wrap-two-lines {
    .material-item {
      width: 322rpx;
      margin-bottom: 24rpx;
    }

    .material-item-img {
      width: calc(50vw - 53rpx);
      height: 204rpx;
      background: $image-skeleton-background-black-font-size-10;
    }
  }
}

.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 14px 40rpx;
  height: 100%;
  box-sizing: border-box;
  overflow: auto;
}

.tips {
  color: #ffacac;
  text-align: center;
  font-size: 12px;
  line-height: 18px;
  margin-bottom: 8px;
}

.toolBar {
  margin-bottom: 14px;
  display: flex;
  width: 100%;
  justify-content: space-between;
}

.downloadBtn {
  color: #000;
  text-align: center;
  font-size: 16px;
  line-height: 20px;
  font-weight: 400;
}

.groupItem {
  width: 100%;
}

.groupTitle {
  color: #999;
  font-size: 16px;
  margin-bottom: 14px;
}

.groupImgWrap {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

.material-wrap {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  padding: 0;
  width: 100%;
  // gap: 14rpx;
}

.material-item {
  border-radius: 20rpx;
  position: relative;
  margin-bottom: 12px;
  margin-top: 0;
  width: calc(50% - 13rpx);
}

.material-item-img {
  display: block;
  height: 230px;
  border-radius: 8px;
  width: 100%;
  margin: 0;
  padding: 0;
  background: $image-skeleton-background-pink-font-size-14;
}

.loadedImg {
  background: transparent;
}
</style>
