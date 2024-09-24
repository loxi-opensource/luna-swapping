<template>
    <div class="xl:flex">
        <el-card class="!border-none flex-1 xl:mr-4 mb-4" shadow="never">
            <template #header>
                <span class="font-medium">服务协议</span>
                <!-- <el-switch
                    class="ml-2"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formData.service_status"
                ></el-switch>
                <span class="ml-2">关闭时，在用户注册登录页将不显示</span>
                <span class="ml-2 text-[#4073FA]">查看</span> -->
            </template>
            <el-form :model="formData" label-width="80px">
                <el-form-item label="协议名称">
                    <el-input v-model="formData.service_title" />
                </el-form-item>
            </el-form>

            <editor class="mb-10" v-model="formData.service_content" height="500"></editor>
        </el-card>
    </div>
    <div class="xl:flex">
        <el-card class="!border-none flex-1 mb-4" shadow="never">
            <template #header>
                <span class="font-medium">隐私协议</span>
                <!-- <el-switch
                    class="ml-2"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formData.privacy_status"
                ></el-switch>
                <span class="ml-2">关闭时，在用户注册登录页将不显示</span>
                <span class="ml-2 text-[#4073FA]">查看</span> -->
            </template>
            <el-form :model="formData" label-width="80px">
                <el-form-item label="协议名称">
                    <el-input v-model="formData.privacy_title" />
                </el-form-item>
            </el-form>

            <editor class="mb-10" v-model="formData.privacy_content" height="500"></editor>
        </el-card>
    </div>
    <div class="xl:flex">
        <el-card class="!border-none flex-1 xl:mr-4 mb-4" shadow="never">
            <template #header>
                <span class="font-medium">支付协议</span>
                <!-- <el-switch
                    class="ml-2"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formData.pay_status"
                ></el-switch>
                <span class="ml-2">关闭时，在开通会员页将不显示</span>
                <span class="ml-2 text-[#4073FA]">查看</span> -->
            </template>
            <el-form :model="formData" label-width="80px">
                <el-form-item label="协议名称">
                    <el-input v-model="formData.pay_title" />
                </el-form-item>
            </el-form>

            <editor class="mb-10" v-model="formData.pay_content" height="500"></editor>
        </el-card>
    </div>
    <div class="xl:flex">
        <el-card class="!border-none flex-1 xl:mr-4 mb-4" shadow="never">
            <template #header>
                <span class="font-medium">用户分销协议</span>
                <!-- <el-switch
                    class="ml-2"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="formData.pay_status"
                ></el-switch>
                <span class="ml-2">关闭时，在开通会员页将不显示</span>
                <span class="ml-2 text-[#4073FA]">查看</span> -->
            </template>
            <el-form :model="formData" label-width="80px">
                <el-form-item label="协议名称">
                    <el-input v-model="formData.distribution_title" />
                </el-form-item>
            </el-form>

            <editor class="mb-10" v-model="formData.distribution_content" height="500"></editor>
        </el-card>
    </div>
    <footer-btns v-perms="['setting.web.web_setting/setAgreement']">
        <el-button type="primary" @click="handleProtocolEdit">保存</el-button>
    </footer-btns>
</template>

<script setup lang="ts" naem="webProtocol">
import { getProtocol, setProtocol } from '@/api/setting/website'

interface formDataObj {
    pay_content: string
    pay_status: number
    pay_title: string
    service_status: number
    service_title: string
    service_content: string
    privacy_title: string
    privacy_content: string
    privacy_status: number
    distribution_title: string
    distribution_content: string
    distribution_status: number
}
const formData = ref<formDataObj>({
    pay_content: '',
    pay_status: 0,
    pay_title: '',
    service_title: '',
    service_content: '',
    privacy_title: '',
    privacy_content: '',
    privacy_status: 0,
    service_status: 0,
    distribution_title: '',
    distribution_content: '',
    distribution_status: 0
})
const protocolGet = async () => {
    formData.value = await getProtocol()
}

const handleProtocolEdit = async (): Promise<void> => {
    await setProtocol({ ...formData.value })
    protocolGet()
}
protocolGet()
</script>
