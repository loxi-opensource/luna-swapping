<template>
    <div class="pages-setting ">
        <el-scrollbar height="100%">
            <div
                class="title flex items-center before:w-[3px] before:h-[14px] before:block before:bg-primary before:mr-2"
            >
                {{ widget?.title }}
            </div>
            <keep-alive>
                <component
                    class="pt-5 pr-4"
                    :is="widgets[widget?.name]?.attr"
                    :content="widget?.content"
                    :styles="widget?.styles"
                    :type="type"
                />
            </keep-alive>
        </el-scrollbar>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import widgets from '../widgets'

const props = defineProps({
    widget: {
        type: Object as PropType<Record<string, any>>,
        default: () => ({})
    },
    type: {
        type: String as PropType<'mobile' | 'pc'>,
        default: 'mobile'
    }
})
</script>

<style lang="scss" scoped>
.pages-setting {
    width: 500px;
    height: calc(100vh - 180px);
    padding: 20px;
}
</style>
