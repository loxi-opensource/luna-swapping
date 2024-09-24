import request from '@/utils/request'

// LUNA作图任务列表
export function apiLunaDrawingTaskLists(params: any) {
    return request.get({ url: '/luna_drawing_task/lists', params })
}

// 删除LUNA作图任务
export function apiLunaDrawingTaskDelete(params: any) {
    return request.post({ url: '/luna_drawing_task/delete', params })
}

// LUNA作图任务详情
export function apiLunaDrawingTaskDetail(params: any) {
    return request.get({ url: '/luna_drawing_task/detail', params })
}
