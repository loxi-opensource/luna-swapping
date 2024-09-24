<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            title="合辑模板 / 子模版库 / 添加模板"
            :async="true"
            width="920px"
            @confirm="handleSubmit"
            @close="handleClose"
            confirm-button-text="确认添加"
        >
            <div class="flex flex-wrap">为合辑模板【{{ usageDetails.name }}】添加子模版</div>
            <div>
                <el-form ref="formRef" class="mt-4" :model="queryParams" :inline="true">
                    <el-form-item label="模版名称">
                        <el-input v-model="queryParams.name"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="onReset">重置</el-button>
                        <el-button
                            @click="filterSelected"
                            :icon="Filter"
                            v-if="selectedTemplates.length > 0"
                            >只看已选模板</el-button
                        >
                    </el-form-item>
                </el-form>
                <div>
                    <div>已选模板：</div>
                    <div class="my-2 flex flex-wrap gap-2 border-2 rounded py-4 px-2">
                        <span
                            class="py-1 px-2 border-2 border-primary rounded-2xl"
                            v-for="item in selectedTemplates"
                            :key="item.id"
                            >{{ item.id + ' | ' + item.name }}</span
                        >
                    </div>
                </div>
                <div>
                    <el-table
                        size="large"
                        height="400px"
                        v-loading="pager.loading"
                        :data="pager.lists"
                        ref="tableRef"
                        @row-click="handleRowClick"
                    >
                        <el-table-column type="selection" width="55" />
                        <el-table-column label="ID" prop="id" width="100" />
                        <el-table-column label="类型" width="80">
                            <template #default="{ row }">
                                {{ row.is_collection ? '合辑' : '单张' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="名称"
                            prop="name"
                            width="200"
                            show-overflow-tooltip
                        />
                        <el-table-column label="算法端ID" prop="up_template_id" width="100" />
                        <el-table-column label="目标图">
                            <template #default="{ row }">
                                <image-preview-inside-cell
                                    :imageUrl="row.image_url"
                                    :originWidth="60"
                                    :popUpwidth="300"
                                />
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="添加时间"
                            prop="create_time"
                            width="180"
                            show-overflow-tooltip
                        />
                    </el-table>
                    <div class="flex justify-end mt-4">
                        <pagination v-model="pager" @change="onPageChange" />
                    </div>
                </div>
            </div>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { usePaging } from '@/hooks/usePaging'
import {
    addChildTemplates,
    templateListsNotInCollection,
    templateDetail
} from '@/api/swap_template/template'
import { Filter } from '@element-plus/icons-vue'
import feedback from '@/utils/feedback'

const emit = defineEmits(['success', 'close'])
//表单ref
const formRef = shallowRef<FormInstance>()
//弹框ref
const popupRef = shallowRef<InstanceType<typeof Popup>>()
//使用详情
const usageDetails = ref<any>({
    name: '',
    children_cnt: 0
})

// 已选择的模板
const selectedTemplates = ref<any[]>([])
// 点击表格行也可以选中/取消
const tableRef = ref<any>(null)
const handleRowClick = (row: any) => {
    console.log('row=>', row)
    if (tableRef.value) {
        tableRef.value.toggleRowSelection(row)
    }
    // 如果已选模板中没有，则添加
    if (!selectedTemplates.value.find((item) => item.id === row.id)) {
        selectedTemplates.value.push(row)
    } else {
        // 如果已选模板中有，则删除
        selectedTemplates.value = selectedTemplates.value.filter((item) => item.id !== row.id)
    }
    console.log('selectedTemplates=>', selectedTemplates.value)
}

//表单数据
const queryParams = reactive<any>({
    collection_id: '',
    name: '',
    id: '' // 关联关系表主键ID。可以用英文逗号拼接多个值
})

const onPageChange = () => {
    // 取消所有已选中的行
    tableRef.value.clearSelection()
    getLists()
}

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: templateListsNotInCollection,
    params: queryParams,
    size: 5
})
// 表格数据发生变化时，从已选中模板中初始化表格行的选中状态
watch(
    () => pager.lists,
    async (value) => {
        if (tableRef.value) {
            await nextTick()
            value.forEach((item: any) => {
                if (selectedTemplates.value.find((selected) => selected.id === item.id)) {
                    console.log('should toggle item=>', item)
                    tableRef.value.toggleRowSelection(item, true)
                }
            })
        }
    }
)

const filterSelected = () => {
    if (!selectedTemplates.value.length) {
        return
    }
    queryParams.id = selectedTemplates.value.map((item) => item.id).join(',')
    resetPage()
}

const onReset = () => {
    queryParams.name = ''
    queryParams.id = ''
    resetPage()
}

//获取使用详情
const getUsageDetails = async (id: number) => {
    try {
        const data = await templateDetail({ id })
        usageDetails.value = data
    } catch (error) {
        console.log('获取使用详情=>', error)
    }
}

//提交表单
const handleSubmit = async () => {
    if (!selectedTemplates.value.length) {
        return feedback.msgWarning('请至少选择一个子模板')
    }

    try {
        await addChildTemplates({
            collection_id: queryParams.collection_id,
            template_ids: selectedTemplates.value.map((item) => item.id)
        })
        popupRef.value?.close()
        emit('success')
    } catch (error) {
        return error
    }
}

const handleClose = () => {
    emit('close')
}

const open = (id: number) => {
    queryParams.collection_id = id
    popupRef.value?.open()
    getUsageDetails(id)
    getLists()
}

defineExpose({ open })
</script>
