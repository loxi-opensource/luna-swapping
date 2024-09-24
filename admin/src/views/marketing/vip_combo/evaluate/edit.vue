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
                <!-- 头像 -->
                <el-form-item label="头像" prop="image">
                    <div class="">
                        <material-picker v-model="formData.image" :limit="1" />
                    </div>
                </el-form-item>
                <!-- 用户昵称 -->
                <el-form-item label="用户昵称" prop="name">
                    <div class="flex">
                        <el-input
                            v-model="formData.name"
                            placeholder="请输入用户昵称"
                            clearable
                            class="w-[360px]"
                        >
                        </el-input>
                    </div>
                </el-form-item>
                <!-- 评价套餐 -->
                <el-form-item label="评价套餐" prop="member_package_id">
                    <div class="flex">
                        <el-select class="!w-[360px]" v-model="formData.member_package_id">
                            <el-option
                                v-for="item in optionsData.menber.lists"
                                :key="item.id"
                                :value="item.id"
                                :label="item.name"
                            ></el-option>
                        </el-select>
                    </div>
                </el-form-item>
                <!-- 评价内容 -->
                <el-form-item label="评价内容" prop="comment_content">
                    <div class="w-[360px]">
                        <el-input
                            v-model="formData.comment_content"
                            clearable
                            class="w-[360px]"
                            placeholder="请输入评价内容"
                            type="textarea"
                            :rows="5"
                        >
                        </el-input>
                    </div>
                </el-form-item>
                <!-- 评价等级 -->
                <el-form-item label="评价等级">
                    <div>
                        <el-rate
                            v-model="formData.comment_level"
                            show-text
                            :texts="['差评', '差评', '中评', '好评', '好评']"
                            text-color="#FABB19"
                            size="large"
                        />
                    </div>
                </el-form-item>

                <!-- 状态 -->
                <el-form-item label="状态">
                    <div>
                        <el-switch
                            v-model="formData.status"
                            :active-value="1"
                            :inactive-value="0"
                        />
                    </div>
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { useDictOptions } from '@/hooks/useDictOptions'
import { addcomment } from '@/api/marketing/evaluate'
import { getmenmberLists } from '@/api/marketing/vip'
const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return (mode.value = '新增虚拟评价')
})
const formData = reactive({
    member_package_id: '',
    image: '',
    name: '',
    comment_content: '',
    comment_level: 5,
    status: 1
})

const { optionsData } = useDictOptions<{
    menber: any[]
}>({
    menber: {
        api: getmenmberLists
    }
})
const formRules = reactive({
    image: [
        {
            required: true,
            message: '请选择头像',
            trigger: ['blur']
        }
    ],
    name: [
        {
            required: true,
            message: '请输入用户昵称',
            trigger: ['blur']
        }
    ],
    member_package_id: [
        {
            required: true,
            message: '请选择评价套餐',
            trigger: ['blur']
        }
    ],
    comment_content: [
        {
            required: true,
            message: '请输入评价内容',
            trigger: ['blur']
        }
    ]
})

const handleSubmit = async () => {
    await formRef.value?.validate()
    await addcomment(formData)
    popupRef.value?.close()
    emit('success')
}

const open = (type = 'add') => {
    mode.value = type
    popupRef.value?.open()
}

const setFormData = async (row: any) => {
    // const data = await adminDetail({
    //     id: row.id
    // })
    // for (const key in formData) {
    //     if (data[key] != null && data[key] != undefined) {
    //         //@ts-ignore
    //         formData[key] = data[key]
    //     }
    // }
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    setFormData
})
/**
 * 处理评价等级
 */
const handleSelect = (index: any) => {
    formData.comment_level = index + 1
}
const handleUnelect = (index: any) => {
    formData.comment_level += index + 1
}
</script>
