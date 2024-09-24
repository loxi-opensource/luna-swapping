<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="600px"
            @confirm="handleSubmit"
            @close="handleClose"
        >
            <el-form ref="formRef" :model="formData" label-width="84px" :rules="formRules">
                <el-form-item label="名称" prop="name">
                    <el-input v-model="formData.name" placeholder="请输入名称" clearable />
                </el-form-item>
                <el-form-item label="换脸模式" prop="is_group_swap">
                    <el-radio-group v-model="formData.is_group_swap" :disabled="mode == 'edit'">
                        <el-radio :label="0">单人换脸</el-radio>
                        <el-radio :label="1">多人换脸</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="跨组多选" prop="allow_cross_group">
                    <el-radio-group
                        v-model="formData.allow_cross_group"
                        :disabled="disableAllowCrossGroup"
                    >
                        <el-radio :label="1">允许跨组</el-radio>
                        <el-radio :label="0">不允许跨组</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="模板限制" prop="template_scope">
                    <el-radio-group
                        v-model="formData.template_scope"
                        :disabled="disableTemplateScope"
                    >
                        <el-radio :label="1">单张模板</el-radio>
                        <el-radio :label="2">合辑模板</el-radio>
                        <el-radio :label="0">不限制</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-radio-group v-model="formData.status">
                        <el-radio :label="1">开启</el-radio>
                        <el-radio :label="0">禁用</el-radio>
                    </el-radio-group>
                </el-form-item>
            </el-form>
            <el-card v-if="mode == 'edit'">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xl"> 已关联模板组数量：{{ groupCnt }} </span>
                    <el-button
                        type="success"
                        @click="handleOpenGroupDetail(formData.id)"
                        :icon="Right"
                    >
                        关联模板组
                    </el-button>
                </div>
            </el-card>
        </popup>
        <strategy-detail v-if="showDetails" ref="detailsRef" @close="showDetails = false" />
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { Right } from '@element-plus/icons-vue'
import StrategyDetail from './components/StrategyDetail.vue'
import { strategyAdd, strategyDetail, strategyEdit } from '@/api/swap_template/strategy'

//弹框ref
const showDetails = ref<boolean>(true)
const detailsRef = shallowRef<InstanceType<typeof StrategyDetail>>()
const handleOpenGroupDetail = async (id: number) => {
    showDetails.value = true
    await nextTick()
    detailsRef.value.open(id)
}

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑玩法策略' : '新增玩法策略'
})
const formData = reactive({
    id: '',
    name: '',
    status: 1,
    allow_cross_group: 1,
    template_scope: 1,
    is_group_swap: 0
})

// 多人换脸模式，不允许跨组
watch(
    () => formData.is_group_swap,
    (val) => {
        if (val == 1) {
            // 如果是多人换脸，则一定不能跨组多选，一定是单张模板
            formData.allow_cross_group = 0
            formData.template_scope = 1
            // 添加模板组时，限制多人换脸模板组 todo
        }
    }
)
// 如果玩法允许跨组多选，则模板类型必须相同。
watch(
    () => formData.allow_cross_group,
    (val) => {
        if (val == 1 && formData.template_scope == 0) {
            // 如果允许跨组多选，则模板类型必须限制单张或合辑。默认是帮他改选为单张。todo 告知用户
            formData.template_scope = 1
        }
    }
)
// 如果不限制模板类型，则一定不允许跨组多选。
watch(
    () => formData.template_scope,
    (val) => {
        if (val == 0) {
            formData.allow_cross_group = 0
        }
    }
)

const disableAllowCrossGroup = computed(() => {
    return mode.value == 'edit' || formData.is_group_swap == 1 || formData.template_scope == 0
})
const disableTemplateScope = computed(() => {
    return mode.value == 'edit' || formData.is_group_swap == 1
})

const formRules = {
    name: [
        {
            required: true,
            message: '请输入',
            trigger: ['blur']
        }
    ]
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    mode.value == 'edit' ? await strategyEdit(formData) : await strategyAdd(formData)
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
const groupCnt = ref(0)
const getDetail = async (row: Record<string, any>) => {
    const data = await strategyDetail({
        id: row.id
    })
    setFormData(data)
    groupCnt.value = data.group_cnt
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
