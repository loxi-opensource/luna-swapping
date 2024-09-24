import request from '@/utils/request'

export type NoticeConfigType = {
    is_bulletin: number
    bulletin_content: string
    bulletin_title: string
}

// 获取公告配置
export function getConfig() {
    return request.get({ url: '/setting.web.web_setting/getBulletinConfig' })
}

// 设置公告配置
export function setConfig(params: NoticeConfigType) {
    return request.post({ url: '/setting.web.web_setting/setBulletinConfig', params })
}
