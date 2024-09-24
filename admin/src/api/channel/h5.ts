import request from '@/utils/request'

// H5渠道配置保存
export function setH5Config(params: any) {
    return request.post({ url: '/channel.web_page_setting/setConfig', params })
}

// H5渠道配置详情
export function getH5Config() {
    return request.get({ url: '/channel.web_page_setting/getConfig' })
}

// PC渠道配置详情
export function getPCConfig() {
    return request.get({ url: '/channel.pc_setting/getConfig' })
}
// PC渠道配置保存
export function setPCConfig(params: any) {
    return request.post({ url: '/channel.pc_setting/setConfig', params })
}
