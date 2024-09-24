import request from '@/utils/request'

// 余额明细
export function accountLog(params?: any) {
    return request.get({ url: '/finance.account_log/lists', params })
}

// 充值记录
export function rechargeLists(params?: any) {
    return request.get({ url: '/recharge.recharge/lists', params }, { ignoreCancelToken: true })
}

// 余额变动类型
export function getUmChangeType(params?: any) {
    return request.get({ url: '/finance.account_log/getUmChangeType', params })
}

// 余额变动类型
export function getDrawChangeType(params?: any) {
    return request.get({ url: '/finance.account_log/getDrawChangeType', params })
}

//退款
export function refund(params?: any) {
    return request.post({ url: '/recharge.recharge/refund', params })
}

//重新退款
export function refundAgain(params?: any) {
    return request.post({ url: '/finance.refund/refundAgain', params })
}

//退款记录
export function refundRecord(params?: any) {
    return request.get({ url: '/finance.refund/record', params })
}

//退款日志
export function refundLog(params?: any) {
    return request.get({ url: '/finance.refund/log', params })
}

//退款统计
export function refundStat(params?: any) {
    return request.get({ url: '/finance.refund/stat', params })
}

//财务中心
export function center(params?: any) {
    return request.get({ url: '/finance.finance/center', params })
}
//
export function getMoneyChangeType(params?: any) {
    return request.get({ url: '/finance.account_log/getMoneyChangeType', params })
}
