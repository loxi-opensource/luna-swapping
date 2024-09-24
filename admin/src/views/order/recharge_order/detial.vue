<template>
  <div class="edit-popup">
    <popup
      ref="popupRef"
      :title="'订单详情'"
      :async="true"
      width="550px"
      :cancelButtonText="'关闭'"
      @confirm="clickConfirm"
    >
      <el-form label-width="84px">
        <el-form-item label="订单编号"> {{ formData.sn }} </el-form-item>
        <el-form-item label="订单来源">{{
          formData.terminal_text
        }}</el-form-item>
        <el-form-item label="用户信息">{{
          formData.user.nickname
        }}</el-form-item>
        <el-form-item label="订单类型">{{
          formData.order_type_text
        }}</el-form-item>
        <el-form-item label="充值套餐">
          {{ formData.recharge_package_name }}
        </el-form-item>
        <el-form-item label="套餐内容">
          <div>
            <!--                        <div>对话次数：{{ formData.number }}</div>-->
            <div>绘画次数：{{ formData.draw_number }}</div>
          </div>
        </el-form-item>
        <el-form-item label="实付金额">
          ￥{{ formData.order_amount }}</el-form-item
        >
        <el-form-item label="支付状态">
          {{ formData.pay_status_text }}
        </el-form-item>
        <el-form-item label="支付方式">
          {{ formData.pay_way_text }}
        </el-form-item>
        <el-form-item label="提交时间">
          {{ formData.create_time }}
        </el-form-item>
        <el-form-item label="支付时间">
          {{ formData.pay_time_text }}</el-form-item
        >
        <!--                <el-form-item label="退款状态"> {{ formData.refund_status_text }} </el-form-item>-->
        <!--                <el-form-item label="退款流水号">{{ formData.refund_transaction_id }}</el-form-item>-->
      </el-form>
    </popup>
  </div>
</template>
<script lang="ts" setup>
import Popup from "@/components/popup/index.vue";
import feedback from "@/utils/feedback";
import { getrechargeDetail } from "@/api/order/recharge";
import { RechargeOrderRefund } from "@/api/order/recharge";

//弹框ref
const popupRef = shallowRef<InstanceType<typeof Popup>>();
const id = ref("");
const emits = defineEmits(["refresh"]);

//表单数据
const formData: any = ref({
  sn: "", //订单编号
  terminal_text: "", //订单来源
  user: {}, //用户信息
  order_type_text: "", //订单类型
  number: "", //充值条数
  order_amount: "", //实付金额
  pay_status_text: "", //支付状态
  pay_way_text: "", //支付方式
  create_time: "", //提交时间
  pay_time_text: "", //支付时间
  refund_status_text: "", //退款状态
  refund_transaction_id: "", //退款流水号
});

//打开弹框
const open = async (value: any) => {
  id.value = value;
  await popupRef.value?.open();
  await getData(value);
};

//点击确定按钮
const clickConfirm = async () => {
  popupRef.value?.close();
  return;
  // await feedback.confirm('是否确认退款')
  // await RechargeOrderRefund({ id: id.value })
  // await emits('refresh')
  // popupRef.value?.close()
};

const getData = async (id: any) => {
  formData.value = await getrechargeDetail({ id });
};

defineExpose({
  open,
});
</script>
