import request from '@/utils/request'

// 配置
export function getConfig() {
    return request.get({ url: '/config/getConfig' })
}

// 工作台主页
export function getWorkbench() {
    return request.get({ url: '/workbench/index' })
}

//字典数据
export function getDictData(params: any) {
    return request.get({ url: '/config/dict', params })
}

//检测新版本
export function checkVersion(params?: any) {
    return request.get({ url: '/config/checkVersion', params })
}

// 获取Luna Token
export function getLunaToken() {
    return request.get({ url: '/config/lunaToken' })
}
