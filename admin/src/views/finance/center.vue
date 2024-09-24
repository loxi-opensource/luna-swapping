<template>
    <el-card shadow="never" class="!border-none">
        <template #header>
            <span class="font-medium">经营状况</span>
        </template>
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.business_overview?.total_amount }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计收入金额
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="所有充值订单和会员订单实付金额总和（包含己退款金额）
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.business_overview?.total_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计订单数（笔）
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="所有充值订单和会员订单成交订单总数（包含已退款订单)
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.business_overview?.total_refund_amount }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计退款金额
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="所有充值订单和会员订单己退款的金额总和

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.business_overview?.total_refund_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计退款订单
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="所有充值订单和会员订单已退款的订单总数

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.business_overview?.net_income }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计净收入
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="除掉退款金额，实际获得的收入金额
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
        </div>
    </el-card>
    <el-card shadow="never" class="!border-none mt-2">
        <template #header>
            <span class="font-medium">订单概况</span>
        </template>
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_recharge_amount }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计充值金额(元)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户累计充值实付金额总和

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_recharge_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    充值订单数(笔）
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户累计充值订单总数


"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_recharge_refund_amount }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计充值退款金额(元)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="充值订单累计己退款的金额总和
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_recharge_refund_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    充值退款订单(笔)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="充值订单累计己退款的订单数量
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.recharge_net_income }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    充值净收入 (元)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="充值订单除掉已退款金额，实际获得的收入
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_member_amount }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计开通会员金额(元)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户累计开通会员实付金额总和

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_member_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    会员订单数(笔）
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户累计开通会员订单总数

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_member_refund_amount }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计会员退款金额(元)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="会员订单累计己退款的金额总和
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.total_member_refund_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    会员退款订单(笔)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="会员订单累计己退款的订单数量

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.order_overview?.member_net_income }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    会员净收入 (元)
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="充值订单除掉已退款金额，实际获得的收入

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
        </div>
    </el-card>
    <el-card shadow="never" class="!border-none mt-2">
        <template #header>
            <span class="font-medium">用户概况</span>
        </template>
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">{{ centerData.user_overview?.user_num }}</div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    用户总人数
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="平台用户总数

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.user_overview?.member_user_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    开通会员人数
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="已开通过会员的人数总和（包含已到期的，按照用户ID来计算，即一个用户ID开通多次也只算一个人）"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.user_overview?.user_total_amount }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    用户累计消费金额
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户累计消费的金额总和（含充值和开通会员）

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.user_overview?.total_recharge_num }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    累计充值人数
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="充值的人数总和（包含已退款的，按照用户ID来计算，即一个用户ID充值多次也只算一个人）
"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.user_overview?.user_balance }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    用户剩余对话余额
                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户当前账号剩余的对话余额条数总和

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.user_overview?.user_balance_draw }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    用户剩余绘画余额

                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户当前账号剩余的绘画余额条数总和

"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.user_overview?.user_total_quiz }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    用户累计提问次数

                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户累计提问对话的次数总和


"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
            <div class="flex flex-col items-start justify-center">
                <div class="font-medium text-[24px]">
                    {{ centerData.user_overview?.user_total_draw }}
                </div>
                <div class="text-[14px] text-[#666666] flex items-center">
                    用户累计绘画次数

                    <el-tooltip
                        class="box-item"
                        effect="dark"
                        content="用户累计提问绘画的次数总和


"
                        placement="bottom"
                    >
                        <img
                            src="@/assets/images/question.png"
                            alt=""
                            class="w-[16px] h-[16px] ml-[5px]"
                        />
                    </el-tooltip>
                </div>
            </div>
        </div>
    </el-card>
</template>
<script setup lang="ts">
import { center } from '@/api/finance'
const centerData: any = ref([])

const getData = async () => {
    centerData.value = await center()
}

onMounted(() => {
    getData()
})
</script>

<style scoped lang="scss"></style>
