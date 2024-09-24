<template>
    <el-date-picker
        v-model="content"
        :type="type"
        range-separator="-"
        :format="format"
        :value-format="valueFormat"
        start-placeholder="开始时间"
        end-placeholder="结束时间"
        :clearable="true"
        @handleClose="handleChange"
    ></el-date-picker>
</template>

<script lang="ts" setup>
import { withDefaults, computed } from 'vue'

/**
 * 获取的是时间戳类型
 * <daterange-picker
 *      value-format="x"
 *      :second="true"
 *      v-model:startTime="formData.start_time"
 *      v-model:endTime="formData.end_time"
 *  />
 *
 *  默认的格式化时间类型
 *  <daterange-picker
 *      v-model:startTime="formData.start_time"
 *      v-model:endTime="formData.end_time"
 *  />
 * **/

/* Props S */
const props = withDefaults(
    defineProps<{
        type: string
        format: string
        valueFormat: string
        second: boolean
        startTime?: string | number
        endTime?: string | number
    }>(),
    {
        type: 'datetimerange',
        format: 'YYYY-MM-DD HH:mm:ss',
        valueFormat: 'YYYY-MM-DD HH:mm:ss',
        second: false,
        startTime: '',
        endTime: ''
    }
)
const emit = defineEmits(['update:startTime', 'update:endTime', 'change'])

const content = computed<any>({
    get: () => {
        if (props.second) {
            return [(props.startTime as number) * 1000, (props.endTime as number) * 1000]
        }
        return [props.startTime, props.endTime]
    },
    set: (value: Event | any) => {
        if (value === null) {
            emit('update:startTime', '')
            emit('update:endTime', '')
        } else {
            if (props.second) {
                emit('update:startTime', value[0] / 1000)
                emit('update:endTime', value[1] / 1000)
                return
            }
            emit('update:startTime', value[0])
            emit('update:endTime', value[1])
        }
    }
})

const handleChange = (event: string[]) => {
    emit('change', event)
}
</script>
