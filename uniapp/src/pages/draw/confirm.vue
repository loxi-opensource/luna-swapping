<template>
  <view class="content" :class="getTheme()">
    <view class="confirm-input">
      <view class="confirm-input-item">
        <view
          class="choosedImgWrap"
          :style="{
            width: `${renderWidth}rpx`,
            height: `${renderHeight}rpx`,
          }"
        >
          <swiper
            class="swiper"
            indicator-dots
            :interval="3000"
            :duration="600"
            indicator-active-color="#C465FF"
            indicator-color="rgba(217,217,217,0.300)"
            @change="handleSwiperChange"
            :autoplay="swiperAutoPlay"
          >
            <swiper-item
              v-for="(template, index) in userDraft.templates"
              :key="index"
              class="swiperItem"
            >
              <luna-image-face-selector
                :image-url="template.image_url"
                :face-list="template.face_list"
                @selectFace="onSelectFace"
                :render-height="renderHeight"
                :render-width="renderWidth"
                :show-floating-tips="showFloatingTips"
                v-if="!userDraft.is_collection"
              >
                <span class="highlight"> 点击框框切换目标位置 </span>
              </luna-image-face-selector>
              <image
                v-else
                :src="template.image_url"
                :style="{
                  width: `${renderWidth}rpx`,
                  height: `${renderHeight}rpx`,
                  borderRadius: '8px',
                }"
              />
              <view
                class="template-desc"
                :style="{
                  width: `${renderWidth}rpx`,
                }"
              >
                <view class="name">
                  {{
                    template.name.length > 8
                      ? template.name.slice(0, 16) + "..."
                      : template.name
                  }}
                </view>
              </view>
            </swiper-item>
          </swiper>
        </view>
      </view>
    </view>
    <view class="create-avatar-tips" v-if="!digitalAvatarList.length">
      请先创建一个数字分身
    </view>
    <view class="add-file" v-if="!digitalAvatarList.length">
      <view class="add-file-btn-list" v-if="!userFilePath">
        <view
          class="add-file-btn-wrap"
          @tap="$debounceClick(chooseImage)('camera')"
        >
          <text class="tn-icon-camera" style="font-size: 50rpx"></text>
          <div class="add-file-btn-label">
            {{ t("draw-upload.camera-btn") }}
          </div>
        </view>
        <view
          class="add-file-btn-wrap"
          @tap="$debounceClick(chooseImage)('album')"
        >
          <text class="tn-icon-image" style="font-size: 50rpx"></text>
          <div class="add-file-btn-label">
            {{ t("draw-upload.image-btn") }}
          </div>
        </view>
      </view>
    </view>
    <view class="user-input-form" v-else>
      <view class="avatar-selector form-item">
        <span class="form-label">选择数字分身</span>
        <view class="avatar-list form-value">
          <scroll-view class="scroll-view_H" scroll-x="true">
            <view class="selected-list-wrapper">
              <view
                v-for="avatar in digitalAvatarList"
                :key="avatar.id"
                class="selected-template-wrapper"
                @tap="$debounceClick(onSelectAvatar)(avatar)"
              >
                <view class="selected-template">
                  <image
                    :src="avatar.face_url"
                    mode="aspectFill"
                    class="selected-template-img"
                  ></image>
                </view>
                <view class="selected-mask" v-if="isSelected(avatar.id)"></view>
                <view class="delete-icon-wrap" v-if="!isSelected(avatar.id)">
                  <image
                    src="/static/delete-avatar-icon.svg"
                    class="delete-icon"
                    @tap.stop="$debounceClick(onDeleteAvatar)(avatar)"
                  />
                </view>
              </view>
            </view>
          </scroll-view>
        </view>
      </view>
      <view class="avatar-selected form-item">
        <span class="form-label">已选数字分身</span>
        <view class="form-value">
          <view class="avatar-wrapper">
            <image
              :src="
                selectedAvatar
                  ? selectedAvatar.face_url
                  : '/static/empty-avatar.svg'
              "
              mode="aspectFill"
              class="avatar-selected-img"
            ></image>
            <image
              :src="closeIconForImage"
              class="delete-icon"
              @tap="$debounceClick(onResetSelectedAvatar)(selectedAvatar)"
              v-if="selectedAvatar"
            />
          </view>
          <view class="add-file-small">
            <view class="add-file-small-btn-list">
              <view
                class="add-file-small-btn-wrap"
                @tap="$debounceClick(chooseImage)('camera')"
              >
                <text class="tn-icon-camera" style="font-size: 50rpx"></text>
                <div class="add-file-small-btn-label">
                  {{ t("draw-upload.camera-btn") }}
                </div>
              </view>
              <view
                class="add-file-small-btn-wrap"
                @tap="$debounceClick(chooseImage)('album')"
              >
                <text class="tn-icon-image" style="font-size: 50rpx"></text>
                <div class="add-file-small-btn-label">
                  {{ t("draw-upload.image-btn") }}
                </div>
              </view>
            </view>
          </view>
        </view>
      </view>
      <view class="draw-number-wrap">
        <view class="title" v-if="userDraft.is_collection">
          <view>每个模板生成数量</view>
          <view>{{ drawNumber }}/{{ maxDrawNumber }}</view>
        </view>
        <view class="slider-wrapper" v-if="userDraft.is_collection">
          <slider
            :value="drawNumber"
            @change="sliderChange"
            @changing="sliderChange"
            min="1"
            :max="maxDrawNumber"
            block-size="24"
            active-color="#8186FF"
            background-color="#8c8c8c4d"
            block-color="#FFF"
            style="margin: 0"
          />
        </view>
        <view class="preview-number">
          <view class="left">
            预计生成
            <span class="num-span">
              {{ totalDrawNum }}
            </span>
            张
          </view>
          <view class="right">
            <view>
              余额剩余
              <span class="num-span">{{ balanceDraw }}</span>
              张
            </view>
            <!--            <button-->
            <!--              type="plain"-->
            <!--              class="recharge-btn"-->
            <!--              style="width: 120rpx; height: 24px"-->
            <!--              @tap="$debounceClick(onRechargeBtnClick)()"-->
            <!--            >-->
            <!--              充值-->
            <!--            </button>-->
          </view>
        </view>
      </view>
      <view class="confirm-submit-wrap">
        <button
          class="main-btn"
          @tap="$debounceClick(onSubmit)()"
          data-eventsync="true"
        >
          制作照片
        </button>
      </view>
    </view>
  </view>
