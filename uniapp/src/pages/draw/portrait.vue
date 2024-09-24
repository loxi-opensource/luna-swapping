<template>
  <view class="content">
    <view class="group-list">
      <scroll-view
        class="scroll-view_H"
        scroll-x="true"
        :enhanced="true"
        :show-scrollbar="tagScrollVisbile"
        :scroll-into-view="'i' + curTagScrollView"
        scroll-with-animation="true"
      >
        <view class="group-wrap">
          <view
            v-for="(group, index) in groupList"
            :key="group.id"
            :style="$getMediumFontWeight()"
            :id="'i' + group.id"
            :class="
              'tag-item ' +
              (group.id == selectedGroup?.id ? 'tag-selected' : '')
            "
            @tap="$debounceClick(onSelectGroup)(group)"
          >
            {{ index == 0 ? `🔥 ` : "" }}{{ group.name }}
          </view>
        </view>
      </scroll-view>
    </view>
    <swiper
      class="swiperWrap"
      :current="curPage"
      height="100%"
      :autoplay="false"
      next-margin="0"
      @change="handleSwiperChange"
    >
      <swiper-item v-for="(group, index) in groupList" :key="index">
        <scroll-view
          class="scroll-view_Y"
          scroll-y="true"
          scroll-with-animation="false"
          :enhanced="true"
          :show-scrollbar="false"
        >
          <view
            class="template-wrap"
            :class="{ 'template-wrap-padding': actionBarVisible }"
          >
            <view
              v-for="(template, index) in group.templates"
              :key="index"
              class="template-item"
              @tap="$debounceClick(onSelectTemplate)(template)"
            >
              <view style="display: flex; flex-direction: column">
                <image
                  :src="template.image_url"
                  mode="aspectFill"
                  :lazy-load="true"
                  class="template-item-img"
                ></image>
                <view class="template-desc">
                  <view class="name">
                    {{ template.name }}
                  </view>
                  <view class="desc">
                    <view>
                      <span style="margin-right: 2px">
                        {{ template.child_template_cnt }}
                      </span>
                      <span>张子模版</span>
                    </view>
                    <image :src="iconCollection" class="icon-collection" />
                  </view>
                </view>
              </view>
              <view class="selected-mask" v-if="isSelected(template.id)"></view>
            </view>
          </view>
        </scroll-view>
      </swiper-item>
    </swiper>
    <view
      class="action-bar"
      v-if="selectedTemplates?.length"
      :class="{ 'action-bar-hidden': !actionBarVisible }"
    >
      <view v-show="actionBarVisible">
        <view class="action-wrapper">
          <view class="desc" :style="$getMediumFontWeight()">{{
            t("draw-portrait.action-wrap-title")
          }}</view>
          <button
            @tap="$debounceClick(onClickNextStep)()"
            class="main-btn"
            :style="$getMediumFontWeight()"
          >
            {{ t("draw-portrait.action-wrap-btn") }}
          </button>
        </view>
        <scroll-view class="scroll-view_H" scroll-x="true">
          <view class="selected-list-wrapper">
            <view
              v-for="template in selectedTemplates"
              :key="template.id"
              class="selected-template-wrapper"
            >
              <view class="selected-template">
                <image
                  :src="template.image_url"
                  mode="aspectFill"
                  class="selected-template-img"
                ></image>
              </view>
              <view class="selected-template-title">
                {{ template.name }}
              </view>
              <image
                :src="closeIconForImage"
                class="delete-icon"
                @tap="$debounceClick(onSelectTemplate)(template)"
              />
            </view>
          </view>
        </scroll-view>
      </view>
      <view
        class="actionBarDisplayBtnWrap"
        @tap="$debounceClick(changeActionBarVisbile)()"
      >
        <image
          class="actionBarDisplayBtn"
          :class="{ closeStatusBtn: !actionBarVisible }"
          src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNyIgaGVpZ2h0PSIxMCIgdmlld0JveD0iMCAwIDE3IDEwIiBmaWxsPSJub25lIj48cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTEuNTg1IDguODk1QS43NDUuNzQ1IDAgMSAxIC41NCA3LjgzM0w3LjQ5Mi45OWMuNTYtLjU1IDEuNDU2LS41NSAyLjAxNiAwbDYuOTUyIDYuODQyYS43NDUuNzQ1IDAgMCAxLTEuMDQ1IDEuMDYyTDkuMjQ0IDIuODIzYTEuMDYgMS4wNiAwIDAgMC0xLjQ4OCAwbC02LjE3IDYuMDcyeiIgZmlsbD0iI0RERCIvPjwvc3ZnPg=="
        ></image>
      </view>
    </view>
  </view>
