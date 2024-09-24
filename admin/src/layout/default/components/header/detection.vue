<template>
    <div class="flex items-center h-full">
        <div class="mr-[35px] bg-[#67C23A] p-[5px] text-white rounded-[4px] cursor-pointer" @click="handleclick">
            正版检测
        </div>
    </div>
    <popup ref="popupRef" :title="'正版检测'" :async="true" width="550px" cancelButtonText="" confirmButtonText="">
        <div v-loading="true"></div>
        <div class="flex justify-center flex-col items-center">
            <div>
                <el-image style="width: 60px; height: 60px" :src="check" />
            </div>
            <div class="mt-[20px]">
                恭喜您，系统检测到您的域名<span class="text-primary">已授权</span>，可前往likeshop.cn官网享受所有正版权益
            </div>
        </div>
        <div class="flex justify-center flex-col items-center">
            <div>
                <el-image style="width: 60px; height: 60px" :src="error" />
            </div>
            <div class="mt-[20px]">
                很遗憾，系统检测到您的域名<span class="text-primary">未授权</span>，请前往likeshop.cn官网【个人中心】-【产品授权】登记域名授权，否则将视为盗版行为!
            </div>
        </div>
    </popup>
</template>
<script setup lang="ts">
import Popup from '@/components/popup/index.vue'
import check from '@/assets/images/check.png'
import error from '@/assets/images/error.png'
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const handleclick = async () => {
    await popupRef.value?.open()
}
</script>
