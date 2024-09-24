<template>
    <div>
        <div>
            <el-form label-width="70px">
                <el-form-item label="是否显示">
                    <el-radio-group v-model="content.enabled">
                        <el-radio :label="1">显示</el-radio>
                        <el-radio :label="0">隐藏</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="标题名称">
                    <el-input class="w-[360px]" v-model="content.name"></el-input>
                </el-form-item>
                <el-form-item label="说明内容">
                    <el-input class="w-[360px]" type="textarea" :rows="20" v-model="content.data"></el-input>
                </el-form-item>
            </el-form>
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
