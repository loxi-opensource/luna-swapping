<script setup>
import { ref, defineProps, defineEmits, watch } from "vue";

// 定义 props
const props = defineProps({
  faceList: {
    type: Array,
    required: true,
  },
  imageUrl: {
    type: String,
    required: true,
  },
  renderHeight: {
    default: 900,
    type: Number,
  },
  renderWidth: {
    default: 600,
    type: Number,
  },
  showFloatingTips: {
    default: true,
    type: Boolean,
  },
});

const floatingTipsVisible = ref(props.showFloatingTips);
watch(
  () => props.showFloatingTips,
  (newValue) => {
    floatingTipsVisible.value = newValue;
  }
);

// 计算floating-tips的位置：最左边的人脸的x值作为他的x值，最下边的人脸的y值加上人脸的高度再加上20rpx作为他的y值
const floatingTipsPosition = ref({
  x: 0,
  y: 0,
});
const updateFloatingTipsPosition = () => {
  const faceList = props.faceList;
  if (!faceList.length) {
    return;
  }
  const leftMostFace = faceList.reduce((prev, current) =>
    prev.x < current.x ? prev : current
  );
  const bottomMostFace = faceList.reduce((prev, current) =>
    prev.y + prev.h < current.y + current.h ? current : prev
  );
  floatingTipsPosition.value = {
    x: leftMostFace.x * props.renderWidth,
    y: (bottomMostFace.y + bottomMostFace.h) * props.renderHeight + 20,
  };
};
updateFloatingTipsPosition();

const defaultBorderColor = "#c4c4c4";
const selectedBorderColor = "#c465ff";
const borderColor = (face) => {
  if (!face || !selectedFace.value) {
    return defaultBorderColor;
  }
  if (face.id === selectedFace.value.id) {
    return selectedBorderColor;
  }
  return defaultBorderColor;
};

const selectedFace = ref();
for (const face of props.faceList) {
  if (face.is_default) {
    selectedFace.value = face;
    break;
  }
}

const emit = defineEmits(["selectFace"]);
const handleSelectFace = (face) => {
  selectedFace.value = face;
  emit("selectFace", face);
};
</script>

<template>
  <view
    class="image-wrapper"
    :style="{ width: `${renderWidth}rpx`, height: `${renderHeight}rpx` }"
  >
    <image :src="imageUrl" mode="aspectFill" class="show-image"></image>
    <view v-for="face in faceList" :key="face.id">
      <view
        class="face-rectangle"
        @click="handleSelectFace(face)"
        :style="{
          width: `${face.w * renderWidth}rpx`,
          height: `${face.h * renderHeight}rpx`,
          left: `${face.x * renderWidth}rpx`,
          top: `${face.y * renderHeight}rpx`,
          border: `2px solid ${borderColor(face)}`,
        }"
      ></view>
    </view>
    <view
      class="floating-tips"
      v-if="floatingTipsVisible"
      :style="{
        left: `${floatingTipsPosition.x}rpx`,
        top: `${floatingTipsPosition.y}rpx`,
      }"
    >
      <slot></slot>
    </view>
  </view>
</template>

<style scoped lang="scss">
.image-wrapper {
  position: relative;

  .show-image {
    width: 100%;
    height: 100%;
    border-radius: 12px;
  }

  .face-rectangle {
    position: absolute;
    border-radius: 8px;
  }

  .floating-tips {
    position: absolute;
    background: rgba(255, 255, 255, 0.8);
    padding: 4px 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
  }
}
</style>
