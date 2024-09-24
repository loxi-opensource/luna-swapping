import request from "@/utils/request";

//订单列表
export function getMemberLists(params: any) {
  return request.get(
    { url: "/member.member_order/lists", params },
    { ignoreCancelToken: true }
  );
}

//订单详情
export function getMemberDetail(params: any) {
  return request.get({ url: "/member.member_order/detail", params });
}

//退款
export function MemberOrderRefund(params: any) {
  return request.post({ url: "/member.member_order/refund", params });
}
