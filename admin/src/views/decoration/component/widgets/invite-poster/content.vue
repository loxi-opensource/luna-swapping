<template>
    <div class="poster" :style="styles">
        <div
            class="w-full h-full poster-contain1 bg-[#BBBBBB]"
            :style="{
                backgroundImage:
                    content.default == 2
                        ? `url(${appStore.getImageUrl(content.posterUrl)})`
                        : content.poster == 1
                        ? `url(${appStore.getImageUrl(content.defaultUrl1)})`
                        : `url(${appStore.getImageUrl(content.defaultUrl2)})`
            }"
        >
            <VueDragResize
                :isActive="true"
                :w="100"
                :h="100"
                v-on:resizing="codeResize"
                v-on:dragging="codeResize"
                :z="999"
                :isResizable="false"
                parentLimitation
                :x="content.code.x"
                :y="content.code.y"
            >
                <div>
                    <img src="@/assets/images/code.jpg" alt="" />
                </div>
            </VueDragResize>

            <VueDragResize
                :isActive="true"
                w="auto"
                h="auto"
                v-on:resizing="dataResize"
                v-on:dragging="dataResize"
                :z="999"
                :isResizable="false"
                parentLimitation
                :x="content.data.x"
                :y="content.data.y"
                v-if="content.showData == 1"
            >
                <div class="text-white">{{ content.data.content }}</div>
            </VueDragResize>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'
import type options from './options'
import VueDragResize from 'vue-drag-resize/src'
import { ref } from 'vue'
import useAppStore from '@/stores/modules/app'
type OptionsType = ReturnType<typeof options>
const props = defineProps({
    content: {
        type: Object as PropType<OptionsType['content']>,
        default: () => ({})
    },
    styles: {
        type: Object as PropType<OptionsType['styles']>,
        default: () => ({})
    },
    height: {
        type: String,
        default: '170px'
    }
})

const appStore = useAppStore()

const codeResize = (e: any) => {
    // console.log(e)
    props.content.code.x = e.left
    props.content.code.y = e.top
}
const dataResize = (e: any) => {
    // console.log(e)
    props.content.data.x = e.left
    props.content.data.y = e.top
}
</script>

<style lang="scss" scoped>
.poster {
    width: 270px;
    height: 450px;
    margin: 0 auto;
}

.poster-contain1 {
    background-repeat: no-repeat;
    background-size: 100% 100%;
    width: 270px;
    height: 450px;
    position: absolute;
}
</style>
