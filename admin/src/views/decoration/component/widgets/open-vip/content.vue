<template>
    <div class="banner mt-[-50px] p-[10px]">
        <div class="container rounded-t-lg">
            <div class="flex justify-between items-center">
                <div class="flex">
                    <decoration-img width="40px" height="40px" :src="content.icon" alt="" />
                    <div class="ml-2">
                        <div class="font-bold">{{ content.title }}</div>
                        <div class="text-[12px]">{{ content.sub_title }}</div>
                    </div>
                </div>

                <div class="bg-[#ffc244] py-[5px] px-[10px] rounded-full text-[12px]">
                    {{ content.btn }}
                </div>
            </div>
        </div>
        <div class="bg-white rounded-b-lg p-[10px] flex justify-between">
            <div>剩余条数</div>
            <div class="flex items-center">
                <div>1条</div>
                <el-icon>
                    <ArrowRight />
                </el-icon>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'
import DecorationImg from '../../decoration-img.vue'
type OptionsType = ReturnType<typeof options>
const props = defineProps({
    content: {
        type: Object as PropType<OptionsType['content']>,
        default: () => ({})
    },
    styles: {
        type: Object as PropType<OptionsType['styles']>,
        default: () => ({})
    }
})
const getImage = computed(() => {
    const { data } = props.content
    if (Array.isArray(data)) {
        return data[0] ? data[0].image : ''
    }
    return ''
})
</script>

<style lang="scss" scoped>
.container {
    background-color: #fff1d4;
    padding: 10px;
}
</style>
