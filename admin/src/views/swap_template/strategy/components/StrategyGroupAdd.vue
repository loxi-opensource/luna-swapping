<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            title="玩法策略 / 添加模板组"
            :async="true"
            width="920px"
            @confirm="handleSubmit"
            @close="handleClose"
            confirm-button-text="确认添加"
        >
            <div class="flex flex-wrap">为玩法【{{ usageDetails.name }}】添加模版组</div>
            <div>
                <el-form ref="formRef" class="mt-4" :model="queryParams" :inline="true">
                    <el-form-item label="模版组名称">
                        <el-input v-model="queryParams.name"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="onReset">重置</el-button>
                        <el-button
                            @click="filterSelected"
                            :icon="Filter"
                            v-if="selectedTemplateGroups.length > 0"
                            >只看已选模板组</el-button
                        >
                    </el-form-item>
                </el-form>
                <div>
                    <div>已选模板：</div>
                    <div class="my-2 flex flex-wrap gap-2 border-2 rounded py-4 px-2">
                        <span
                            class="py-1 px-2 border-2 border-primary rounded-2xl"
                            v-for="item in selectedTemplateGroups"
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
                        @select-all="handleSelectAll"
                    >
                        <el-table-column type="selection" width="55" />
                        <el-table-column label="ID" prop="id" width="100" />
                        <el-table-column label="模板组类型" width="120">
                            <template #default="{ row }">
                                {{ row.is_collection ? '合辑' : '单张' }}
                            </template>
                        </el-table-column>
                        <el-table-column label="名称" prop="name" width="140" />
                        <el-table-column label="换脸模式" width="120">
                            <template #default="{ row }">
                                {{ row.is_group_swap ? '多人换脸' : '单人换脸' }}
                            </template>
                        </el-table-column>
                        <el-table-column label="模板数量" prop="template_cnt" width="100" />
                        <el-table-column label="创建时间" prop="create_time" min-width="180" />
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
import { addGroups, groupListsNotInStrategy, strategyDetail } from '@/api/swap_template/strategy'
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
const selectedTemplateGroups = ref<any[]>([])
// 点击表格行也可以选中/取消
const tableRef = ref<any>(null)
const handleSelectAll = (rows: any[]) => {
    // 如果是全选当前页，只添加当前页的数据。如果是取消全选当前页，只删除当前页的数据
    rows.length > 0
        ? pager.lists.forEach((row) => {
              if (!selectedTemplateGroups.value.find((item) => item.id === row.id)) {
                  selectedTemplateGroups.value.push(row)
              }
          })
        : pager.lists.forEach((row) => {
              selectedTemplateGroups.value = selectedTemplateGroups.value.filter(
                  (item) => item.id !== row.id
              )
          })
}
const handleRowClick = (row: any) => {
    console.log('row=>', row)
    if (tableRef.value) {
        tableRef.value.toggleRowSelection(row)
    }
    // 如果已选模板中没有，则添加
    if (!selectedTemplateGroups.value.find((item) => item.id === row.id)) {
        selectedTemplateGroups.value.push(row)
    } else {
        // 如果已选模板中有，则删除
        selectedTemplateGroups.value = selectedTemplateGroups.value.filter(
            (item) => item.id !== row.id
        )
    }
    console.log('selectedTemplateGroups=>', selectedTemplateGroups.value)
}

//表单数据
const queryParams = reactive<any>({
    strategy_id: '',
    name: '',
    id: '' // 关联关系表主键ID。可以用英文逗号拼接多个值
})

const filterSelected = () => {
    if (!selectedTemplateGroups.value.length) {
        return
    }
    queryParams.id = selectedTemplateGroups.value.map((item) => item.id).join(',')
    resetPage()
}

const onReset = () => {
    queryParams.name = ''
    queryParams.id = ''
    resetPage()
}

const onPageChange = () => {
    // 取消所有已选中的行
    tableRef.value.clearSelection()
    getLists()
}

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: groupListsNotInStrategy,
    params: queryParams
})
// 表格数据发生变化时，从已选中模板中初始化表格行的选中状态
watch(
    () => pager.lists,
    async (value) => {
        if (tableRef.value) {
            await nextTick()
            value.forEach((item: any) => {
                if (selectedTemplateGroups.value.find((selected) => selected.id === item.id)) {
                    console.log('should toggle item=>', item)
                    tableRef.value.toggleRowSelection(item, true)
                }
            })
        }
    }
)

//获取使用详情
const getUsageDetails = async (id: number) => {
    try {
        const data = await strategyDetail({ id })
        usageDetails.value = data
    } catch (error) {
        console.log('获取使用详情=>', error)
    }
}

//提交表单
const handleSubmit = async () => {
    if (!selectedTemplateGroups.value.length) {
        return feedback.msgWarning('请至少选择一个模板组')
    }

    try {
        await addGroups({
            strategy_id: queryParams.strategy_id,
            group_ids: selectedTemplateGroups.value.map((item) => item.id)
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
    queryParams.strategy_id = id
    popupRef.value?.open()
    getUsageDetails(id)
    getLists()
}

defineExpose({ open })
</script>