</template>

<script setup>
import { ref, computed, reactive } from "vue";
import { checkLogin } from "../../utils/common";
import { submitDrawingTask, uploadImage } from "../../api/lunaDraw";
import { imageMode } from "../../context.js";
import { onLoad, onShow } from "@dcloudio/uni-app";
import LunaImageFaceSelector from "../../components/LunaImageFaceSelector.vue";
import { closeIcon } from "../../common/svgBase64";
import { requireLogin } from "../../utils/request";
import { DraftStore, DraftType } from "../../store/draft";
const closeIconForImage = ref(closeIcon);

import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import {
  getDigitalAvatarList,
  removeDigitalAvatar,
} from "../../api/digitalAvatar";
import { getUserInfo } from "../../api/user";
const store = useStore();
const { t } = useI18n();

const userDraft = computed(() => DraftStore.getDraft(store));
const drawNumber = ref(4);
const maxDrawNumber = 8;
DraftStore.setRandomCandidateCnt(store, drawNumber.value);
const sliderChange = (e) => {
  drawNumber.value = e.detail.value;
  DraftStore.setRandomCandidateCnt(store, drawNumber.value);
};
const totalDrawNum = computed(() => {
  if (!userDraft.value.is_collection) {
    return userDraft.value.templates.length;
  }
  return drawNumber.value * userDraft.value.templates.length;
});
const balanceDraw = ref(0);
const renderWidth = 520;
const renderHeight = renderWidth * 1.5;

