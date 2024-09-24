<template>
    <div class="container mt-2 p-2">
        <div class="bg-white rounded-lg p-2">
            <span class="font-bold">会员权益</span>
            <div class="flex flex-wrap">
                <div
                    class="flex flex-col w-1/3 items-center my-2"
                    v-for="item in lists"
                    :key="item.name"
                >
                    <decoration-img width="45px" height="45px" :src="item.image" alt="" />
                    <div class="mt-2">{{ item.name }}</div>
                </div>
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

const lists: any[] = [
    {
        name: '与AI畅聊'
    },
    {
        name: '人工智能'
    },
    {
        name: '智能对话'
    },
    {
        name: '超低延迟'
    },
    {
        name: '独享接口'
    },
    {
        name: '专属客服'
    }
]
</script>

<style lang="scss" scoped></style>
