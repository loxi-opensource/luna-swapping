<template>
    <div class="">
        <el-form ref="formRef" class="ls-form" :model="formData" label-width="120px">
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">分享设置</div>
                <el-form-item label="分享页面" prop="name">
                    <div>
                        <el-radio-group v-model="formData.share_page" class="ml-4">
                            <el-radio :label="1">当前页面</el-radio>
                            <el-radio :label="2">首页</el-radio>
                        </el-radio-group>
                        <div class="form-tips">
                            用户使用微信分享把商城页面发送给微信好友时，分享当前页面对应的链接。
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="分享标题" prop="name">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.share_title"
                            placeholder="分享标题，不填则为当前页面标题"
                        />
                    </div>
                </el-form-item>
                <el-form-item label="分享简介" prop="name">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.share_content"
                            placeholder="分享简介，不填则为空"
                            type="textarea"
                            :row="5"
                        />
                    </div>
                </el-form-item>
                <el-form-item label="分享封面" prop="name">
                    <div>
                        <material-picker v-model="formData.share_image" :limit="1" />
                        <div class="form-tips">建议尺寸：宽200px*高200px。jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
    </div>
    <footer-btns v-perms="['setting.shareSetting/setConfig']">
        <el-button type="primary" @click="handleSubmit">保存</el-button>
    </footer-btns>
</template>
<script setup lang="ts">
import { getshareConfig, setshareConfig } from '@/api/setting/share'

interface formDataInter {
    share_page: number
    share_title: string
    share_content: string
    share_image: string
}
const formData = ref<formDataInter>({
    share_page: 1,
    share_title: '',
    share_content: '',
    share_image: ''
})
/**
 * 初始化数据
 */
const getData = async () => {
    formData.value = await getshareConfig()
}
getData()
/**
 * 保存数据
 */
const handleSubmit = async () => {
    await setshareConfig(formData.value)
    getData()
}
</script>