</template>

<script setup>
import { computed, ref } from "vue";
import { closeIcon, collectionIcon } from "../../common/svgBase64.js";
import { useStore } from "vuex";
import { DraftStore, DraftType } from "../../store/draft";
import { onLoad, onShareAppMessage } from "@dcloudio/uni-app";
import { useI18n } from "vue-i18n";

const selectedTemplates = ref([]);
const selectedGroup = ref();
const store = useStore();
const { t } = useI18n();

const tagScrollVisbile = ref(false);
const actionBarVisible = ref(true);
const curPage = ref(0);
const curTagScrollView = ref("");
const closeIconForImage = ref(closeIcon);
const iconCollection = ref(collectionIcon);
// todo 改成组件入参
const groupList = computed(() => store.state.portrait);

onLoad(async () => {
  // todo 改成组件入参
  await store.dispatch("fetchPortrait");
  const defaultSelected = groupList.value?.[0];
  selectedGroup.value = defaultSelected || [];
});

const onClickNextStep = () => {
  if (!selectedTemplates.value?.length) {
    uni.showModal({
      title: t("draw-portrait.toast-none-selected-title"),
      content: t("draw-portrait.toast-none-selected-content"),
      confirmColor: "#C465FF",
      confirmText: t("draw-portrait.toast-none-selected-confirmText"),
      showCancel: false,
    });
    return;
  }

  DraftStore.setTemplates(
    store,
    selectedTemplates.value.map((item) => {
      return new DraftType.Template({
        id: item.id,
        up_file_id: item.up_template_id,
        name: item.name,
        image_url: item.image_url,
        group_name: item.group_name,
        group_id: item.group_id,
        face_list: item.face_list,
      });
    })
  );

  // todo 动态获取，根据当前玩法信息
  DraftStore.setStrategyId(store, 1);
  DraftStore.setIsCollection(store, 1);

  uni.navigateTo({
    url: "/pages/draw/confirm",
  });
};

const onSelectGroup = (tag) => {
  selectedGroup.value = tag;
  const nextPage = groupList.value.findIndex(
    (item) => item.id == selectedGroup.value?.id
  );
  curPage.value = nextPage;
};

const onSelectTemplate = (template) => {
  if (selectedTemplates.value.find((s) => s.id === template.id)?.id) {
    selectedTemplates.value = selectedTemplates.value.filter(
      (s) => s.id !== template.id
    );
  } else {
    if (selectedTemplates.value.length < 5) {
      selectedTemplates.value.push({
        ...template,
        group_name: selectedGroup.value.name,
        group_id: selectedGroup.value.id,
      });
    } else {
      uni.showToast({
        title: t("draw-portrait.toast-exceed-max"),
        icon: "none",
      });
    }
  }
};
const isSelected = (id) => {
  return selectedTemplates.value.filter?.((cur) => cur.id === id)?.length;
};
const changeActionBarVisbile = () => {
  actionBarVisible.value = !actionBarVisible.value;
};

const handleSwiperChange = (current) => {
  const nextPage = current.detail.current;
  const groupId = groupList.value[nextPage].id;
  selectedGroup.value = groupList.value[nextPage];
  curPage.value = nextPage;
  if (current.detail.source == "touch") {
    curTagScrollView.value = groupId;
  }
};

onShareAppMessage(() => {
  return {
    title: "Luna AI写真",
    path: "/pages/draw/portrait",
  };
});
</script>

<style lang="scss" scoped>
@import "@/common/variable.scss";
.content {
  padding: 14px 0;
  height: 100%;
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
}

.scroll-view_H {
  white-space: nowrap;
  width: 100%;
}

.scroll-view_Y {
  height: 100%;
}

.swiperWrap {
  height: calc(100vh - 64px);
}

.scroll-view_Y::-webkit-scrollbar {
  width: 0 !important;
  height: 0 !important;
}

.scroll-view_Y::-webkit-scrollbar-thumb {
  background-color: transparent;
  /* 设置滚动条颜色为透明 */
}

.group-wrap {
  display: flex;
  justify-content: flex-start;
  gap: 24rpx;
  width: max-content;
}

.group-list {
  margin: 0 40rpx 14px;
}

