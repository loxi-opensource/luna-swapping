<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="550px"
            destroy-on-close
            @confirm="handleSubmit"
        >
            <Container v-model="formData" />
        </popup>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import Container from './container.vue'
import type { WidgetNormalization } from './material'
import { cloneDeep } from 'lodash-es'
const emit = defineEmits<{
    (event: 'add', value: WidgetNormalization): void
    (event: 'edit', value: WidgetNormalization): void
}>()

const formData = ref<WidgetNormalization>({
    name: '',
    id: '',
    title: '',
    props: {}
})
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()

const mode = ref('add')
//弹框标题
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑表单项' : '添加表单项'
})

//提交
const handleSubmit = async () => {
    await formRef.value?.validate()
    if (mode.value == 'add') {
        emit('add', formData.value)
    } else {
        emit('edit', formData.value)
    }
    popupRef.value?.close()
}

const open = (type = 'add') => {
    mode.value = type
    if (type === 'add') {
        formData.value = {
            name: '',
            id: '',
            title: '',
            props: {}
        }
    }
    popupRef.value?.open()
}
const setFormData = (data: WidgetNormalization) => {
    formData.value = cloneDeep(data)
}
defineExpose({
    open,
    setFormData
})
</script>
