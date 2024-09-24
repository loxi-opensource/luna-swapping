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
        <el-form-item label="算法端任务ID" prop="up_task_id">
          <el-input
            class="w-[140px]"
            v-model="queryParams.up_task_id"
            clearable
            placeholder="请输入上游任务ID"
          />
        </el-form-item>
        <el-form-item label="任务状态" prop="status">
          <el-select class="!w-[140px]" v-model="queryParams.status" clearable>
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
          <el-table-column label="ID" prop="id" show-overflow-tooltip />
          <el-table-column label="模板信息" width="300">
            <template #default="{ row }">
              <div class="flex flex-wrap gap-1">
                <el-tag
                  v-for="(tl, i) in row.template_label_list"
                  :key="i"
                  type="info"
                  >{{ tl }}
                </el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column label="模板类型" min-width="120">
            <template #default="{ row }">
              {{ row.is_collection ? "合辑" : "单张" }}
            </template>
          </el-table-column>
          <el-table-column
            label="玩法策略"
            prop="strategy.name"
            width="120"
            show-overflow-tooltip
          />
          <el-table-column label="生成结果图" width="400">
            <template #default="{ row }">
              <div class="flex flex-wrap gap-1">
                <el-image
                  :src="url"
                  v-for="(url, index) in row.result_images"
                  :key="url"
                  fit="cover"
                  class="w-10 h-10"
                  :zoom-rate="1.2"
                  :max-scale="1"
                  :min-scale="0.2"
                  :initial-index="index"
                  :preview-src-list="row.result_images"
                  :infinite="true"
                  :preview-teleported="true"
                />
              </div>
            </template>
          </el-table-column>
          <el-table-column
            label="用户ID"
            prop="user_id"
            show-overflow-tooltip
          />
          <el-table-column
            label="生成数量"
            prop="draw_number"
            show-overflow-tooltip
          />
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
          <el-table-column
            label="算法端ID"
            prop="up_task_id"
            show-overflow-tooltip
          />
          <el-table-column
            label="失败信息"
            prop="error_msg"
            show-overflow-tooltip
          />
        </el-table>
      </div>
      <div class="flex mt-4 justify-end">
        <pagination v-model="pager" @change="getLists" />
      </div>
    </el-card>
  </div>
</template>

<script lang="ts" setup name="lunaDrawingTaskLists">
import { usePaging } from "@/hooks/usePaging";
import { useDictData } from "@/hooks/useDictOptions";
import { apiSwapTaskLists } from "@/api/swap_task/swap_task";

// 查询条件
const queryParams = reactive({
  user_id: "",
  order_sn: "",
  order_id: "",
  up_task_id: "",
  tag_file_id: "",
  status: "",
  is_retry: "",
});

// 选中数据
const selectData = ref<any[]>([]);

// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
  selectData.value = val.map(({ id }) => id);
};

// 获取字典数据
const { dictData } = useDictData("task_status,is_or_not");

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
  fetchFun: apiSwapTaskLists,
  params: queryParams,
});

getLists();
</script>
