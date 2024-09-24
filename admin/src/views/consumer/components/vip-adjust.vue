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
            <el-form ref="formRef" :model="formData" label-width="84px">
                <el-form-item label="到期时间">
                    <div>
                        <div class="flex items-center">
                            <el-date-picker
                                v-model="formData.member_end_time"
                                type="date"
                                placeholder="请选择"
                                value-format="YYYY-MM-DD HH:mm:ss"
                                :disabled="!!formData.member_perpetual"
                            />
                            <el-checkbox
                                class="ml-4"
                                v-model="formData.member_perpetual"
                                :true-label="1"
                                :false-label="0"
                                >永久</el-checkbox
                            >
                        </div>
                        <div class="form-tips">留空表示关闭会员</div>
                    </div>
                </el-form-item>
                <el-form-item
                    label="会员套餐"
                    v-if="formData.member_end_time || formData.member_perpetual == 1"
                >
                    <el-select v-model="formData.member_package_id">
                        <el-option label="请选择" value></el-option>
                        <el-option
                            v-for="(item, index) in menberList"
                            :key="index"
                            :label="item.name"
                            :value="item.id"
                        ></el-option>
                    </el-select>
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { adjustMember, getMemberList } from '@/api/consumer'
const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return (mode.value = '调整会员时间')
})
const formData: any = ref({
    id: '',
    member_end_time: '',
    member_perpetual: '',
    member_package_id: ''
})

const menberList: any = ref([])

const handleSubmit = async () => {
    if (formData.member_end_time == null) {
        formData.member_end_time = ''
    }
    await adjustMember(formData.value)
    popupRef.value?.close()
    emit('success')
}

const open = (type = 'add') => {
    mode.value = type
    popupRef.value?.open()
    getMemberdrowDownList()
}

const setFormData = async (data: any, id: any) => {
    console.log(data)
    setTimeout(() => {
        if (!data.member_perpetual) {
            formData.value.member_end_time = data.member_end_time_desc
        }
        formData.value.member_perpetual = data.member_perpetual
        formData.value.member_package_id = data.member_package_id || ''
        formData.value.id = id
    }, 500)
}

//获取会员下拉
const getMemberdrowDownList = async () => {
    menberList.value = await getMemberList()
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    setFormData
})
</script>
