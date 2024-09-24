import request from "@/utils/request";

//问题分类列表
export function getSnList(params: any) {
  return request.get({ url: "/user.sn/lists", params });
}

//新增问题示例
export function addSn(params: any) {
  return request.post({ url: "/user.sn/add", params });
}

//编辑问题示例
export function editSn(params: any) {
  return request.post({ url: "/user.sn/edit", params });
}

//删除问题示例
export function delSn(params: any) {
  return request.post({ url: "/user.sn/del", params });
}
//修改状态
export function editSnStatus(params: any) {
  return request.post({ url: "/user.sn/status", params });
}
