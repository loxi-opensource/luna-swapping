import request from '@/utils/request'

export function getUpgradeLists(params: any) {
    return request.get({ url: '/setting.system.upgrade/lists', params })
}

// 下载更新包
export function upgradeDownloadPkg(params: any) {
    return request.post({ url: '/setting.system.upgrade/downloadPkg', params })
}

// 一键更新
export function upgrade(params: any) {
    return request.post({ url: '/setting.system.upgrade/upgrade', params, timeout: 120 * 1000 })
}
