<template>
    <div>
        <el-form label-width="70px">
            <el-form-item label="海报背景" v-if="type == 'mobile'">
                <el-radio-group v-model="content.default">
                    <div>
                        <el-radio :label="1">系统默认</el-radio>
                        <el-radio :label="2">自定义</el-radio>
                    </div>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="" v-if="type == 'mobile' && content.default == 1">
                <!-- <img
                    :src="appStore.getImageUrl(content.defaultUrl)"
                    alt=""
                    class="w-[80px] h-[120px] mr-[30px] cursor-pointer p-[10px]"
                    @click="handlechange(1)"
                    :class="{ actived: content.poster == 1 }"
                /> -->
                <img
                    :src="appStore.getImageUrl(content.defaultUrl1)"
                    alt=""
                    class="w-[80px] h-[120px] mr-[30px] cursor-pointer p-[10px]"
                    @click="handlechange(1)"
                    :class="{ actived: content.poster == 1 }"
                />
                <img
                    :src="appStore.getImageUrl(content.defaultUrl2)"
                    alt=""
                    class="w-[80px] h-[120px] cursor-pointer p-[10px]"
                    :class="{ actived: content.poster == 2 }"
                    @click="handlechange(2)"
                />
            </el-form-item>
            <el-form-item label="" v-if="type == 'mobile' && content.default == 2">
                <div>
                    <material-picker v-model="content.posterUrl" exclude-domain />
                    <div class="form-tips">建议尺寸: 540 * 900</div>
                </div>
            </el-form-item>
            <el-form-item label="邀请文案" v-if="type == 'mobile'">
                <div class="flex">
                    <el-input
                        placeholder
                        v-model="content.data.content"
                        class="w-[300px]"
                    ></el-input>
                    <el-checkbox
                        class="ml-2"
                        :true-label="1"
                        v-model="content.showData"
                        :false-label="0"
                        >显示</el-checkbox
                    >
                </div>
            </el-form-item>
        </el-form>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'

import useAppStore from '@/stores/modules/app'

type OptionsType = ReturnType<typeof options>
const props = defineProps({
    content: {
        type: Object as PropType<OptionsType['content']>,
        default: () => ({})
    },
    styles: {
        type: Object as PropType<OptionsType['styles']>,
        default: () => ({})
    },
    type: {
        type: String as PropType<'mobile' | 'pc'>,
        default: 'mobile'
    }
})

const appStore = useAppStore()

const handlechange = (val: any) => {
    props.content.poster = val
}
</script>

<style lang="scss" scoped>
.actived {
    border: 1px solid rgba(64, 115, 250, 1);
}
</style>
