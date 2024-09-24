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
                <img
                    :src="appStore.getImageUrl(content.defaultUrl1)"
                    alt=""
                    class="w-[80px] mr-[30px] cursor-pointer p-[10px]"
                    @click="handlechange(1)"
                    :class="{ actived: content.poster == 1 }"
                />
                <img
                    :src="appStore.getImageUrl(content.defaultUrl2)"
                    alt=""
                    class="w-[80px] cursor-pointer p-[10px]"
                    :class="{ actived: content.poster == 2 }"
                    @click="handlechange(2)"
                />
            </el-form-item>
            <el-form-item label="" v-if="type == 'mobile' && content.default == 2">
                <div>
                    <material-picker v-model="content.posterUrl" exclude-domain />
                    <div class="form-tips">建议尺寸: 750 * 400</div>
                </div>
            </el-form-item>
            <el-form-item label="邀请文案" v-if="type == 'mobile'">
                <div class="flex">
                    <el-input placeholder v-model="content.data" class="w-[300px]"></el-input>
                    <el-checkbox
                        class="ml-2"
                        :true-label="1"
                        v-model="content.showData"
                        :false-label="0"
                        >显示</el-checkbox
                    >
                </div>
            </el-form-item>
            <el-form-item label="背景颜色">
                <div>
                    <color-picker v-model="content.bgColor"></color-picker>
                </div>
            </el-form-item>
            <el-form-item label="文字颜色">
                <div>
                    <color-picker
                        v-model="content.textColor"
                        default-color="#ffffff"
                    ></color-picker>
                </div>
            </el-form-item>
            <el-form-item label="内容显示">
                <el-radio-group v-model="content.showContentType">
                    <el-radio :label="1">固定行数</el-radio>
                    <el-radio :label="2">显示全部</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item v-if="content.showContentType == 1">
                <el-slider :min="1" :max="30" v-model="content.contentNum" show-input />
            </el-form-item>
        </el-form>
    </div>
</template>
<script lang="ts" setup>
import useAppStore from '@/stores/modules/app'
import type { PropType } from 'vue'
import type options from './options'

type OptionsType = ReturnType<typeof options>
const props = defineProps({
    content: {
        type: Object,
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
