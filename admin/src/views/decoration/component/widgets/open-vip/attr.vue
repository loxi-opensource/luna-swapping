<template>
    <div>
        <el-form label-width="70px">
            <el-form-item label="会员图标">
                <material-picker v-model="content.icon" exclude-domain />
            </el-form-item>
            <el-form-item label="标题名称">
                <el-input class="w-[360px]" v-model="content.title"></el-input>
            </el-form-item>
            <el-form-item label="副标题名">
                <el-input class="w-[360px]" v-model="content.sub_title"></el-input>
            </el-form-item>
            <el-form-item label="按钮文案">
                <el-input class="w-[360px]" v-model="content.btn"></el-input>
            </el-form-item>
        </el-form>
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
