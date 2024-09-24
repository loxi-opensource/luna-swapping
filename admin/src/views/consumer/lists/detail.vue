<template>
  <div>
    <el-card class="!border-none" shadow="never">
      <el-page-header content="用户详情" @back="$router.back()" />
    </el-card>
    <el-card class="mt-4 !border-none" header="基本资料" shadow="never">
      <el-form
        ref="formRef"
        class="ls-form"
        :model="formData"
        label-width="120px"
      >
        <div class="flex items-center py-5 mb-10 bg-page">
          <div class="flex flex-col items-center justify-center basis-40">
            <div class="mb-2 text-tx-regular">用户头像</div>
            <el-avatar :src="formData.avatar" :size="58" />
          </div>
          <div class="flex flex-col items-center justify-center basis-40">
            <div class="text-tx-regular">对话余额</div>
            <div class="flex items-center mt-2">
              {{ formData.balance }}
              <el-button
                v-perms="['user.user/adjustMoney']"
                type="primary"
                link
                @click="showAdjustMoney = true"
              >
                调整
              </el-button>
            </div>
          </div>
          <div class="flex flex-col items-center justify-center basis-40">
            <div class="text-tx-regular">绘画余额</div>
            <div class="flex items-center mt-2">
              {{ formData.balance_draw }}
              <el-button
                v-perms="['user.user/adjustUserDraw']"
                type="primary"
                link
                @click="showAdjustDraw = true"
              >
                调整
              </el-button>
            </div>
          </div>
          <div class="flex flex-col items-center justify-center basis-40">
            <div class="text-tx-regular">累计消费</div>
            <div class="flex items-center mt-2">
              {{ formData.total_amount }}
            </div>
          </div>
          <div class="flex flex-col items-center justify-center basis-40">
            <div class="text-tx-regular">累计提问</div>
            <div class="flex items-center mt-2">
              {{ formData.total_quiz }}次
            </div>
          </div>
          <div class="flex flex-col items-center justify-center basis-40">
            <div class="text-tx-regular">累计绘画</div>
            <div class="flex items-center mt-2">
              {{ formData.total_draw }}次
            </div>
          </div>
        </div>
        <el-form-item label="用户ID："> {{ formData.sn }} </el-form-item>
        <el-form-item label="用户昵称：">
          {{ formData.nickname }}
        </el-form-item>
        <!-- <el-form-item label="账号：">
                    {{ formData.account }}
                    <popover-input
                        class="ml-[10px]"
                        @confirm="handleEdit($event, 'account')"
                        :limit="32"
                        v-perms="['user.user/edit']"
                    >
                        <el-button type="primary" link>
                            <icon name="el-icon-EditPen" />
                        </el-button>
                    </popover-input>
                </el-form-item> -->
        <el-form-item label="会员：">
          {{
            `${formData.member_desc}(${formData.member_end_time_desc || "无"})`
          }}
          <el-button type="primary" link @click="handleadjust">
            <icon name="el-icon-EditPen" />
          </el-button>
        </el-form-item>
        <el-form-item label="真实姓名：">
          {{ formData.real_name || "-" }}
          <popover-input
            class="ml-[10px]"
            @confirm="handleEdit($event, 'real_name')"
            :limit="32"
            v-perms="['user.user/edit']"
          >
            <el-button type="primary" link>
              <icon name="el-icon-EditPen" />
            </el-button>
          </popover-input>
        </el-form-item>
        <el-form-item label="性别：">
          {{ formData.sex }}
          <popover-input
            class="ml-[10px]"
            type="select"
            :options="[
              {
                label: '未知',
                value: 0,
              },
              {
                label: '男',
                value: 1,
              },
              {
                label: '女',
                value: 2,
              },
            ]"
            @confirm="handleEdit($event, 'sex')"
            v-perms="['user.user/edit']"
          >
            <el-button type="primary" link>
              <icon name="el-icon-EditPen" />
            </el-button>
          </popover-input>
        </el-form-item>
        <el-form-item label="联系电话：">
          {{ formData.mobile || "-" }}
          <popover-input
            class="ml-[10px]"
            type="number"
            @confirm="handleEdit($event, 'mobile')"
            v-perms="['user.user/edit']"
          >
            <el-button type="primary" link>
              <icon name="el-icon-EditPen" />
            </el-button>
          </popover-input>
        </el-form-item>
        <el-form-item label="上级邀请人"
          >{{ formData.inviter_name || "-" }}
        </el-form-item>
        <el-form-item label="邀请的用户"
          >{{ formData.invite_num ?? "-" }}
          <router-link
            :to="{
              path: getRoutePath('distribution.distributor/belowLists'),
              query: {
                id: formData.id,
              },
            }"
            ><el-button link type="primary"> 查看邀请人数</el-button>
          </router-link>
        </el-form-item>
        <el-form-item label="分销资格"
          ><span class="text-[#F2A626]">{{
            formData.is_distribution_desc
          }}</span>
          <router-link
            :to="{
              path: getRoutePath('distribution.distributor/detail'),
              query: {
                id: formData.id,
              },
            }"
          >
            <el-button link type="primary" v-if="formData.is_distribution == 1">
              查看分销信息</el-button
            >
          </router-link>
        </el-form-item>

        <el-form-item label="注册来源：">
          {{ formData.channel_desc }}
        </el-form-item>
        <el-form-item label="注册时间：">
          {{ formData.create_time }}
        </el-form-item>
        <el-form-item label="最近登录时间：">
          {{ formData.login_time }}
        </el-form-item>
        <el-form-item v-perms="['user.user/blacklist']">
          <el-button
            v-if="formData.is_blacklist == 0"
            @click="handledisable(0)"
          >
            加入黑名单
          </el-button>
          <el-button
            v-if="formData.is_blacklist == 1"
            @click="handledisable(1)"
          >
            移除黑名单
          </el-button>
          <el-button @click="openVipRecord"> 会员开通记录 </el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <account-adjust
      title="对话余额调整"
      v-model:show="showAdjustMoney"
      :value="formData.balance"
      @confirm="handleConfirmAdjust"
    />
    <account-adjust
      title="绘画余额调整"
      v-model:show="showAdjustDraw"
      :value="formData.balance_draw"
      @confirm="handleConfirmAdjustDraw"
    />
    <VipAdjust
      v-if="showEdit"
      ref="editRef"
      @success="getDetails"
      @close="showEdit = false"
    ></VipAdjust>
    <recordPop ref="recordPopRef"></recordPop>
  </div>
