import request from "@/utils/request";
//评价列表
export function getcommentLists(params: any) {
  return request.get({ url: "/member.member_package_comment/lists", params });
}
//删除评价
export function Delcomment(params: any) {
  return request.post({ url: "/member.member_package_comment/del", params });
}
//添加评价
export function addcomment(params: any) {
  return request.post({ url: "/member.member_package_comment/add", params });
}
