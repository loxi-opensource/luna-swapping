<script setup lang="ts">
import { computed } from 'vue'
import { ElOption, ElSelect } from 'element-plus'
import { isObject } from 'lodash'

const props = defineProps<{
    modelValue?: string | string[]
    options: { label: string; value: string }[] | string[]
}>()
const emit = defineEmits<{
    (event: 'update:modelValue', value: string | string[]): void
}>()
const value = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value!)
    }
})
const getOption = computed<{ label: string; value: string }[]>(() => {
    return props.options.map((item) => {
        if (isObject(item)) {
            return item
        } else {
            return {
                label: item,
                value: item
            }
        }
    })
})
</script>
<template>
    <ElSelect v-model="value" :teleported="true" style="width: 100%">
        <ElOption
            v-for="item in getOption"
            :key="item.value"
            :label="item.label"
            :value="item.value"
        />
    </ElSelect>
</template>

<style lang="scss"></style>
