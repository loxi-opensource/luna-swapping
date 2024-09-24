import request from '@/utils/request'

// 创作分类列表
export function feedbackList(params: any) {
    return request.get({ url: '/feedback/lists', params })
}