/**
 * @typedef {Object} DigitalAvatar 数字分身信息
 * @property {number} id - 数字分身ID
 * @property {number} face_id - 算法段人脸ID
 * @property {string} face_url - 脸部图片地址
 * @property {number} file_id - 文件ID
 * @property {number} up_file_id - 算法端文件ID
 */

/** @type {import('vue').Ref<DigitalAvatar[]>} */
const digitalAvatarList = ref([]);

/** @type {import('vue').Ref<DigitalAvatar | null>} */
const selectedAvatar = ref(null);

const templateSelectedAvatar = reactive({});
const initTemplateSelectedAvatar = () => {
  userDraft.value.templates.forEach((template) => {
    templateSelectedAvatar[template.up_file_id] = selectedAvatar.value;
  });
  console.log("after initTemplateSelectedAvatar", templateSelectedAvatar);
};

const chooseImage = async (sourceType) => {
  const isLogin = await checkLogin({ delta: 1 });
  if (!isLogin) {
    return;
  }

  uni.chooseImage({
    count: 1,
    sizeType: ["original", "compressed"],
    sourceType: [sourceType],
    success: (res) => {
      console.log(JSON.stringify(res.tempFilePaths));
      confirmUpload(res.tempFilePaths[0]);
    },
    fail: () => {},
  });
};

const confirmUpload = async (filePath) => {
  console.log("filePath = ", filePath);
  if (!filePath) {
    return;
  }

  uni.showLoading({ title: "数字分身制作中", mask: true });
  try {
    const res = await uploadImage(filePath);
    uni.hideLoading();
    const data = JSON.parse(res.data);
    if (data.code === -1) {
      requireLogin({
        toastMsg: t("api-toast.login-status-expired"),
      });
    }
    if (data.code !== 1) {
      uni.showToast({
        title: data.msg || t("api-toast.server-error"),
        icon: "none",
      });
      return;
    }
    getAvatarList(digitalAvatarList.value.length === 0);
  } catch (err) {
    console.log(err);
    uni.hideLoading();
  }
};

const onSelectAvatar = (avatar) => {
  console.log(avatar, "on select avatar");
  swiperAutoPlay.value = false;
  selectedAvatar.value = avatar;
  if (userDraft.value.is_collection) {
    return;
  }
  // 以下逻辑是非合辑模板专属
  templateSelectedAvatar[currTemplate.value.up_file_id] = avatar;
  // 设置templateFaceMappingList对应模板的对应人脸关系
  const templateItem = templateFaceMappingList.value.find(
    (item) => item.up_file_id == currTemplate.value.up_file_id
  );
  // templateItem.mapping 对象的key是人脸id，value是avatar的face_id。其他key设置为null
  console.log(
    "freddy debug",
    templateItem.mapping,
    currSelectedFace.value.id,
    selectedAvatar.value.face_id
  );
  for (const key in templateItem.mapping) {
    if (key == currSelectedFace.value.id) {
      templateItem.mapping[key] = selectedAvatar.value.face_id;
    } else {
      templateItem.mapping[key] = null;
    }
  }
  console.log("updated tempateMapping", templateFaceMappingList.value);
};
const onDeleteAvatar = (avatar) => {
  console.log(avatar, "on delete avatar");
  // 检查是否有模板使用了该avatar，如果有则提示用户删除失败
  const isUsed = templateFaceMappingList.value.some((item) => {
    return Object.values(item.mapping).includes(avatar.face_id);
  });

  if (isUsed) {
    uni.showToast({
      title: "该分身已有模板使用，请先取消选择",
      icon: "none",
    });
    return;
  }

  uni.showModal({
    content: "是否删除该分身？",
    confirmText: "确认删除",
    confirmColor: "#FF0000",
    success: function (res) {
      if (res.confirm) {
        console.log("确认删除");
        digitalAvatarList.value = digitalAvatarList.value.filter(
          (item) => item.id !== avatar.id
        );
        removeDigitalAvatar({ id: avatar.id });
      } else if (res.cancel) {
        console.log("用户点击取消");
      }
    },
  });

  // selectedAvatar.value = null;
};
const onResetSelectedAvatar = () => {
  selectedAvatar.value = null;
  if (userDraft.value.is_collection) {
    return;
  }
  templateSelectedAvatar[currTemplate.value.up_file_id] = null;
};
const isSelected = (id) => {
  if (userDraft.value.is_collection) {
    return selectedAvatar.value && selectedAvatar.value.id === id;
  }
  return templateSelectedAvatar[currTemplate.value.up_file_id]?.id === id;
};

