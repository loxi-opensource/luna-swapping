<template>
    <div class="">
        <el-form ref="formRef" class="ls-form" :model="formData" label-width="160px">
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">图片内容安全审核</div>
                <el-form-item label="审核开关" prop="audit_open">
                    <div>
                        <el-switch
                            v-model="formData.audit_open"
                            :active-value="1"
                            :inactive-value="0"
                        />
                        <span class="mt-1 ml-2">{{ formData.audit_open ? '开启' : '关闭' }}</span>
                        <div class="form-tips">
                            1、用户上传图片是否需要检测涉黄涉政治等不合规内容，默认关闭<br />
                            2、需要开通阿里云图片审核增强版API服务。文档地址：<a
                                href="https://help.aliyun.com/document_detail/467829.html"
                                target="_blank"
                                >https://help.aliyun.com/document_detail/467829.html</a
                            >
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="ACCESS_KEY" prop="access_key" v-if="formData.audit_open">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.access_key"
                            placeholder="请输入ACCESS_KEY(AK)"
                        />
                    </div>
                </el-form-item>
                <el-form-item label="SECRET_KEY" prop="secret_key" v-if="formData.audit_open">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.secret_key"
                            placeholder="请输入SECRET_KEY(SK)"
                        />
                    </div>
                </el-form-item>
                <el-form-item label="接入地址" prop="endpoint" v-if="formData.audit_open">
                    <div class="w-80">
                        <el-input v-model="formData.endpoint" placeholder="请输入endpoint" />
                        <div class="form-tips">例如：green-cip.cn-shanghai.aliyuncs.com</div>
                    </div>
                </el-form-item>
                <el-form-item label="接入地域" prop="region_id" v-if="formData.audit_open">
                    <div class="w-80">
                        <el-input v-model="formData.region_id" placeholder="请输入region_id" />
                        <div class="form-tips">例如：cn-shanghai</div>
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
    </div>
    <footer-btns v-perms="['setting.imageCheckSetting/setConfig']">
        <el-button type="primary" @click="handleSubmit">保存</el-button>
    </footer-btns>
</template>
<script setup lang="ts">
import { getImageCheckConfig, setImageCheckConfig } from '@/api/setting/image_check'

interface formDataInter {
    access_key: string
    secret_key: string
    endpoint: string
    region_id: string
    audit_open: number
}
const formData = ref<formDataInter>({
    access_key: '',
    secret_key: '',
    endpoint: '',
    region_id: '',
    audit_open: 0
})
/**
 * 初始化数据
 */
const getData = async () => {
    formData.value = await getImageCheckConfig()
}
getData()
/**
 * 保存数据
 */
const handleSubmit = async () => {
    await setImageCheckConfig(formData.value)
    getData()
}
</script>
