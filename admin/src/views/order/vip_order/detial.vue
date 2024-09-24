<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="'订单详情'"
            :async="true"
            width="550px"
            :cancelButtonText="'关闭'"
            :confirmButtonText="'申请退款'"
            @confirm="refund"
        >
            <el-form label-width="84px">
                <el-form-item label="订单编号"> {{ formData.sn }} </el-form-item>
                <el-form-item label="订单来源">{{ formData.terminal_text }}</el-form-item>
                <el-form-item label="用户信息">{{ formData.user.nickname }}</el-form-item>
                <el-form-item label="订单类型">{{ formData.order_type_text }}</el-form-item>
                <el-form-item label="购买套餐"> {{ formData.member_package }} </el-form-item>
                <el-form-item label="实付金额"> ￥{{ formData.order_amount }}</el-form-item>
                <el-form-item label="支付状态"> {{ formData.pay_status_text }} </el-form-item>
                <el-form-item label="支付方式"> {{ formData.pay_way_text }} </el-form-item>
                <el-form-item label="提交时间"> {{ formData.create_time }} </el-form-item>
                <el-form-item label="支付时间"> {{ formData.pay_time_text }}</el-form-item>
                <el-form-item label="退款状态"> {{ formData.refund_status_text }} </el-form-item>
                <el-form-item label="退款流水号">{{ formData.refund_transaction_id }}</el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import Popup from '@/components/popup/index.vue'
import { getMemberDetail } from '@/api/order/vip'
import { MemberOrderRefund } from '@/api/order/vip'

//弹框ref
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const id = ref('')

//表单数据
const formData: any = ref({
    sn: '', //订单编号
    terminal_text: '', //订单来源
    user: {}, //用户信息
    order_type_text: '', //订单类型
    member_package: '', //充值条数
    order_amount: '', //实付金额
    pay_status_text: '', //支付状态
    pay_way_text: '', //支付方式
    create_time: '', //提交时间
    pay_time_text: '', //支付时间
    refund_status_text: '', //退款状态
    refund_transaction_id: '' //退款流水号
})

const refund = async () => {
    await MemberOrderRefund({ id: id.value })
    popupRef.value?.close()
}

//打开弹框
const open = async (value: any) => {
    id.value = value
    await popupRef.value?.open()
    await getData(value)
}

const getData = async (id: any) => {
    formData.value = await getMemberDetail({ id })
}

defineExpose({
    open
})
</script>