</template>

<script lang="ts" setup name="consumerDetail">
import type { FormInstance } from "element-plus";
import {
  adjustMoney,
  getUserDetail,
  userEdit,
  disable,
  adjustUserDraw,
} from "@/api/consumer";
import { isEmpty } from "@/utils/util";
import AccountAdjust from "../components/account-adjust.vue";
import VipAdjust from "../components/vip-adjust.vue";
import feedback from "@/utils/feedback";
import { getRoutePath } from "@/router";
import recordPop from "../components/recordPop.vue";

const route = useRoute();
const formData = reactive({
  id: "",
  avatar: "",
  channel: "",
  create_time: "",
  login_time: "",
  mobile: "",
  nickname: "",
  real_name: 0,
  sex: 0,
  sn: "",
  account: "",
  balance: "",
  total_amount: "",
  total_quiz: "",
  member_desc: "",
  member_end_time: "",
  member_perpetual: "",
  is_distribution: 1,
  is_distribution_desc: "",
  distribution_status: "",
  distribution_time: "",
  is_end: "",
  inviter_id: "",
  inviter_name: "",
  invite_num: "",
  first_leader: "",
  second_leader: "",
  user_money: "",
  total_user_money: "",
  is_blacklist: 0,
  channel_desc: "",
  member_end_time_desc: "",
  member_package_id: "",
  balance_draw: "",
  total_draw: "",
});

const showAdjustMoney = ref(false);
const showAdjustDraw = ref(false);
const formRef = shallowRef<FormInstance>();
//会员记录弹框ref
const recordPopRef = shallowRef();

const getDetails = async () => {
  const data = await getUserDetail({
    id: route.query.id,
  });
  Object.keys(formData).forEach((key) => {
    //@ts-ignore
    formData[key] = data[key];
  });
};

const handleEdit = async (value: string, field: string) => {
  if (isEmpty(value)) return;
  await userEdit({
    id: route.query.id,
    field,
    value,
  });
  getDetails();
};

const handleConfirmAdjust = async (value: any) => {
  await adjustMoney({ user_id: route.query.id, ...value });
  showAdjustMoney.value = false;
  getDetails();
};
const handleConfirmAdjustDraw = async (value: any) => {
  await adjustUserDraw({ user_id: route.query.id, ...value });
  showAdjustDraw.value = false;
  getDetails();
};

getDetails();
/**
 * 调整会员时间
 */
const editRef = shallowRef<InstanceType<typeof VipAdjust>>();
const showEdit = ref(false);
const handleadjust = async () => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open("add");
  editRef.value?.setFormData(formData, route.query.id);
};
const handlebalck = async () => {
  await disable({ id: route.query.id });
  getDetails();
};

/**
 * 黑名单
 */
const handledisable = async (status: number) => {
  await feedback.confirm(`是否${status ? "移出黑名单" : "加入黑名单"}`);
  await disable({ id: route.query.id });
  await getDetails();
};

//打开开通会员弹框
const openVipRecord = () => {
  recordPopRef.value.open(route.query.id);
};
</script>
