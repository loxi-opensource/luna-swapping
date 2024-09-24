<template>
  <el-card shadow="never" class="!border-none">
    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
      <div class="flex flex-col items-center justify-center">
        <div class="font-medium text-[24px]">{{ pager.extend.order_num }}</div>
        <div>订阅订单数</div>
      </div>
      <div class="flex flex-col items-center justify-center">
        <div class="font-medium text-[24px]">
          {{ pager.extend.total_amount }}
        </div>
        <div>累计金额</div>
      </div>
      <div class="flex flex-col items-center justify-center">
        <div class="font-medium text-[24px]">
          {{ pager.extend.refund_order_num }}
        </div>
        <div>退款订单</div>
      </div>
      <div class="flex flex-col items-center justify-center">
        <div class="font-medium text-[24px]">
          {{ pager.extend.refund_total_amount }}
        </div>
        <div>累计退款金额</div>
      </div>
      <div class="flex flex-col items-center justify-center">
        <div class="font-medium text-[24px]">{{ pager.extend.net_income }}</div>
        <div>净收入</div>
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
          placeholder="请输入用户ID编号/用户昵称"
          clearable
        />
      </el-form-item>
      <el-form-item label="下单时间" prop="date_range">
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
        />
      </el-form-item>
      <el-form-item label="免费试用">
        <el-select class="!w-[100px]" v-model="queryParams.is_free">
          <el-option label="全部" value />
          <el-option label="是" value="1"></el-option>
          <el-option label="否" value="0"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="支付状态">
        <el-select class="!w-[120px]" v-model="queryParams.pay_status">
          <el-option label="全部" value />
          <el-option label="已支付" value="1"></el-option>
          <el-option label="未支付" value="0"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="resetPage">查询</el-button>
        <el-button @click="resetParams">重置</el-button>
        <export-data
          class="ml-2.5"
          :fetch-fun="getMemberLists"
          :params="queryParams"
          :page-size="pager.size"
        />
      </el-form-item>
    </el-form>
  </el-card>
  <el-card class="!border-none mt-4" shadow="never">
    <el-table size="small" v-loading="pager.loading" :data="pager.lists">
      <el-table-column label="订单编号" prop="sn" min-width="180" />
      <el-table-column label="用户信息" min-width="300">
        <template #default="{ row }">
          <el-popover placement="top" width="220px" trigger="hover">
            <div class="flex items-center">
              <span class="mr-4">头像: </span>
              <el-avatar :size="50" :src="row?.avatar" />
            </div>
            <div class="mt-[20px]">
              <span class="mr-4"> 昵称: </span>
              <span>{{ row.nickname }}</span>
            </div>
            <div class="mt-[20px]">
              <span class="mr-4">编号: </span>
              <span>{{ row.user_sn }}</span>
            </div>
            <template #reference>
              <div class="flex items-center">
                <el-avatar :size="50" :src="row?.avatar">
                  {{ row.nickname }}
                </el-avatar>
                <div class="ml-[10px]">
                  {{ row.nickname }}
                </div>
              </div>
            </template>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column
        label="支付方式"
        prop="pay_type_desc"
        min-width="100"
        show-overflow-tooltip
      />
      <el-table-column label="购买套餐" prop="member_package" min-width="200" />
      <el-table-column label="实付金额" prop="order_amount" min-width="100" />
      <el-table-column label="支付状态" min-width="120">
        <template #default="{ row }">
          <div>
            <el-tag
              :type="row.pay_status_text == '已支付' ? 'success' : 'warning'"
            >
              {{ row.pay_status_text }}
            </el-tag>
          </div>
          <div v-if="row.refund_status != 0" class="text-warning">
            {{ row.refund_status_text }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="支付时间" prop="pay_time_text" min-width="180" />
      <el-table-column label="创建时间" prop="create_time" min-width="180" />
      <el-table-column
        label="上次登录IP"
        prop="login_ip"
        min-width="140"
        show-overflow-tooltip
      />
      <el-table-column label="用户ID" prop="user_id" min-width="100" />
      <el-table-column label="免费试用" prop="is_free_text" min-width="100" />
      <el-table-column label="续费订单" prop="is_renew_order" min-width="100" />
      <el-table-column
        label="IP信息"
        prop="login_ipinfo"
        width="200"
        show-overflow-tooltip
      />
      <el-table-column label="操作" width="100" fixed="right">
        <template #default="{ row }">
          <div class="flex">
            <el-button
              v-perms="['member.member_order/detail']"
              type="primary"
              link
              @click="handleDetial(row.id)"
            >
              订单详情
            </el-button>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <div class="flex justify-end mt-4">
      <pagination v-model="pager" @change="getLists" />
    </div>
  </el-card>
  <Detial v-if="showEdit" ref="editRef"></Detial>
</template>

<script setup lang="ts">
import Detial from "./detial.vue";
import feedback from "@/utils/feedback";
import { getMemberLists, MemberOrderRefund } from "@/api/order/vip";
import { usePaging } from "@/hooks/usePaging";
import { getrechargeLists } from "@/api/order/recharge";

/**
 * 处理弹出框
 */
const editRef = shallowRef<InstanceType<typeof Detial>>();
const showEdit = ref(false);
const handleDetial = async (id: any) => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open(id);
};

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

/**
 * 退款处理
 */
const handleRefund = async (id: any) => {
  await feedback.confirm("是否确认退款");
  MemberOrderRefund({ id });
};
/**
 * 初始化数据
 */
const queryParams = reactive({
  user_info: "", //用户信息
  terminal: "", //订单来源
  refund_status: "", //退款状态
  is_free: "",
  pay_status: "1",
  date_range: [],
});

const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: getMemberLists,
  params: queryParams,
});

getLists();
</script>
