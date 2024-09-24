<template>
    <div class="">
        <el-form ref="formRef" class="ls-form" :model="formData" label-width="160px">
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">LunaAI换脸算法服务</div>
                <el-form-item label="secret" prop="access_key">
                    <div class="w-80">
                        <el-input v-model.trim="formData.secret" placeholder="请输入secret" />
                    </div>
                </el-form-item>
                <el-form-item label="secret_key" prop="secret_key">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.secret_key"
                            placeholder="请输入secret_key"
                        />
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
    </div>
    <footer-btns v-perms="['setting.lunaServiceSetting/setConfig']">
        <el-button type="primary" @click="handleSubmit">保存</el-button>
    </footer-btns>
</template>
<script setup lang="ts">
import { getLunaServiceConfig, setLunaServiceConfig } from '@/api/setting/luna_service'

interface formDataInter {
    secret: string
    secret_key: string
}
const formData = ref<formDataInter>({
    secret: '',
    secret_key: ''
})
/**
 * 初始化数据
 */
const getData = async () => {
    formData.value = await getLunaServiceConfig()
}
getData()
/**
 * 保存数据
 */
const handleSubmit = async () => {
    await setLunaServiceConfig(formData.value)
    getData()
}
</script>
