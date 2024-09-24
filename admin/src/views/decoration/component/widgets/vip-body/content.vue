<template>
    <div class="container">
        <img src="../../../image/bgc-2.png" alt="" class="img" />
        <!-- 会员套餐 -->
        <div class="p-[10px] flex">
            <div class="container-combo w-1/3 rounded-lg">
                <div
                    class="bg-[#FF4747] text-white text-[12px] px-2 inline-block rounded-tl-lg rounded-br-lg"
                >
                    有效期30天
                </div>
                <div class="text-center mt-2">月度会员</div>
                <div class="text-center text-[#FF4747] mt-2">
                    ￥<text class="text-[24px]">68</text>
                </div>
                <div class="text-center line-through text-[#999999] mt-2">￥98</div>
            </div>
            <div class="bg-white w-1/3 rounded-lg mx-2">
                <div
                    class="bg-[#FF4747] text-white text-[12px] px-2 inline-block rounded-tl-lg rounded-br-lg"
                >
                    有效期90天
                </div>
                <div class="text-center mt-2">季度会员</div>
                <div class="text-center text-[#FF4747] mt-2">
                    ￥<text class="text-[24px]">98</text>
                </div>
                <div class="text-center line-through text-[#999999] mt-2">￥199</div>
            </div>
            <div class="bg-white w-1/3 rounded-lg">
                <div
                    class="bg-[#FF4747] text-white text-[12px] px-2 inline-block rounded-tl-lg rounded-br-lg"
                >
                    有效期365天
                </div>
                <div class="text-center mt-2">年度会员</div>
                <div class="text-center text-[#FF4747] mt-2">
                    ￥<text class="text-[24px]">168</text>
                </div>
                <div class="text-center line-through text-[#999999] mt-2">￥299</div>
            </div>
        </div>
        <!-- 优惠金额 -->
        <div class="bg-white rounded-lg mx-[10px] p-3">优惠金额</div>
        <!-- 支付方式 -->
        <div class="bg-white rounded-lg mx-[10px] px-3 mt-2 py-2">
            <div class="text-[12px] text-[#999999]">支付方式</div>
            <div class="flex mt-2 justify-between">
                <div class="flex">
                    <img src="../../../image/icon_wx.png" alt="" class="w-[20px] h-[20px]" />
                    <div class="ml-2">微信支付</div>
                </div>
                <img src="../../../image/selected.png" alt="" class="w-[20px] h-[20px]" />
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup></script>

<style lang="scss" scoped>
.container {
    &-combo {
        background: linear-gradient(179.19deg, #ffe8cf 0%, #ffe8cf 23.65%, #f2ceac 100%);
    }
}
</style>
