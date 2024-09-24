import request from '@/utils/request'

// LUNA作图任务列表
export function apiSwapTaskLists(params: any) {
    return request.get({ url: '/swap_task.swap_task/lists', params })
}
