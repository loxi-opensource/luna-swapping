<template>
  <el-card shadow="never" class="!border-none">
    <el-form ref="formRef" class="mb-[-16px]" :inline="true">
      <el-form-item label="反馈类型">
        <el-select class="!w-[280px]" v-model="queryParams.type">
          <el-option label="全部" value />
          <el-option label="投诉" value="3" />
          <el-option label="故障" value="1" />
          <el-option label="建议" value="2" />
        </el-select>
      </el-form-item>
      <el-form-item label="提交时间">
        <daterange-picker
          v-model:startTime="queryParams.start_time"
          v-model:endTime="queryParams.end_time"
        />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="resetPage">查询</el-button>
        <el-button @click="resetParams">重置</el-button>
      </el-form-item>
    </el-form>
  </el-card>
  <el-card class="!border-none mt-4" shadow="never">
    <el-table size="large" v-loading="pager.loading" :data="pager.lists">
      <el-table-column label="用户昵称" min-width="100">
        <template #default="{ row }">
          <div class="flex items-center">
            <el-avatar :src="row.avatar" :size="50" />
            <div class="ml-[10px]">{{ row.nickname }}</div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="联系方式" prop="mobile" min-width="100" />
      <el-table-column label="反馈类型" prop="type_desc" min-width="100" />
      <el-table-column label="反馈内容" prop="content" min-width="100" />
      <el-table-column label="提交时间" prop="create_time" min-width="100" />
    </el-table>
  </el-card>
</template>
<script setup lang="ts">
import { feedbackList } from "@/api/feedback/feedback";

import { usePaging } from "@/hooks/usePaging";
const queryParams = reactive({
  type: "",
  start_time: "",
  end_time: "",
});

//分页组件
const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: feedbackList,
  params: queryParams,
});

onMounted(() => {
  getLists();
});
</script>
<style lang="scss" scoped></style>
