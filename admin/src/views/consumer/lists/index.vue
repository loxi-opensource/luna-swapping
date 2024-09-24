<template>
  <div>
    <el-card class="!border-none" shadow="never">
      <el-form
        ref="formRef"
        class="mb-[-16px]"
        :model="queryParams"
        :inline="true"
      >
        <el-form-item label="用户信息">
          <el-input
            class="w-[220px]"
            v-model="queryParams.keyword"
            placeholder="用户ID/编号//昵称/邮箱"
            clearable
            @keyup.enter="resetPage"
          />
        </el-form-item>
        <!--                <el-form-item label="注册时间">-->
        <!--                    <daterange-picker-->
        <!--                        v-model:startTime="queryParams.create_time_start"-->
        <!--                        v-model:endTime="queryParams.create_time_end"-->
        <!--                    />-->
        <!--                </el-form-item>-->
        <el-form-item label="注册时间" prop="date_range">
          <el-date-picker
            v-model="queryParams.date_range"
            type="daterange"
            unlink-panels
            range-separator="到"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            format="YYYY/MM/DD"
            value-format="YYYY-MM-DD"
            :shortcuts="shortcuts"
            :size="size"
          />
        </el-form-item>
        <el-form-item label="注册来源">
          <el-select class="!w-[120px]" v-model="queryParams.provider">
            <el-option label="全部" value />
            <el-option label="邮箱" value="email"></el-option>
            <el-option label="苹果" value="apple"></el-option>
            <el-option label="Google" value="google"></el-option>
          </el-select>
        </el-form-item>
        <!--                <el-form-item label="开通会员">-->
        <!--                    <el-select class="!w-[280px]" v-model="queryParams.is_member">-->
        <!--                        <el-option label="全部" value="" />-->
        <!--                        <el-option label="未开通" :value="0" />-->
        <!--                        <el-option label="已开通" :value="1" />-->
        <!--                    </el-select>-->
        <!--                </el-form-item>-->
        <el-form-item>
          <el-button type="primary" @click="resetPage">查询</el-button>
          <el-button @click="resetParams">重置</el-button>
          <export-data
            class="ml-2.5"
            :fetch-fun="getUserList"
            :params="queryParams"
            :page-size="pager.size"
          />
        </el-form-item>
      </el-form>
    </el-card>
    <el-card class="!border-none mt-4" shadow="never">
      <el-table size="large" v-loading="pager.loading" :data="pager.lists">
        <el-table-column label="用户ID" prop="id" min-width="60" />
        <!--                <el-table-column label="SN码" prop="sn_code" min-width="120" />-->
        <el-table-column label="头像" min-width="60">
          <template #default="{ row }">
            <el-avatar :src="row?.avatar" :size="50">
              {{ row.nickname }}
            </el-avatar>
          </template>
        </el-table-column>
        <el-table-column label="用户昵称" prop="nickname" min-width="120" />
        <el-table-column label="邮箱" prop="email" min-width="180" />
        <!-- <el-table-column label="登录账号" prop="account" min-width="120" /> -->
        <!--                <el-table-column label="余额" min-width="160">-->
        <!--                    <template #default="{ row }">-->
        <!--                        <div>对话：{{ row.balance }}</div>-->
        <!--                        <div>绘画：{{ row.balance_draw }}</div>-->
        <!--                    </template>-->
        <!--                </el-table-column>-->
        <!--                <el-table-column label="会员" min-width="100">-->
        <!--                    <template #default="{ row }">-->
        <!--                        <div class="text-[#4073FA]">{{ row.member_desc }}</div>-->
        <!--                        &lt;!&ndash; <div v-if="row.is_end" class="text-[#FBAE00]">已到期</div> &ndash;&gt;-->
        <!--                    </template>-->
        <!--                </el-table-column>-->
        <!--                <el-table-column label="到期时间" prop="member_end_time_desc" min-width="180">-->
        <!--                    <template #default="{ row }">-->
        <!--                        <div>{{ row.member_end_time_desc }}</div>-->
        <!--                        <div v-if="row.is_end" class="text-[#FBAE00]">已到期</div>-->
        <!--                    </template>-->
        <!--                </el-table-column>-->
        <!--                <el-table-column label="累计消费" prop="total_amount" min-width="120" />-->
        <el-table-column label="注册时间" prop="create_time" min-width="120" />
        <el-table-column label="注册来源" prop="provider" min-width="60" />
        <el-table-column label="上次登录IP" prop="login_ip" min-width="140" />
        <el-table-column
          label="IP信息"
          prop="login_ipinfo"
          width="200"
          show-overflow-tooltip
        />
        <el-table-column label="操作" fixed="right" min-width="60">
          <template #default="{ row }">
            <!--                        <el-button v-perms="['user.user/detail']" type="primary" link>-->
            <!--                            <router-link-->
            <!--                                :to="{-->
            <!--                                    path: getRoutePath('user.user/detail'),-->
            <!--                                    query: {-->
            <!--                                        id: row.id-->
            <!--                                    }-->
            <!--                                }"-->
            <!--                            >-->
            <!--                                详情-->
            <!--                            </router-link>-->
            <!--                        </el-button>-->
            <el-button
              v-perms="['user.user/blacklist']"
              type="primary"
              link
              @click="handledisable(row.id, row.is_blacklist)"
            >
              {{ row.is_blacklist ? "移出黑名单" : "加入黑名单" }}
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="flex justify-end mt-4">
        <pagination v-model="pager" @change="getLists" />
      </div>
    </el-card>
  </div>
</template>
<script lang="ts" setup name="consumerLists">
import { usePaging } from "@/hooks/usePaging";
import { getRoutePath } from "@/router";
import { getUserList, disable } from "@/api/consumer";
import { ClientMap } from "@/enums/appEnums";
import feedback from "@/utils/feedback";
import { getrechargeLists } from "@/api/order/recharge";
const queryParams = reactive({
  keyword: "",
  channel: "",
  create_time_start: "",
  create_time_end: "",
  is_member: "",
  provider: "",
  date_range: [],
});

const size = ref<"default" | "large" | "small">("default");

const value1 = ref("");
const value2 = ref("");

const shortcuts = [
  {
    text: "今天",
    value: () => {
      const start = new Date();
      return [start, start];
    },
  },
  {
    text: "昨天",
    value: () => {
      const start = new Date();
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 1);
      return [start, start];
    },
  },
  {
    text: "本月",
    value: () => {
      const start = new Date();
      start.setDate(1); // 将日期设置为本月的第一天
      const end = new Date(start.getFullYear(), start.getMonth() + 1, 0); // 获取下个月的第0天，即本月的最后一天
      return [start, end];
    },
  },
  {
    text: "7天前",
    value: () => {
      const end = new Date();
      const start = new Date();
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
      return [start, end];
    },
  },
  {
    text: "上个月前",
    value: () => {
      const end = new Date();
      const start = new Date();
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
      return [start, end];
    },
  },
  {
    text: "3个月前",
    value: () => {
      const end = new Date();
      const start = new Date();
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
      return [start, end];
    },
  },
];

const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: getUserList,
  params: queryParams,
});

/**
 * 黑名单
 */
const handledisable = async (id: number, status: number) => {
  await feedback.confirm(`是否${status ? "移出黑名单" : "加入黑名单"}`);
  await disable({ id });
  await getLists();
};

onActivated(() => {
  getLists();
});
getLists();
</script>
