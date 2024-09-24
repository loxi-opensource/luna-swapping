<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
            @close="handleClose"
        >
            <el-form ref="formRef" :model="formData" label-width="84px" :rules="formRules">
                <el-form-item label="权益图标" prop="image">
                    <MaterialPicker v-model="formData.image" />
                </el-form-item>
                <el-form-item label="权益名称" prop="name">
                    <el-input
                        v-model="formData.name"
                        placeholder="请输入权益名称"
                        clearable
                        show-word-limit
                        :maxlength="100"
                    />
                </el-form-item>
                <el-form-item label="权益描述" prop="describe">
                    <el-input
                        v-model="formData.describe"
                        placeholder="请输入权益描述"
                        clearable
                        show-word-limit
                        :maxlength="100"
                    />
                </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <div>
                        <el-input-number v-model="formData.sort" :min="0" :max="9999" />
                        <div class="form-tips">默认为0， 数值越大越排前</div>
                    </div>
                </el-form-item>
                <el-form-item label="状态" required prop="status">
                    <el-switch v-model="formData.status" :active-value="1" :inactive-value="0" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import { memberBenefitsEdit, memberBenefitsAdd, memberBenefitsDetail } from '@/api/marketing/vip'
import Popup from '@/components/popup/index.vue'
const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑会员权益' : '新增会员权益'
})
const formData = reactive({
    id: '',
    name: '',
    describe: '',
    image: '',
    sort: 0,
    status: 1
})

const formRules = {
    image: [
        {
            required: true,
            message: '请输入权益图标',
            trigger: ['blur']
        }
    ],
    name: [
        {
            required: true,
            message: '请输入权益名称',
            trigger: ['blur']
        }
    ]
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    mode.value == 'edit' ? await memberBenefitsEdit(formData) : await memberBenefitsAdd(formData)
    popupRef.value?.close()
    emit('success')
}

const open = (type = 'add') => {
    mode.value = type
    popupRef.value?.open()
}

const setFormData = (data: Record<any, any>) => {
    for (const key in formData) {
        if (data[key] != null && data[key] != undefined) {
            //@ts-ignore
            formData[key] = data[key]
        }
    }
}

const getDetail = async (row: Record<string, any>) => {
    const data = await memberBenefitsDetail({
        id: row.id
    })
    setFormData(data)
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
