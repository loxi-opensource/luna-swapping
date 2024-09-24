<script setup lang="ts">
import { computed } from 'vue'
import { ElSwitch } from 'element-plus'
const props = defineProps<{
    modelValue?: boolean
}>()
const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void
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
    <ElSwitch v-model="value" />
</template>