const getAvatarList = async (isFirstFetching = true) => {
  const res = await getDigitalAvatarList();
  if (res.code === 1) {
    const faces = res.data.list || [];
    digitalAvatarList.value = faces.map((item) => {
      return {
        id: item.id,
        face_id: item.up_face_id,
        face_url: item.face_url,
        file_id: item.file_id,
        up_file_id: item.up_file_id,
      };
    });
    selectedAvatar.value = digitalAvatarList.value[0];
    if (
      digitalAvatarList.value.length &&
      isFirstFetching &&
      !userDraft.value.is_collection
    ) {
      initTemplateFaceMappingList();
      initTemplateSelectedAvatar();
    }
  }
};

const getUserBalance = () => {
  getUserInfo().then((res) => {
    balanceDraw.value = res.data?.balance_draw || 0;
  });
};

onLoad(() => {
  getUserBalance();
  getAvatarList();
});

onShow(() => {
  uni.$once("navigating_back:from_page_login", (event) => {
    console.log("从登录页返回", event);
    getUserBalance();
    getAvatarList();
  });
});

const showFloatingTips = ref(true);
const currSelectedFace = computed(() => {
  return currTemplate.value.face_list.find((face) => face.id);
});
const onSelectFace = (face) => {
  if (userDraft.value.is_collection) {
    // 合辑模板不支持指定人脸区域
    return;
  }

  swiperAutoPlay.value = false;
  showFloatingTips.value = false;
  console.log(face, "on select face");
  // 设置templateFaceMappingList对应模板的对应人脸关系
  const templateItem = templateFaceMappingList.value.find(
    (item) => item.up_file_id == currTemplate.value.up_file_id
  );
  // templateItem.mapping 对象的key是人脸id，value是avatar的face_id。其他key设置为null
  for (const key in templateItem.mapping) {
    if (key == face.id) {
      templateItem.mapping[key] = selectedAvatar.value?.face_id || null;
    } else {
      templateItem.mapping[key] = null;
    }
  }
  console.log("updated tempateMapping", templateFaceMappingList.value);
};

// 获取主题
const getTheme = () => {
  return imageMode.value === "gallary" ? "gallaryTheme" : "portraitTheme";
};

const swiperAutoPlay = ref(true);
// 处理滑块变化
const currTemplateIndex = ref(0);
const handleSwiperChange = (e) => {
  currTemplateIndex.value = e.detail.current;
  if (userDraft.value.is_collection) {
    return;
  }
  selectedAvatar.value = templateSelectedAvatar[currTemplate.value.up_file_id];
};

// 当前预览的模板
const currTemplate = computed(() => {
  return userDraft.value.templates[currTemplateIndex.value];
});
console.log("currTemplate", currTemplate.value);

// 初始化模板人脸映射列表
const templateFaceMappingList = ref([]);
const initTemplateFaceMappingList = () => {
  userDraft.value.templates.forEach((template) => {
    templateFaceMappingList.value.push(
      new DraftType.TemplateFaceMappingItem({
        up_file_id: template.up_file_id,
        mapping: {},
      })
    );
    template.face_list.forEach((face) => {
      templateFaceMappingList.value.find(
        (item) => item.up_file_id === template.up_file_id
      ).mapping[face.id] =
        selectedAvatar.value.face_id && face.is_default
          ? selectedAvatar.value.face_id
          : null;
    });
  });
  console.log("templateFaceMappingList", templateFaceMappingList.value);
};

