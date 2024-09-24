<template>
    <div>
        <div>
            <el-form label-width="70px">
                <el-form-item label="是否开启">
                    <el-radio-group v-model="content.enabled">
                        <el-radio :label="1">开启</el-radio>
                        <el-radio :label="0">关闭</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="标题名称">
                    <el-input
                        class="w-[360px]"
                        placeholder="请输入"
                        v-model="content.name"
                    ></el-input>
                </el-form-item>
                <el-form-item label="菜单设置">
                    <div class="flex-1">
                        <el-alert
                            title="会员权益请前往【营销中心】-【会员套餐】添加"
                            :closable="false"
                            type="warning"
                        />
                        <!-- <draggable class="draggable" v-model="content.data" animation="300">
                            <template v-slot:item="{ element: item, index }">
                                <del-wrap
                                    :key="index"
                                    @close="handleDelete(index)"
                                    class="max-w-[400px]"
                                >
                                    <div
                                        class="bg-fill-light flex items-center w-full p-4 mt-4 cursor-move"
                                    >
                                        <material-picker
                                            v-model="item.image"
                                            upload-class="bg-body"
                                            exclude-domain
                                        />
                                        <div class="ml-3 flex-1">
                                            <el-form-item label="权益名称">
                                                <el-input
                                                    v-model="item.name"
                                                    placeholder="请输入名称"
                                                />
                                            </el-form-item>
                                        </div>
                                    </div>
                                </del-wrap>
                            </template>
                        </draggable> -->
                    </div>
                </el-form-item>
                <!-- <el-form-item>
                    <el-button type="primary" @click="handleAdd">添加权益</el-button>
                </el-form-item> -->
            </el-form>
        </div>
    </div>
</template>
<script lang="ts" setup>
import feedback from '@/utils/feedback'
import Draggable from 'vuedraggable'

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
const limit = 6

// const handleAdd = () => {
//     props.content.data?.length < limit
//     props.content.data.push({
//         image: '',
//         name: ''
//     })
// }
// const handleDelete = (index: number) => {
//     if (props.content.data?.length <= 1) {
//         return feedback.msgError('最少保留一个权益')
//     }
//     props.content.data.splice(index, 1)
// }
//
</script>

<style lang="scss" scoped></style>
