<template>
  <view class="wrapper">
    <view class="content">
      <view class="left-image-list-wrapper image-list-wrapper">
        <view
          class="image-wrapper"
          v-for="(item, index) in leftList"
          :key="index"
        >
          <image
            :src="item.image_url"
            mode="widthFix"
            :lazy-load="true"
            @tap="onSelectTemplate(item)"
          ></image>
          <view
            v-if="isTemplateSelected(item)"
            class="selected-mask"
            @tap="onSelectTemplate(item)"
          ></view>
        </view>
      </view>
      <view class="image-list-wrapper">
        <view
          class="image-wrapper"
          v-for="(item, index) in rightList"
          :key="index"
        >
          <image
            :src="item.image_url"
            mode="widthFix"
            :lazy-load="true"
            @tap="onSelectTemplate(item)"
          ></image>
          <view
            v-if="isTemplateSelected(item)"
            class="selected-mask"
            @tap="onSelectTemplate(item)"
          ></view>
        </view>
      </view>
    </view>

    <view
      class="generate-button-wrapper"
      :class="!isGenerateBtnExpend && 'generate-button-wrapper-small'"
      @tap="$debounceClick(handleGenerateBtnClick)()"
      :style="
        selectedTemplates.length == 0 && {
          backgroundColor: '#c465ff',
          backdropFilter: 'none',
          backgroundImage: 'none',
        }
      "
    >
      <view class="image-count-wrappe">
        <text class="selected-image-count">{{ selectedTemplates.length }}</text>
        <text class="image-limit">/{{ maxSelectedNum }}</text>
      </view>
      <view class="generate-text">生成</view>
    </view>
  </view>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { DraftStore, DraftType } from "../../store/draft";

const templateList = ref([]);
const selectedTemplates = ref([]);
const maxSelectedNum = 5;

const store = useStore();
const currGroupDetail = store.state.browseringGroupDetail;
templateList.value = currGroupDetail.templateList;

const leftList = computed(() => {
  if (!templateList.value) return [];
  return templateList.value.filter((_, index) => index % 2 === 0);
});

const rightList = computed(() => {
  if (!templateList.value) return [];
  return templateList.value.filter((_, index) => index % 2 !== 0);
});

const handleGenerateBtnClick = () => {
  if (selectedTemplates.value.length === 0) {
    uni.showToast({
      title: "请至少先选择1张模板",
      icon: "none",
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
        group_name: currGroupDetail.groupName,
        group_id: currGroupDetail.groupId,
        face_list: item.face_list,
      });
    })
  );

  uni.navigateTo({
    url: `/pages/draw/confirm`,
  });
};

const isTemplateSelected = (item) => {
  return selectedTemplates.value.find((el) => el.id === item.id);
};

const onSelectTemplate = (template) => {
  if (!selectedTemplates.value.find((item) => item.id === template.id)) {
    if (selectedTemplates.value.length < maxSelectedNum) {
      selectedTemplates.value = [...selectedTemplates.value, template];
    }
  } else {
    selectedTemplates.value = selectedTemplates.value.filter(
      (item) => item.id !== template.id
    );
  }
};
</script>

<style lang="scss" scoped>
@import "@/common/variable.scss";

.wrapper {
  height: 100vh;
  // background: url('../../static/page-bg.jpg') repeat;
  background-size: 100%;
  flex-flow: column;
  overflow: hidden;
  box-sizing: border-box;
  padding: 12px 0;
  display: flex;
  //padding-bottom: 102px;
}

.content {
  display: flex;
  justify-content: space-between;
  overflow: auto;

  .image-list-wrapper {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding-right: 32rpx;
  }

  .image-wrapper {
    position: relative;
    margin-bottom: 2px;
    image {
      width: 324rpx;
      border-radius: 8px;
      background: $image-skeleton-background-pink-font-size-14;
    }

    text {
      position: absolute;
      text-align: start;
      left: 8px;
      bottom: 8px;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      line-height: 16px; /* 100% */
      letter-spacing: 1px;
      z-index: 3;
    }
  }

  .selected-mask {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 3px;
    z-index: 3;
    border-radius: 8px;
    border: 3px solid #c465ff;
    box-sizing: border-box;
  }

  .image-wrapper::before {
    position: absolute;
    bottom: 0;
    height: 62px;
    border-radius: 8px;
    content: "";
    left: 0;
    right: 0;
    //background: linear-gradient(180deg, rgba(80, 40, 135, 0) 0%, rgba(255, 168, 189, 0.56) 97.58%);
    z-index: 2;
    // border-radius: 0 0 8px 8px;
  }

  .left-image-list-wrapper {
    padding-left: 32rpx;
    padding-right: 0;
    .image-wrapper:nth-child(odd) {
      image {
        width: 330rpx;
      }
    }

    .image-wrapper:nth-child(even) {
      image {
        width: 324rpx;
        height: 206px;
      }
    }
  }
}

.generate-button-wrapper {
  padding-left: 36px;
  transition: 0.8s all;
  position: absolute;
  box-sizing: border-box;
  width: 125px;
  right: 0;
  bottom: 72px;
  z-index: 10;
  background-image: linear-gradient(
    270deg,
    rgba(175, 126, 255, 1) 106%,
    rgba(175, 126, 255, 0) -4%
  );
  backdrop-filter: blur(5.5px);

  display: flex;
  height: 126rpx;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;

  .selected-image-count {
    color: #fff;
    text-align: center;
    font-size: 20px;
    font-weight: 500;
    line-height: 18px; /* 90% */
  }
  .image-limit {
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
    font-weight: 500;
    line-height: 18px;
  }

  .generate-text {
    margin-top: 8rpx;
    color: #fff;
    text-align: center;
    font-size: 12px;
    font-weight: 500;
    line-height: 18px; /* 150% */
  }
}

.generate-button-wrapper-small {
  width: 140rpx;
  padding-left: 0px;
}
</style>
