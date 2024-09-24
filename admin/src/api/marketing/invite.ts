import request from "@/utils/request";
//邀请列表
export function getinviteLists(params: any) {
  return request.get({ url: "/task.task_invite/lists", params });
}
//设置详情
export function getInviteconfig(params?: any) {
  return request.get({ url: "/task.task_invite/getConfig", params });
}
//编辑设置
export function editInviteconfig(params: any) {
  return request.post({ url: "/task.task_invite/setConfig", params });
}
