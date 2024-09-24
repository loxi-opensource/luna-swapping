import { lunaRequest } from '@/utils/request/luna'

// 创建任务
export function submitTask(params: any) {
    return lunaRequest.post({ url: '/userMessage/createSwapEnhance', params })
}
// 创建任务V3版本：指定映射多人换脸
export function submitTaskV3(params: any) {
    return lunaRequest.post({ url: '/userMessage/createSwapEnhanceV3', params })
}
// 任务状态
export function taskStatus(params: any) {
    return lunaRequest.get({ url: '/userMessage/polling', params })
}

// 素材图人脸列表
export function getMaterialFaceList(params: any) {
    return lunaRequest.get({ url: '/userMessage/getMaterialFileFaceList', params })
}
// 用户图人脸列表
export function getUserFaceList(params: any) {
    return lunaRequest.get({ url: '/userMessage/getUserFileFaceList', params })
}
