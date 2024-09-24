<script setup lang="ts">
import { computed } from 'vue'
import { ElRadio, ElRadioButton, ElRadioGroup } from 'element-plus'
const props = defineProps<{
    modelValue?: string | number | boolean
    options: { label: string; value: string }[]
    type?: '' | 'button'
}>()
const emit = defineEmits<{
    (event: 'update:modelValue', value: string | number | boolean): void
}>()
const value = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value!)
    }
})
</script>
<template>
    <ElRadioGroup v-model="value">
        <component
            :is="props.type == 'button' ? ElRadioButton : ElRadio"
            v-for="item in options"
            :key="item.value"
            :label="item.value"
            :name="item.label"
        >
            {{ item.label }}
        </component>
    </ElRadioGroup>
</template>