// 提交绘图任务
const onSubmit = async () => {
  if (!userDraft.value.is_collection) {
    // 遍历 templateSelectedAvatar，如果有模板没有选择人脸，则提示用户选择
    for (const key in templateSelectedAvatar) {
      if (!templateSelectedAvatar[key]) {
        uni.showToast({
          title: "请为每个模板选择一个数字分身",
          icon: "none",
        });
        return;
      }
    }

    // templateFaceMappingList 遍历每一个mapping，过滤掉值为空的数据
    DraftStore.setTemplateFaceMapping(
      store,
      templateFaceMappingList.value.map((item) => {
        for (const key in item.mapping) {
          if (!item.mapping[key]) {
            delete item.mapping[key];
          }
        }
        return item;
      })
    );
    DraftStore.resetUserImage(store);
  } else {
    if (!selectedAvatar.value) {
      uni.showToast({
        title: "请先选择一个数字分身",
        icon: "none",
      });
      return;
    }
    DraftStore.setUserImage(
      store,
      new DraftType.UserImage({
        file_id: selectedAvatar.value.file_id,
        up_file_id: selectedAvatar.value.up_file_id,
        up_face_id: selectedAvatar.value.face_id,
      })
    );
    DraftStore.resetTemplateFaceMapping(store);
  }

  const res = await submitDrawingTask({
    draft: userDraft.value,
  });

  if (res.code !== 1) {
    uni.showToast({
      title: res.msg || "服务器开小差了",
      icon: "none",
    });
    return;
  }

  const upTaskId = res.data?.messageId;
  if (!upTaskId) {
    uni.showToast({
      title: "提交创作任务异常，请联系客服",
      icon: "none",
    });
    return;
  }

  uni.navigateTo({
    url:
      "/pages/draw/generate?up_task_id=" +
      encodeURIComponent(upTaskId) +
      "&show_predicate_time=" +
      encodeURIComponent(res.data.show_predicate_time),
  });
};
</script>

<style scoped lang="scss">
@font-face {
  font-family: "HelveticaNarrowRegular";
  src: url("data:font/woff;charset=utf-8;base64,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")
    format("woff");
}

.gallaryTheme {
  background-color: #111;

  .tips {
    color: #303030;
  }

  .price-plan-lable,
  .text-span {
    color: #fff;
  }

  .price-plan-item {
    background-color: transparent;
    border: 1.6px solid #202020;

    .plan-title {
      color: #ababab;
    }
  }

  .origin-price-wrap {
    background-color: #252525;

    .origin-price-label {
      color: #9e9e9e;
    }
  }

  .plan-item-selected {
    border: 1.6px solid #b05cff;
    box-shadow: 0px 4px 4px 0px rgba(189, 98, 255, 0.16);

    .plan-title {
      color: #fff;
    }
  }

  .tips-wrap > .text-span {
    color: rgba(200, 200, 200, 0.17);
  }

  .share-price-wrap {
    .origin-price-label,
    .plan-price-origin {
      color: #c5b1b7;
    }

    background-color: #5a2132;
  }
}

.content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 28rpx 40rpx;
  height: 100%;
  box-sizing: border-box;
  min-height: 600px;
  /*  #ifdef  MP-TOUTIAO  */
  min-height: 750px;
  /*  #endif  */

  ::v-deep wx-slider .wx-slider-handle-wrapper {
    height: 8px;
  }
}

.tips {
  text-align: center;
  color: #c8c8c8;
  font-size: 24rpx;
  margin-bottom: 32rpx;
}

.confirm-input {
  display: flex;
  justify-content: center;
}

.confirm-input-item {
  width: 100%;
  display: flex;
  flex-direction: column;
}

.confirm-input-item image {
  width: calc(50vw - 30px);
}

.confirm-input-item .text-span {
  text-align: center;
  font-weight: 400;
  font-size: 16px;
  line-height: 18px;
  min-height: 18px;
  margin-bottom: 12px;
}

