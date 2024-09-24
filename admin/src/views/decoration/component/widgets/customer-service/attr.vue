<template>
    <div>
        <el-form label-width="90px">
            <el-form-item label="客服标题">
                <div class="flex">
                    <el-input class="w-[240px]" v-model="content.title" />
                    <el-checkbox class="ml-2" true-label="1" false-label="0" v-model="content.title_status">显示</el-checkbox>
                </div>
            </el-form-item>
            <el-form-item label="服务时间">
                <div class="flex">
                    <el-input class="w-[240px]" v-model="content.time" />
                    <el-checkbox class="ml-2" true-label="1" v-model="content.time_status" false-label="0">显示</el-checkbox>
                </div>
            </el-form-item>
            <el-form-item label="联系电话">
                <div class="flex">
                    <el-input class="w-[240px]" v-model="content.mobile" />
                    <el-checkbox class="ml-2" true-label="1" v-model="content.mobile_status"
                        false-label="0">显示</el-checkbox>
                </div>
            </el-form-item>
            <el-form-item label="客服二维码">
                <div>
                    <material-picker v-model="content.qrcode" exclude-domain />
                    <div class="form-tips">建议图片尺寸：200*200像素；图片格式：jpg、png、jpeg</div>
                </div>
            </el-form-item>
        </el-form>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'
type OptionsType = ReturnType<typeof options>
defineProps({
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
