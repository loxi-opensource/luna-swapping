import request from "@/utils/request";

//充值订单列表
export function getrechargeLists(params: any) {
  return request.get(
    { url: "/recharge.recharge_order/lists", params },
    { ignoreCancelToken: true }
  );
}

//充值订单详情
export function getrechargeDetail(params: any) {
  return request.get({ url: "/recharge.recharge_order/detail", params });
}

//退款
export function RechargeOrderRefund(params: any) {
  return request.post({ url: "/recharge.recharge_order/refund", params });
}