.price-plan {
  padding: 0;
  margin-bottom: 60px;
  width: 100%;
}

.price-plan-lable {
  text-align: left;
  font-weight: 500;
  width: 100%;
  padding: 0 0 26rpx;
  font-size: 16px;
}

.ad-plan-tip {
  color: #cecece;
  letter-spacing: -0.3px;
  font-size: 8px;
  line-height: 22rpx;
  display: flex;
  align-items: center;

  image {
    height: 14rpx;
    width: 14rpx;
    margin-right: 2rpx;
  }
}

.price-plan-list {
  display: flex;
  flex-direction: column;
  gap: 28rpx;
}

.price-plan-item {
  background: transparent;
  border: 1.6px solid #ededed;
  padding: 0 24rpx;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 128rpx;
  position: relative;
}

.plan-price-origin {
  font-family: HelveticaNarrowRegular;
  font-weight: 700;
  font-size: 18px;
  line-height: 58rpx;
  display: inline-block;
  text-decoration: line-through;
  color: #9e9e9e;
}

.plan-price-discount {
  font-family: HelveticaNarrowRegular;
  font-weight: 700;
  font-size: 24px;
  //line-height: 58rpx;
  display: inline-block;
  color: white;
}

.plan-price-wrap {
  display: flex;
  gap: 22rpx;
  justify-content: center;
  align-items: baseline;
}

.plan-item-selected {
  border: 1px solid #b05cff;
  box-shadow: 0px 4px 4px 0px rgba(189, 98, 255, 0.16);
}

.price-wrap-selected {
  background-color: #ff5789;
  width: 320rpx;
  height: 80rpx;
  display: flex;
  align-items: center;

  .origin-price-label {
    color: #fff;
  }

  .plan-price-origin {
    color: #fff;
  }
}

.origin-price-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 6rpx 0 8rpx;
  width: 320rpx;
  border-radius: 8px;
  box-sizing: border-box;
}

.share-price-wrap {
  .origin-price-label,
  .plan-price-origin {
    color: #fff;
  }

  background-color: #ffaac4;
}

.priceTips {
  position: absolute;
  top: 0;
  left: 0;
  line-height: 32rpx;
  background-color: #8032ff;
  color: #fff;
  text-align: center;
  font-size: 8px;
  padding: 0 18rpx;
  border-radius: 8px 0 8px 0;
}

.share-price-tips {
  position: absolute;
  top: -42rpx;
  right: 10rpx;
  color: #fff;
  font-size: 12px;
  height: 58rpx;
  width: 280rpx;
  background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMjkiIHZpZXdCb3g9IjAgMCAxNDAgMjkiIGZpbGw9Im5vbmUiPgo8cGF0aCBkPSJNMCA3Ljk5OTk5QzAgMy41ODE3MSAzLjU4MTcyIDAgOCAwSDEzMkMxMzYuNDE4IDAgMTQwIDMuNTgxNzIgMTQwIDhWMTcuNjUzOUMxNDAgMjIuMDcyMSAxMzYuNDE4IDI1LjY1MzggMTMyIDI1LjY1MzhIMTI3Ljk2NkwxMjIuMDM5IDI4LjY1ODhDMTIxLjY0IDI4Ljg2MDggMTIxLjE1NiAyOC43NzQxIDEyMC44NTMgMjguNDQ2NkwxMTguMjY1IDI1LjY1MzhIOEMzLjU4MTczIDI1LjY1MzggMCAyMi4wNzIxIDAgMTcuNjUzOFY3Ljk5OTk5WiIgZmlsbD0idXJsKCNwYWludDBfbGluZWFyXzY1MF8zNDQ5KSIvPgo8ZGVmcz4KPGxpbmVhckdyYWRpZW50IGlkPSJwYWludDBfbGluZWFyXzY1MF8zNDQ5IiB4MT0iOTMuMzMzMyIgeTE9IjIuMjMwNzciIHgyPSI5My4zMzMzIiB5Mj0iMjkiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KPHN0b3Agc3RvcC1jb2xvcj0iI0Q1N0FGRiIvPgo8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM4RjAwRkYiLz4KPC9saW5lYXJHcmFkaWVudD4KPC9kZWZzPgo8L3N2Zz4=");
  background-repeat: no-repeat;
  background-position: center;

  text {
    position: absolute;
    top: 15%;
    left: 15%;
  }
}

