<template>
    <div class="pc-aside absolute top-0 bottom-0 left-0 w-[150px]">
        <ElScrollbar>
            <div class="h-full px-[16px] flex flex-col">
                <div class="mb-auto">
                    <div class="nav">
                        <template v-for="(item, index) in nav" :key="index">
                            <div
                                v-if="item.is_show == '1'"
                                :to="item.link.path"
                                class="nav-item"
                                :class="{
                                    active: index == 0
                                }"
                            >
                                <decoration-img
                                    width="18px"
                                    height="18px"
                                    :src="index == 0 ? item.selected : item.unselected"
                                    fit="cover"
                                >
                                    <template #error>
                                        <span></span>
                                    </template>
                                </decoration-img>

                                <div class="ml-[10px]">{{ item.name }}</div>
                            </div>
                        </template>
                    </div>
                </div>
                <div>
                    <div class="menu">
                        <div class="menu-item" v-for="(item, index) in menu" :key="index">
                            <decoration-img
                                width="18px"
                                height="18px"
                                :src="item.unselected"
                                fit="cover"
                            >
                                <template #error>
                                    <span></span>
                                </template>
                            </decoration-img>

                            <span class="ml-[6px] line-clamp-1">{{ item.name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </ElScrollbar>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import DecorationImg from '../../decoration-img.vue'
const props = defineProps({
    nav: {
        type: Array as PropType<any[]>,
        default: () => []
    },
    menu: {
        type: Array as PropType<any[]>,
        default: () => []
    }
})
</script>
<style lang="scss" scoped>
.pc-aside {
    border: 2px solid var(--el-color-primary);
    :deep(.el-scrollbar__view) {
        height: 100%;
    }
    .nav {
        .nav-item {
            border-radius: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 44px;
            margin: 10px 0;

            &.active {
                @apply text-white;
                background: linear-gradient(
                    266.94deg,
                    rgba(35, 181, 113, 1) 2.32%,
                    rgba(38, 126, 247, 1) 56.15%,
                    rgba(15, 197, 254, 1) 96.76%
                );
            }
        }
    }

    .menu-item {
        display: flex;
        align-items: center;
        background-color: #f6f6f6;
        height: 40px;
        line-height: 40px;
        padding: 0 10px;
        border-radius: 4px;
        cursor: pointer;
        margin: 10px 0;
    }
}
</style>
