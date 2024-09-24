<template>
  <el-card shadow="never" class="!border-none">
    <div class="flex">
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">
          {{ pager.extend.today_invite_num }}
        </div>
        <div>今日邀请/次</div>
      </div>
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">
          {{ pager.extend.today_rewards_num }}
        </div>
        <div>今日发放奖励/条</div>
      </div>
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">{{ pager.extend.invite_num }}</div>
        <div>累计邀请/次</div>
      </div>
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">
          {{ pager.extend.rewards_num }}
        </div>
        <div>共发放奖励/条</div>
      </div>
    </div>
  </el-card>
  <el-card shadow="never" class="!border-none mt-4">
    <el-form
      ref="formRef"
      class="mb-[-16px]"
      :model="queryParams"
      :inline="true"
    >
      <el-form-item label="用户信息">
        <el-input
          class="w-[280px]"
          v-model="queryParams.new_user_info"
          placeholder="请输入用户ID编号/用户昵称"
          clearable
          @keyup.enter="resetPage"
        />
      </el-form-item>
      <el-form-item label="邀请人信息">
        <el-input
          class="w-[280px]"
          v-model="queryParams.user_info"
          placeholder="请输入邀请人ID/昵称"
          clearable
          @keyup.enter="resetPage"
        />
      </el-form-item>
      <el-form-item label="分享时间">
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
      <el-table-column label="ID" prop="id" min-width="100" />
      <el-table-column label="邀请人信息" min-width="120">
        <template #default="{ row }">
          <span v-if="!row.user">-</span>
          {{ row.user?.nickname }}
        </template>
      </el-table-column>
      <el-table-column label="新用户信息" min-width="180">
        <template #default="{ row }">
          <span v-if="!row.new_user">-</span>
          <div class="flex items-center" v-else>
            <el-avatar :src="row.new_user?.avatar" :size="50" />
            <div class="ml-2">
              {{ row.new_user?.nickname }}
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="注册来源" min-width="120">
        <template #default="{ row }">
          <span v-if="!row.new_user">-</span>
          {{ row.new_user?.channel_desc }}
        </template>
      </el-table-column>
      <el-table-column label="注册时间" min-width="120">
        <template #default="{ row }">
          <span v-if="!row.new_user">-</span>
          {{ row.new_user?.create_time }}
        </template>
      </el-table-column>
      <el-table-column label="邀请奖励" prop="rewards" min-width="100" />
    </el-table>
    <div class="flex justify-end mt-4">
      <pagination v-model="pager" @change="getLists" />
    </div>
  </el-card>
</template>
<script setup lang="ts">
import { getinviteLists } from "@/api/marketing/invite";
import { usePaging } from "@/hooks/usePaging";

/**
 * 初始化数据
 */
const queryParams = reactive({
  user_info: "",
  new_user_info: "",
  start_time: "",
  end_time: "",
});
const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: getinviteLists,
  params: queryParams,
});
getLists();
</script>
