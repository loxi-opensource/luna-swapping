import request from '@/utils/request'
//获取充值套餐配置
export function getRechargeConfig() {
    return request.get({ url: '/recharge.recharge_package/getConfig' })
}
//设置充值套餐配置
export function setRechargeConfig(params: any) {
    return request.post({ url: '/recharge.recharge_package/setConfig', params })
}

// 充值套餐列表
export function getRechargeLists(params: any) {
    return request.get({ url: '/recharge.recharge_package/lists', params })
}

// 添加充值套餐
export function rechargeAdd(params: any) {
    return request.post({ url: '/recharge.recharge_package/add', params })
}
// 编辑充值套餐
export function rechargeEdit(params: any) {
    return request.post({ url: '/recharge.recharge_package/edit', params })
}
// 删除充值套餐
export function rechargeDelete(params: any) {
    return request.post({ url: '/recharge.recharge_package/del', params })
}

// 充值套餐详情
export function getRechargeDetail(params: any) {
    return request.get({ url: '/recharge.recharge_package/detail', params })
}

// 修改套餐状态
export function rechargeStatus(params: any) {
    return request.post({ url: '/recharge.recharge_package/status', params })
}

// 修改推荐状态
export function rechargeRecommend(params: any) {
    return request.post({ url: '/recharge.recharge_package/recommend', params })
}
