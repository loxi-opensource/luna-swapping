import request from "@/utils/request";
//分享列表
export function getshareLists(params: any) {
  return request.get({ url: "/task.task_share/lists", params });
}
//设置详情
export function getShareconfig(params?: any) {
  return request.get({ url: "/task.task_share/getConfig", params });
}
//编辑分享设置
export function editShareconfig(params: any) {
  return request.post({ url: "/task.task_share/setConfig", params });
}
