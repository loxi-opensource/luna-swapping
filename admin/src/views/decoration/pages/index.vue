<template>
    <div class="decoration-pages min-w-[1100px]">
        <div class="flex h-full items- justify-between">
            <Menu v-model="activeMenu" :menus="menus" />
            <Preview v-model="selectWidgetIndex" :pageData="getPageData" />
            <attr-setting class="bg-white" :widget="getSelectWidget" />
        </div>

        <footer-btns class="mt-4" :fixed="true" v-perms="['decorate.page/save']">
            <el-button type="primary" @click="setData">保存</el-button>
        </footer-btns>
    </div>
</template>
<script lang="ts" setup name="decorationPages">
import Menu from '../component/pages/menu.vue'
import Preview from '../component/pages/preview.vue'
import AttrSetting from '../component/pages/attr-setting.vue'
import widgets from '../component/widgets'
import { getDecoratePages, setDecoratePages } from '@/api/decoration'
import { getNonDuplicateID } from '@/utils/util'
enum pagesTypeEnum {
    USER = '1',
    VIP = '2',
    SERVICE = '3',
    POSTER = '5',
    INVITE = '6'
}

const generatePageData = (widgetNames: string[]) => {
    return widgetNames.map((widgetName) => {
        const options = {
            id: getNonDuplicateID(),
            ...(widgets[widgetName]?.options() || {})
        }
        return options
    })
}

const menus: Record<
    string,
    {
        id: number
        name: string
        pageData: any[]
    }
> = reactive({
    [pagesTypeEnum.USER]: {
        id: 1,
        type: 1,
        name: '个人中心',
        pageData: generatePageData(['user-info', 'open-vip', 'my-service', 'user-banner'])
    },
    [pagesTypeEnum.VIP]: {
        id: 2,
        type: 2,
        name: '会员中心',
        pageData: generatePageData([
            'vip-top',
            'vip-body',
            'vip-advantage',
            'vip-notice',
            'vip-evaluate'
        ])
    },
    [pagesTypeEnum.SERVICE]: {
        id: 3,
        type: 3,
        name: '客服设置',
        pageData: generatePageData(['customer-service'])
    },
    [pagesTypeEnum.POSTER]: {
        id: 5,
        type: 5,
        name: '邀请海报',
        pageData: generatePageData(['invite-poster', 'invite-rule'])
    },
    [pagesTypeEnum.INVITE]: {
        id: 6,
        type: 6,
        name: '对话海报',
        pageData: generatePageData(['dialogue-poster'])
    }
})

const activeMenu = ref('1')
const selectWidgetIndex = ref(-1)
const getPageData = computed(() => {
    return menus[activeMenu.value]?.pageData ?? []
})
const getSelectWidget = computed(() => {
    return menus[activeMenu.value]?.pageData[selectWidgetIndex.value] ?? ''
})

const getData = async () => {
    const data = await getDecoratePages({ id: activeMenu.value })
    menus[String(data.id)].pageData = JSON.parse(data.data)
}

const setData = async () => {
    await setDecoratePages({
        ...menus[activeMenu.value],
        data: JSON.stringify(menus[activeMenu.value].pageData)
    })
    getData()
}
watch(
    activeMenu,
    () => {
        selectWidgetIndex.value = getPageData.value.findIndex((item) => !item.disabled)
        getData()
    },
    {
        immediate: true
    }
)
</script>
<style lang="scss" scoped>
.decoration-pages {
    height: calc(100vh - var(--navbar-height) - 120px);
    @apply flex flex-col;
}
</style>
