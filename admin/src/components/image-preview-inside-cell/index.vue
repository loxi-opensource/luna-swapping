<template>
    <el-popover trigger="hover" placement="right" :width="popUpwidth" hide-after="0">
        <template #default>
            <img :src="imageUrl" />
        </template>
        <template #reference>
            <img
                :src="imageUrl"
                :style="{
                    width: originWidth + 'px',
                    height: originWidth + 'px',
                    objectFit: 'cover'
                }"
            />
        </template>
    </el-popover>
</template>

<script lang="ts" setup>
defineProps({
    popUpwidth: {
        type: [String, Number],
        default: 'auto'
    },
    imageUrl: {
        type: String,
        default: ''
    },
    originWidth: {
        type: [String, Number],
        default: 'auto'
    }
})
</script>
