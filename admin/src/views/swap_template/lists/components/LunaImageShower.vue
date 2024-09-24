<template>
    <el-card>
        <div class="flex gap-4 mt-2">
            <face-rectangle-drawer
                v-if="fileUrl"
                :face-data="faceData"
                width="160"
                :image-src="fileUrl"
            ></face-rectangle-drawer>
            <div class="flex flex-col justify-between flex-1">
                <el-input
                    v-model="fileIdSpecified"
                    placeholder="可直接指定算法侧文件ID"
                    readonly
                    v-if="fileIdSpecified"
                />
                <div v-if="fileUrl && faceData.length" class="text-ellipsis line-clamp-3">
                    人脸位置：{{ faceDataSpecified }}
                </div>
            </div>
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { defineProps, onMounted, ref } from 'vue'
import cache from '@/utils/cache'
import { LUNA_TOKEN_KEY } from '@/enums/cacheEnums'
import { getLunaToken } from '@/api/app'
import FaceRectangleDrawer from './FaceRectangleDrawer.vue'

const props = defineProps<{
    isMaterialFile: boolean
    fileId: string
    fileUrl: string
    faceData: any[]
}>()

const faceDataSpecified = ref(props.faceData)
const fileIdSpecified = ref(props.fileId)

onMounted(() => {
    getLunaToken().then((res) => {
        console.log('getLunaToken', res)
        cache.set(LUNA_TOKEN_KEY, res.token)
    })
})
</script>
