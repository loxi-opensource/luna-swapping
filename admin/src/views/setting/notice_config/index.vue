<!-- 公告设置 -->
<template>
    <div class="notice-config">
        <el-form ref="formRef" :rules="rules" :model="formData" label-width="120px">
            <el-card shadow="never" class="!border-none">
                <div class="font-medium mb-7">公告设置</div>
                <el-form-item label="公告弹窗" prop="is_bulletin">
                    <div>
                        <el-switch
                            v-model="formData.is_bulletin"
                            :active-value="1"
                            :inactive-value="0"
                        />
                        <span class="mt-1 ml-2">
                            {{ formData.is_bulletin ? '开启' : '关闭' }}
                        </span>
                        <div class="form-tips">用户每天首次进入站点会触发弹窗</div>
                    </div>
                </el-form-item>
                <el-form-item label="公告标题" prop="bulletin_title">
                    <div>
                        <el-input
                            class="w-[375px]"
                            v-model="formData.bulletin_title"
                            placeholder="请输入公告标题"
                            clearable
                        />
                    </div>
                </el-form-item>
                <el-form-item label="公告设置" prop="bulletin_content">
                    <editor v-model="formData.bulletin_content" :height="667" :width="375" />
                </el-form-item>
            </el-card>
        </el-form>

        <footer-btns v-perms="['setting.web.web_setting/setBulletinConfig']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>

<script lang="ts" setup name="noticeConfig">
import type { NoticeConfigType } from '@/api/setting/notice_config'
import { getConfig, setConfig } from '@/api/setting/notice_config'
import type { FormInstance, FormRules } from 'element-plus'
const formRef = ref<FormInstance>()

// 表单数据
const formData = reactive<NoticeConfigType>({
    is_bulletin: 1,
    bulletin_content: '',
    bulletin_title: ''
})

// 表单验证
const rules = reactive<FormRules>({
    is_bulletin: [{ required: true, trigger: 'blur' }],
    bulletin_title: [{ required: true, message: '请输入公告标题' }]
})

// 获取公告设置数据
const getData = async () => {
    try {
        const data = await getConfig()
        for (const key in formData) {
            //@ts-ignore
            formData[key] = data[key]
        }
        console.log(formData)
    } catch (error) {
        console.log('获取=>', error)
    }
}

// 保存公告设置数据
const handleSubmit = async () => {
    await formRef.value?.validate()
    try {
        await setConfig(formData)
        await getData()
    } catch (error) {
        console.log('保存=>', error)
    }
}

getData()
</script>

<style lang="scss" scoped></style>
