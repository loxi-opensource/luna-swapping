<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="用户ID" prop="user_id">
                    <el-input
                        class="w-[160px]"
                        v-model="queryParams.user_id"
                        clearable
                        placeholder="请输入用户ID"
                    />
                </el-form-item>
                <el-form-item label="订单编号" prop="order_sn">
                    <el-input
                        class="w-[200px]"
                        v-model="queryParams.order_sn"
                        clearable
                        placeholder="请输入订单编号"
                    />
                </el-form-item>
                <el-form-item label="订单ID" prop="order_id">
                    <el-input
                        class="w-[140px]"
                        v-model="queryParams.order_id"
                        clearable
                        placeholder="请输入订单ID"
                    />
                </el-form-item>
                <el-form-item label="上游任务ID" prop="up_task_id">
                    <el-input
                        class="w-[140px]"
                        v-model="queryParams.up_task_id"
                        clearable
                        placeholder="请输入上游任务ID"
                    />
                </el-form-item>
                <el-form-item label="任务状态" prop="status">
                    <el-select
                        class="w-[140px]"
                        v-model="queryParams.status"
                        clearable
                        placeholder="请选择任务状态"
                    >
                        <el-option label="全部" value=""></el-option>
                        <el-option
                            v-for="(item, index) in dictData.task_status"
                            :key="index"
                            :label="item.name"
                            :value="item.value"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <div>
                <el-table
                    :data="pager.lists"
                    @selection-change="handleSelectionChange"
                    size="small"
                >
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="ID" prop="id" show-overflow-tooltip />
                    <el-table-column label="用户ID" prop="user_id" show-overflow-tooltip />
                    <el-table-column label="用户上传图片" width="160">
                        <template #default="{ row }">
                            <div class="flex flex-wrap gap-1">
                                <el-image
                                    :src="url"
                                    v-for="(url, index) in row.user_file_list"
                                    :key="url"
                                    fit="cover"
                                    class="w-10 h-10"
                                    :zoom-rate="1.2"
                                    :max-scale="1"
                                    :min-scale="0.2"
                                    :initial-index="index"
                                    :preview-src-list="row.user_file_list"
                                    :infinite="true"
                                    :preview-teleported="true"
                                />
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="生成结果图" width="400">
                        <template #default="{ row }">
                            <div class="flex flex-wrap gap-1">
                                <el-image
                                    :src="url"
                                    v-for="(url, index) in row.result_images_thumb"
                                    :key="url"
                                    fit="cover"
                                    class="w-10 h-10"
                                    :zoom-rate="1.2"
                                    :max-scale="1"
                                    :min-scale="0.2"
                                    :initial-index="index"
                                    :preview-src-list="row.result_images_thumb"
                                    :infinite="true"
                                    :preview-teleported="true"
                                />
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="作图数量" prop="draw_number" show-overflow-tooltip />
                    <el-table-column label="任务状态" prop="status">
                        <template #default="{ row }">
                            <dict-value :options="dictData.task_status" :value="row.status" />
                        </template>
                    </el-table-column>
                    <el-table-column
                        label="创建时间"
                        prop="create_time"
                        show-overflow-tooltip
                        width="160"
                    />
                    <el-table-column label="订单类型">
                        <template #default="{ row }">
                            <div v-if="row.order_id < 1000000000">
                                <el-tag type="warning">单笔充值</el-tag>
                            </div>
                            <div v-else-if="row.order_id < 2000000000">
                                <el-tag type="success">会员订阅</el-tag>
                            </div>
                            <div v-else-if="row.order_id < 3000000000">
                                <el-tag type="info">余额扣减</el-tag>
                            </div>
                            <div v-else-if="row.order_id < 4000000000">
                                <el-tag type="info">免费试用</el-tag>
                            </div>
                            <div v-else>-</div>
                        </template>
                    </el-table-column>
                    <el-table-column
                        label="关联订单编号"
                        prop="order_sn"
                        show-overflow-tooltip
                        width="180"
                    />
                    <el-table-column
                        label="关联订单ID"
                        prop="order_id"
                        show-overflow-tooltip
                        width="120"
                    />
                    <el-table-column label="上游ID" prop="up_task_id" show-overflow-tooltip />
                    <el-table-column label="重试任务" prop="is_retry">
                        <template #default="{ row }">
                            <dict-value :options="dictData.is_or_not" :value="row.is_retry" />
                        </template>
                    </el-table-column>
                    <el-table-column label="带水印" prop="is_watermark">
                        <template #default="{ row }">
                            <dict-value :options="dictData.is_or_not" :value="row.is_watermark" />
                        </template>
                    </el-table-column>
                    <el-table-column label="失败信息" prop="error_msg" show-overflow-tooltip />
                </el-table>
            </div>
            <div class="flex mt-4 justify-end">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <edit-popup
            v-if="showEdit"
            ref="editRef"
            :dict-data="dictData"
            @success="getLists"
            @close="showEdit = false"
        />
    </div>
</template>

<script lang="ts" setup name="lunaDrawingTaskLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiLunaDrawingTaskLists } from '@/api/luna_drawing_task'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)

// 查询条件
const queryParams = reactive({
    user_id: '',
    order_sn: '',
    order_id: '',
    up_task_id: '',
    tag_file_id: '',
    status: '',
    is_retry: ''
})

// 选中数据
const selectData = ref<any[]>([])

// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id)
}

// 获取字典数据
const { dictData } = useDictData('task_status,is_or_not')

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiLunaDrawingTaskLists,
    params: queryParams
})

getLists()
</script>
