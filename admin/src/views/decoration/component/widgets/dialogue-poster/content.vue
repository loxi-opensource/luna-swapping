<template>
    <div class="h-full poster" :style="styles">
        <!-- <div class="w-full h-full" :class="content.default == 1
                ? `poster-contain${content.poster}`
                : `backgroundImage: url(${content.posterUrl})`/admin/src/assets/images/poster1.png
            "> -->
        <div class="poster-bg flex flex-col">
            <img
                class="w-full"
                :src="
                    content.default == 2
                        ? appStore.getImageUrl(content.posterUrl)
                        : content.poster == 1
                        ? appStore.getImageUrl(content.defaultUrl1)
                        : appStore.getImageUrl(content.defaultUrl2)
                "
                alt=""
            />
            <div
                class="flex-1 min-h-0"
                :style="{
                    background: content.bgColor
                }"
            ></div>
        </div>

        <div
            class="w-full h-full poster-contain1 bg-[#BBBBBB]"
            :style="{
                color: content.textColor
            }"
        >
            <div class="px-[20px] pt-[120px]">
                <div class="bg-white rounded-lg p-[15px] text-tx-primary">
                    <span class="text-[18px] font-bold">
                        宇宙的尽头是什么？宇宙的尽头是什么？宇宙的尽头是什么？宇宙…
                    </span>
                    <br />
                    <span
                        class="text-[14px]"
                        :class="{
                            'line-clamp-[1]': content.showContentType == 1
                        }"
                        :style="{
                            '-webkit-line-clamp': content.contentNum
                        }"
                    >
                        目前我们对宇宙的尽头还没有完全的了解，也没有确凿的证据证明宇宙是否有尽头。根据我们目前对宇宙的认知，宇宙是无限的，没有边界。但是，我们只能观测到宇宙中可见的部分，而宇宙中还有很多我们无法观测
                        到的暗物质和暗能量，因此我们对宇宙的认识还有很多未知和待探索的领域。
                        近年来，科学家们通过天文观测和理论模型
                        等手段，提出了一些有关宇宙尽头的假说，如宇宙是一个闭合的球面空间等。但这些假说还需要更多的科学实验证实或否定。因此，宇宙的尽头仍然是一个待解决的科学问题。
                    </span>
                </div>
            </div>
            <div class="px-[20px] pt-[10px]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img
                            :src="appStore.getImageUrl(content.defaultAvatar)"
                            alt=""
                            class="w-[60px] h-[60px] rounded-full"
                        />
                        <div class="ml-[10px] text-[16px]">
                            <div>李富贵</div>
                            <div v-if="content.showData == 1">{{ content.data }}</div>
                        </div>
                    </div>
                    <div>
                        <img src="@/assets/images/code.jpg" alt="" class="w-[100px] h-[100px]" />
                        <div>长按识别二维码</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import useAppStore from '@/stores/modules/app'
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
    },
    height: {
        type: String,
        default: '170px'
    }
})

const appStore = useAppStore()
</script>

<style lang="scss" scoped>
.poster {
    width: 360px;
    height: 615px;
    position: relative;
    overflow: hidden;
    &-bg {
        position: absolute;
        width: 100%;
        height: 100%;
    }
}

.poster-contain1 {
    position: relative;
    z-index: 1;
    background-color: transparent;
}
</style>
