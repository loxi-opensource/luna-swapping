<template>
    <footer class="layout-footer">
        <div
            class="flex flex-wrap justify-center p-2 text-xs text-tx-secondary max-w-[1200px] mx-auto"
        >
            <a
                class="mx-2 hover:underline flex items-center"
                :href="item.value"
                target="_blank"
                v-for="item in copyright"
                :key="item.key"
            >
                <img v-if="item.icon" :src="item.icon" class="h-[16px] mr-[4px]" alt="" />
                {{ item.key }}
            </a>
        </div>
    </footer>
</template>

<script setup lang="ts">
import useAppStore from '@/stores/modules/app'

const appStore = useAppStore()
const copyright = computed(() => appStore.config.copyright_config || [])
</script>