.ad-plan-item-right {
  background-color: #f5f5f5;
  width: 448rpx;
  line-height: 72rpx;
  border-radius: 8px;
  font-size: 12px;
  color: #9e9e9e;
  text-align: center;
}

.price-plan-item-right {
  display: flex;
  justify-self: center;
  align-items: center;
  gap: 20rpx;
}

.price-plan-item .plan-title {
  color: #000;
  font-size: 16px;
}

.price-plan-item-right .plan-price {
  font-size: 31px;
  font-weight: bold;
  color: #fff;
  margin: 0 32rpx 0 14rpx;
}

.confirm-submit-wrap {
  display: flex;
  flex-direction: column;
  gap: 20rpx;
  z-index: 0;
  width: 100%;
  padding-bottom: 50rpx;
}

.tips-wrap .text-span {
  text-align: center;
  font-size: 24rpx;
  font-weight: 400;
  line-height: 36rpx;
  color: #c8c8c8;
}

.confirm-submit-wrap button {
  width: 100%;
  margin-top: 10rpx;
}

.loading-page {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #111111b2;
  backdrop-filter: blur(8px);
}

.loading-overlay-content {
  position: absolute;
  top: 20%;
  justify-content: center;
  display: flex;
  flex-direction: column;
  width: 100%;
  align-items: center;
  gap: 30rpx;
}

.loading-overlay-content image {
  border-radius: 30rpx;
  margin-bottom: 60rpx;
}

.loading-overlay-content .text-span {
  font-size: 24rpx;
  width: 60%;
  text-align: center;
  margin-top: 40rpx;
  color: #c8c8c8;
  line-height: 36rpx;
}

