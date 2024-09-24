<template>
  <el-menu
    :default-active="modelValue"
    class="w-[220px] pages-menu border-none"
  >
    <el-menu-item
      v-for="(item, key) in menus"
      :index="key"
      :key="item.id"
      @click="handlecilck(item.id)"
    >
      <span>{{ item.name }}</span>
    </el-menu-item>
  </el-menu>
</template>
<script lang="ts" setup>
import type { PropType } from "vue";

defineProps({
  menus: {
    type: Object as PropType<Record<string, any>>,
    default: () => ({}),
  },
  modelValue: {
    type: String,
    default: "1",
  },
});
const emit = defineEmits<{
  (event: "update:modelValue", value: string): void;
}>();
const handleSelect = (index: string) => {
  emit("update:modelValue", index);
};
const handlecilck = (id: any) => {
  emit("update:modelValue", id);
};
</script>

<style lang="scss" scoped>
.pages-menu {
  height: calc(100vh - 180px);
  padding: 20px 0 20px 20px;

  :deep(.el-menu-item) {
    border-color: transparent;

    &.is-active {
      border-right-width: 2px;
      border-color: var(--el-color-primary);
      background-color: var(--el-color-primary-light-9);
    }
  }
}
</style>
