<template>
  <el-card shadow="never" class="!border-none">
    <div class="flex">
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">
          {{ pager.extend.today_share_num }}
        </div>
        <div class="text-center">今日分享/次</div>
      </div>
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">
          {{ pager.extend.today_rewards_num }}
        </div>
        <div class="text-center">今日发放奖励/条</div>
      </div>
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">{{ pager.extend.share_num }}</div>
        <div class="text-center">累计分享/次</div>
      </div>
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">{{ pager.extend.invite_num }}</div>
        <div class="text-center">成功邀请/人</div>
      </div>
      <div class="flex flex-col justify-center items-center flex-1">
        <div class="font-medium text-[24px]">
          {{ pager.extend.rewards_num }}
        </div>
        <div class="text-center">共发放奖励/条</div>
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
          v-model="queryParams.user_info"
          placeholder="用户编号/昵称/手机号码"
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
      <el-table-column label="ID" prop="id" min-width="90" />
      <el-table-column label="用户信息" min-width="180">
        <template #default="{ row }">
          <div class="flex items-center">
            <el-avatar :src="row?.user?.avatar" :size="50" />
            <div class="ml-2" v-if="row?.user?.nickname">
              {{ row?.user?.nickname || "-" }}
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="分享渠道" prop="channel_desc" min-width="120">
        <template #default="{ row }">
          <span class="">
            {{ row.channel_desc || "-" }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="分享时间" prop="create_time" min-width="120" />

      <el-table-column label="点击量" prop="click_num" min-width="120" />
      <el-table-column label="成功邀请" min-width="120">
        <template #default="{ row }"> {{ row.invite_num }}人 </template>
      </el-table-column>
      <el-table-column label="分享奖励" min-width="120">
        <template #default="{ row }"> {{ row.rewards }}条 </template>
      </el-table-column>
    </el-table>
    <div class="flex justify-end mt-4">
      <pagination v-model="pager" @change="getLists" />
    </div>
  </el-card>
</template>
<script setup lang="ts">
import { getshareLists } from "@/api/marketing/share";
import { usePaging } from "@/hooks/usePaging";

/**
 * 初始化数据
 */
const queryParams = reactive({
  user_info: "",
  start_time: "",
  end_time: "",
});
const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: getshareLists,
  params: queryParams,
});
getLists();
</script>