.main-btn {
  border-radius: 8px;
  background: linear-gradient(171deg, #c465ff 1.8%, #8247ff 100%);
  box-shadow: 0px 4px 8px 0px rgba(146, 78, 255, 0.5);
  color: #ffffff !important;
  width: 534rpx;
  line-height: 88rpx;
  font-size: 16px;
  font-style: normal;
  font-weight: 500;
}

.confirm-input-image {
  height: 426rpx;
  background: #f5f6f7;
  border-radius: 40rpx;
}

.choosedImgWrap {
  border-radius: 40rpx;
  background: #f5f6f7;
}

.choosedImage {
  border-radius: 40rpx;
  width: 100%;
  height: 100%;
}

.swiper {
  width: 100%;
  height: 100%;
}

.swiperItem {
  width: 100%;
  height: 100%;
  position: relative;

  .template-desc {
    position: absolute;
    bottom: 0;
    height: 30rpx;
    z-index: 2;
    font-size: 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #000;
    background-color: #f4f4f4;
    gap: 4px;
    padding: 8px 8px 28px 8px;

    .name {
      font-size: 12px;
      font-weight: 580;
    }

    .desc {
      font-size: 10px;
      color: #909090;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;

      .icon-collection {
        width: 40rpx;
        height: 40rpx;
      }
    }
  }
}

.user-input-form {
  display: flex;
  flex-direction: column;
  gap: 20rpx;
  width: 100%;
  margin-top: 40rpx;

  .form-item {
    display: flex;
    flex-direction: column;
    gap: 16rpx;

    .form-label {
      font-size: 14px;
      color: rgba(0, 0, 0, 0.8);
    }
  }

  .avatar-selector {
    .avatar-list {
      display: flex;
      justify-content: space-between;
      border: 2px solid #e5e5e5;
      border-radius: 4px;
      padding: 16rpx;

      .add-icon {
        width: 130rpx;
        height: 130rpx;
        border-radius: 8px;
      }
    }
  }

  .avatar-selected {
    .avatar-selected-img {
      width: 130rpx;
      height: 130rpx;
      border-radius: 8px;
      background: #f5f6f7;
      box-shadow: 9px 8px 15px 0px rgba(128, 0, 128, 0.5);
    }

    .form-value {
      padding: 8rpx 16rpx;
      display: flex;
      gap: 20rpx;
      justify-content: space-between;

      .avatar-wrapper {
        position: relative;

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
      }

      .add-file-small {
        display: flex;

        .add-file-small-btn-wrap {
          display: flex;
          flex-direction: column;
          width: 130rpx;
          height: 130rpx;
          border-radius: 8px;
          background: #f5f6f7;
          justify-content: center;
          align-items: center;
        }

        .add-file-small-btn-label {
          padding: 4rpx 0;
          font-size: 12px;
        }

        .add-file-small-btn-list {
          display: flex;
          justify-content: center;
          gap: 20rpx;
          width: 100%;
        }
      }
    }
  }
}

.scroll-view_H {
  white-space: nowrap;
  width: 100%;
}

.selected-list-wrapper {
  display: flex;
  gap: 14rpx;

  .selected-template-wrapper {
    position: relative;

    .selected-template {
      width: 130rpx;
      height: 130rpx;
      border-radius: 8px;
      overflow: hidden;
      position: relative;

      .selected-template-img {
        width: 130rpx;
        height: 130rpx;
      }
    }

    .selected-mask {
      position: absolute;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0px;
      z-index: 3;
      border-radius: 8px;
      border: 4px solid #c465ff;
      box-sizing: border-box;
    }

    .delete-icon-wrap {
      padding: 0 2px;
      background: #ef5350;
      border-radius: 0 8px 0 8px;
      position: absolute;
      top: 0;
      right: 0;
      z-index: 99;
      pointer-events: auto; /* 确保可以接收点击事件 */

      .delete-icon {
        width: 28rpx;
        height: 28rpx;
      }
    }
  }
}

.create-avatar-tips {
  font-size: 20px;
  font-weight: bold;
  margin: 40rpx 0;
}

.add-file {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  .add-file-btn-wrap {
    display: flex;
    flex-direction: column;
    width: 212rpx;
    height: 212rpx;
    background: #f5f6f7;
    border-radius: 30rpx;
    justify-content: center;
    align-items: center;
  }

  .add-file-btn-label {
    padding: 4rpx 0;
    font-size: 14px;
  }

  .add-file-btn-list {
    display: flex;
    justify-content: center;
    gap: 20rpx;
    width: 100%;
  }
}

.draw-number-wrap {
  width: 100%;

  .title {
    font-size: 14px;
    color: rgba(0, 0, 0, 0.8);
    font-style: normal;
    line-height: normal;

    display: flex;
    justify-content: space-between;
    padding-right: 16rpx;
  }

  .slider-wrapper {
    margin: 20rpx;
  }
}

.preview-number {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12px;
  color: rgba(0, 0, 0, 0.8);
  margin: 0 20rpx;

  .num-span {
    color: #ff5a9f;
    letter-spacing: -0.5px;
  }

  .right {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 28rpx;

    button {
      width: 100rpx;
      border-radius: 8rpx;
      background: #ff5a9f;

      color: #fff;
      font-size: 12px;
      font-weight: 600;
      text-align: center;
      letter-spacing: -0.5px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  }
}

.highlight {
  cursor: pointer; /* 鼠标悬停时显示为手型 */
  color: rgba(0, 0, 0, 0.8);
  animation: breathing 2s infinite; /* 呼吸效果 */
  font-size: 10px;
}

@keyframes breathing {
  0%,
  100% {
    transform: scale(1); /* 初始和结束状态 */
  }
  50% {
    transform: scale(1.1); /* 中间状态放大 */
  }
}
</style>
