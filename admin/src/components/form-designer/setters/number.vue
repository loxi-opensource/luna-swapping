<script setup lang="ts">
import { computed } from 'vue'
import { ElInputNumber } from 'element-plus'
const props = defineProps<{
    modelValue?: number
}>()
const emit = defineEmits<{
    (event: 'update:modelValue', value: number): void
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
    <ElInputNumber v-model="value" />
</template>

<style lang="scss"></style>
