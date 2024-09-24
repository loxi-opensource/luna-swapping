<template>
    <div class="container mt-2 p-2">
        <div class="bg-white rounded-lg p-2">
            <span class="font-bold">用户评价</span>
            <div class="mt-4" v-for="item in content.data" :key="item">
                <div class="flex items-center justify-between">
                    <div class="flex">
                        <img
                            src="../../../image/user2.jpeg"
                            alt=""
                            class="w-[30px] h-[30px] rounded-full"
                        />
                        <div class="ml-2">
                            <div class="font-bold">花开半夏yyyy</div>
                            <div class="text-[#999999] text-[12px]">年度会员</div>
                        </div>
                    </div>
                    <div>
                        <el-rate :model-value="5" disabled />
                    </div>
                </div>
                <div class="mt-2">
                    抖音推荐发现了这款软件，输入任何问题，人工智能大师都能给出自己的答案。还支持上下文，可以持续和它探讨，非常有意思，果断购买！
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'

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
</script>

<style lang="scss" scoped></style>
