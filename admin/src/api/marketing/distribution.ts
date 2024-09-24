import request from '@/utils/request'
//列表
export function getdistributorLists(params: any) {
    return request.get({ url: '/distribution.distributor/lists', params })
}
//修改分销状态
export function changedistributor(data: any) {
    return request.post({ url: '/distribution.distributor/status', data })
}
//开通分销商/adminapi/distribution.distributor/add
export function adddistributor(data: any) {
    return request.post({ url: '/distribution.distributor/add', data })
}
//分销详情/adminapi/distribution.distributor/detail
export function getdistributordetail(params: any) {
    return request.get({ url: '/distribution.distributor/detail', params })
}
//下级列表/adminapi/distribution.distributor/belowLists
export function getbelowLists(params: any) {
    return request.get({ url: '/distribution.distributor/belowLists', params })
}
//分销订单
export function getorderLists(params: any) {
    return request.get({ url: '/distribution.distribution_order/lists', params })
}

//注册奖励
export function getRegisterReward(params?: any) {
    return request.get({ url: '/setting.user.user/getRegisterReward', params })
}
///adminapi/setting.user.user/getRegisterReward设置注册奖励
export function setRegisterReward(data: any) {
    return request.post({ url: '/setting.user.user/setRegisterReward', data })
}

//提现记录
export function getwithdrawLists(params: any) {
    return request.get({ url: '/distribution.withdraw/lists', params })
}
//提现审核
export function verify(data: any) {
    return request.post({ url: '/distribution.withdraw/verify', data })
}
//转账
export function withdrawtransfer(data: any) {
    return request.post({ url: '/distribution.withdraw/transfer', data })
}
//提现详情
export function withdrawdetail(params: any) {
    return request.get({ url: '/distribution.withdraw/detail', params })
}
//申请列表
export function applylists(params: any) {
    return request.get({ url: '/distribution.distributionApply/lists', params })
}
//申请详情
export function applydetial(params: any) {
    return request.get({ url: '/distribution.distributionApply/detail', params })
}
//审核
export function applyaudit(data: any) {
    return request.post({ url: '/distribution.distributionApply/audit', data })
}
//获取提现设置
export function whitdrawConfig(params?: any) {
    return request.get({ url: '/distribution.withdraw/getConfig', params })
}
//提现设置
export function setConfig(data: any) {
    return request.post({ url: '/distribution.withdraw/setConfig', data })
}
//分销设置
export function getConfig(params?: any) {
    return request.get({ url: '/distribution.config/getConfig', params })
}
export function setdistributionConfig(data: any) {
    return request.post({ url: '/distribution.config/setConfig', data })
}
