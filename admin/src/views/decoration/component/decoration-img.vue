<template>
  <el-image :style="styles" v-bind="props" :src="getImageUrl(src)">
    <template #placeholder>
      <slot name="placeholder">
        <div class="image-slot"></div>
      </slot>
    </template>
    <template #error>
      <slot name="error">
        <div class="image-slot">
          <icon name="el-icon-Picture" :size="30" />
        </div>
      </slot>
    </template>
  </el-image>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import type { CSSProperties } from "vue";
import { addUnit } from "@/utils/util";
import { imageProps } from "element-plus";
import useAppStore from "@/stores/modules/app";
const props = defineProps({
  width: {
    type: [String, Number],
    default: "auto",
  },
  height: {
    type: [String, Number],
    default: "auto",
  },
  radius: {
    type: [String, Number],
    default: 0,
  },
  ...imageProps,
});

const { getImageUrl } = useAppStore();
const styles = computed<CSSProperties>(() => {
  return {
    width: addUnit(props.width),
    height: addUnit(props.height),
    borderRadius: addUnit(props.radius),
  };
});
</script>

<style lang="scss" scoped>
.el-image {
  display: block;
  .image-slot {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    background: #fafafa;
    color: #909399;
  }
}
</style>