.tag-item {
  height: 26px;
  top: 102px;
  left: 92px;
  border-radius: 8px;
  padding: 0 16rpx;
  line-height: 26px;
  color: #8f8f8f;
  text-align: center;
  font-size: 14px;
}

.tag-selected {
  background: #c465ff;
  color: #ffffff;
  font-weight: 500;
}

.template-wrap {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  justify-items: center;
  align-items: center;
  gap: 12px;
  margin-right: 40rpx;
  margin-left: 40rpx;
  padding-bottom: 90rpx;
}

.template-wrap-padding {
  padding-bottom: 360rpx;
}

.selected-template-wrapper {
  position: relative;
}

.selected-template {
  width: 130rpx;
  height: 130rpx;
  border-radius: 8px;
  overflow: hidden;
  position: relative;
}

.selected-template-img {
  width: 130rpx;
  height: 130rpx;
}

.delete-icon {
  width: 32rpx;
  height: 32rpx;
  position: absolute;
  top: -12rpx;
  right: -8rpx;
  z-index: 10;
}

.delete-icon::before {
  content: "";
  position: absolute;
  top: -32rpx;
  right: -32rpx;
  bottom: -32rpx;
  left: -32rpx;
}

.selected-list-wrapper {
  display: flex;
  gap: 14rpx;
  margin-top: 22px;
  padding-bottom: 40px;
}

.template-item {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.template-item-img {
  width: 100%;
  height: 400rpx;
  border-radius: 8px 8px 0 0;
  background: $image-skeleton-background-pink-font-size-14;
}

.checked-icon {
  width: 24px;
  height: 24px;
  position: absolute;
  right: 8px;
  bottom: 8px;
}

.file-side {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.action-wrapper {
  margin: 20px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 48rpx;
  line-height: 48rpx;
}

.selected-mask {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0px;
  z-index: 3;
  border-radius: 8px;
  border: 3px solid #c465ff;
  box-sizing: border-box;
}

.template-desc {
  height: 76rpx;
  z-index: 2;
  border-radius: 0 0 8px 8px;
  font-size: 14px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  color: #000;
  background-color: #f4f4f4;
  gap: 2px;
  padding: 8px;

  .name {
    font-size: 14px;
    font-weight: 580;
  }

  .desc {
    font-size: 12px;
    color: #909090;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    width: 100%;

    .icon-collection {
      width: 40rpx;
      height: 40rpx;
    }
  }
}

.selected-template-title {
  line-height: 18px;
  font-size: 12px;
  margin-top: 4px;
  text-align: center;
  color: #000;
}

.action-wrapper .desc {
  color: #000000;
  font-size: 12px;
  font-weight: 500;
}

.main-btn {
  background: linear-gradient(171deg, #c465ff 1.8%, #8247ff 100%);
  color: #ffffff !important;
  width: 272rpx;
  border-radius: 8px;
  font-size: 22px;
  font-weight: 500;
  line-height: 96rpx;
  padding: 0;
  transform: scale(0.5) translate(50%);
  margin: 0;
}

.main-btn::after {
  border: none;
}

.main-btn-disabled {
  background: #f5f5f5;
  color: #b8b8b8 !important;
}

.action-bar {
  background-color: #f5f6f7;
  padding: 0 24rpx 0 16rpx;
  position: absolute;
  bottom: 0px;
  left: 0px;
  right: 0px;
  filter: drop-shadow(0px -1px 8px rgba(0, 0, 0, 0.25));
  border-radius: 14px 14px 0 0;
  transition: max-height 0.3s;
  max-height: 400rpx;
}

.action-bar-hidden {
  max-height: 80rpx;
  height: 80rpx;
}

.actionBarDisplayBtnWrap {
  position: absolute;
  top: 0;
  left: 50%;
  transform: translate(-50%, -50%);
  border-radius: 14px;
  background: #f5f6f7;
  width: 120rpx;
  height: 36rpx;
  display: flex;
  justify-content: center;
  align-items: center;
}

.actionBarDisplayBtnWrap::before {
  content: "";
  position: absolute;
  top: -18rpx;
  bottom: -18rpx;
  left: -10rpx;
  right: -10rpx;
}

.actionBarDisplayBtn {
  width: 34rpx;
  height: 19rpx;
  background-color: transparent;
  transition: transform 0.3s;
  transform-origin: center;
  transform: rotateZ(180deg);
}

.closeStatusBtn {
  transform: rotate(0deg);
}
</style>
