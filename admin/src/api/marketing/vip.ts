import request from '@/utils/request'
//会员套餐列表
export function getmenmberLists(params: any) {
    return request.get({ url: '/member.member_package/lists', params })
}
//添加会员套餐
export function addMenmber(params: any) {
    return request.post({ url: '/member.member_package/add', params })
}
//删会员套餐
export function delMenmber(params: any) {
    return request.post({ url: '/member.member_package/del', params })
}
//会员套餐详情
export function detialMenmber(params: any) {
    return request.get({ url: '/member.member_package/detail', params })
}
//编辑会员套餐
export function editlMenmber(params: any) {
    return request.post({ url: '/member.member_package/edit', params })
}
export function updatestatus(params: any) {
    return request.post({ url: '/member.member_package/status', params })
}
export function updatedefault(params: any) {
    return request.post({ url: '/member.member_package/default', params })
}
//开启关闭会员套餐
export function getConfig(params?: any) {
    return request.get({ url: '/member.member_package/getConfig', params })
}
export function setConfig(data: any) {
    return request.post({ url: '/member.member_package/setConfig', data })
}

//会员权益列表
export function getMemberBenefits(params: any) {
    return request.get({ url: '/member.member_benefits/lists', params })
}

export function memberBenefitsDetail(params: any) {
    return request.get({ url: '/member.member_benefits/detail', params })
}

// 添加会员权益
export function memberBenefitsAdd(params: any) {
    return request.post({ url: '/member.member_benefits/add', params })
}

// 编辑会员权益
export function memberBenefitsEdit(params: any) {
    return request.post({ url: '/member.member_benefits/edit', params })
}

// 删除会员权益
export function memberBenefitsDelete(params: any) {
    return request.post({ url: '/member.member_benefits/del', params })
}

// 调整会员权益状态
export function memberBenefitsStatus(params: any) {
    return request.post({ url: '/member.member_benefits/status', params })
}

export function getBenefitsListsAll() {
    return request.get({ url: '/member.member_package/benefitsLists' })
}
