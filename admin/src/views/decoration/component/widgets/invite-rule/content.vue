<template>
    <div class="container mt-2 p-2">
        <div class="bg-white rounded-lg p-2">
            <span class="font-bold">{{ content.name }}</span>
            <div class="text-[#999999]">
                {{ content.data }}
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'

type OptionsType = ReturnType<typeof options>
const props = defineProps({
    content: {
        type: Object as PropType<OptionsType['content']>,
        default: () => ({})
    },
    styles: {
        type: Object as PropType<OptionsType['styles']>,
        default: () => ({})
    }
})
</script>

<style lang="scss" scoped>
.container {}
</style>
