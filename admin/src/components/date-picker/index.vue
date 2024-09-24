<template>
    <el-date-picker
        v-model="content"
        :type="type"
        :placeholder="placeholder"
        :format="format"
        :clearable="true"
        :value-format="valueFormat"
        @handleClose="handleChange"
    />
</template>

<script lang="ts" setup>
import { withDefaults, computed } from 'vue'

/**
 * 获取的是时间戳类型
 * <date-picker
 *      value-format="x"
 *      :second="true"
 *      v-model="formData.start_time"
 *  />
 *
 *  默认的格式化时间类型
 *  <date-picker
 *      v-model="formData.start_time"
 *  />
 * **/

/* Props S */
const props = withDefaults(
    defineProps<{
        type: string
        format: string
        valueFormat: string
        placeholder: string
        second: boolean
        modelValue?: string | number
    }>(),
    {
        type: 'date',
        format: 'YYYY-MM-DD',
        valueFormat: 'YYYY-MM-DD HH:mm:ss',
        placeholder: '',
        second: false,
        modelValue: ''
    }
)
const emit = defineEmits(['update:modelValue', 'change'])

const content = computed<any>({
    get: () => {
        if (props.second) {
            return (props.modelValue as number) * 1000
        }
        return props.modelValue
    },
    set: (value: Event | any) => {
        if (value === null) {
            emit('update:modelValue', '')
        } else {
            if (props.second) {
                emit('update:modelValue', value / 1000)
                return
            }
            emit('update:modelValue', value)
        }
    }
})

const handleChange = (event: string[]) => {
    emit('change', event)
}
</script>
