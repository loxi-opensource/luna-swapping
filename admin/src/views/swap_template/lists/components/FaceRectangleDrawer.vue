<template>
    <div class="main">
        <el-button
            @click="toggleDisplay"
            size="small"
            plain
            :icon="!showCanvas ? View : Hide"
            class="absolute left-0 bottom-0 z-50"
        >
            {{ showCanvas ? '原图' : '人脸' }}
        </el-button>
        <el-image
            ref="image"
            :src="imageSrc"
            @load="handleImageLoad"
            fit="cover"
            :style="{
                width: `${width}px`,
                display: 'none'
            }"
        />
        <el-image
            :src="imageSrc"
            fit="cover"
            :style="{
                width: `${width}px`,
                display: 'block'
            }"
            v-show="!showCanvas"
            class="rounded-lg"
        />
        <canvas ref="canvas" v-show="showCanvas" class="rounded-lg"></canvas>
    </div>
</template>

<script lang="ts" setup>
import { defineProps, ref, watch } from 'vue'
import { Hide, View } from '@element-plus/icons-vue'

// 定义 props
const props = defineProps<{
    imageSrc: string
    width: {
        type: number
        default: 200
    }
    faceData: Array<{ x: number; y: number; w: number; h: number }>
}>()

const canvas = ref<HTMLCanvasElement | null>(null)
const image = ref<HTMLImageElement | null>(null)

const showCanvas = ref(false) // 初始状态为显示原图

const toggleDisplay = () => {
    showCanvas.value = !showCanvas.value // 切换状态
}

// 处理图片加载
const handleImageLoad = (event: Event) => {
    const target = event.target as HTMLImageElement
    image.value = target // 将原生 img 元素赋值给 image
    drawRectangles() // 调用绘制矩形框函数
}

// 绘制矩形框的函数
const drawRectangles = () => {
    if (!canvas.value || !image.value || !(image.value instanceof HTMLImageElement)) {
        console.error('Canvas or image is not valid')
        return
    }

    const ctx = canvas.value.getContext('2d')
    if (!ctx) return

    // 计算图片渲染比例
    const renderRatio = props.width / image.value.width

    // 设置画布大小
    canvas.value.width = image.value.width * renderRatio
    canvas.value.height = image.value.height * renderRatio

    // 绘制图片
    // ctx.drawImage(image.value, 0, 0)
    ctx.drawImage(
        image.value,
        0,
        0,
        image.value.naturalWidth,
        image.value.naturalHeight,
        0,
        0,
        image.value.naturalWidth * renderRatio,
        image.value.naturalHeight * renderRatio
    )

    // 绘制矩形框
    ctx.strokeStyle = 'red' // 矩形框颜色
    ctx.lineWidth = 2 // 矩形框宽度

    props.faceData.forEach((face) => {
        // 将比例转换为像素值
        const rectX = face.x * image.value.width * renderRatio
        const rectY = face.y * image.value.height * renderRatio
        const rectW = face.w * image.value.width * renderRatio
        const rectH = face.h * image.value.height * renderRatio

        ctx.strokeRect(rectX, rectY, rectW, rectH)
    })
}

// 监听 imageSrc 的变化
watch(
    () => props.imageSrc,
    () => {
        drawRectangles()
    }
)
</script>

<style scoped lang="scss">
.main {
    position: relative;

    canvas {
        position: relative;
        width: 100%;
        height: auto;
    }
}
</style>
