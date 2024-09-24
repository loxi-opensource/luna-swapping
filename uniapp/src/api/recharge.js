import request from "../utils/request";

export function getRechargePackage(data) {
  return request({
    url: "/api/recharge/rechargePackage",
    method: "GET",
    preventLoading: true,
    data,
  });
}

export function getRechargeOrders() {
  return request({
    url: "/api/recharge/orderList",
    method: "GET",
  });
}

export function submitRechargeOrder(data) {
  return request({
    url: "/api/recharge/recharge",
    method: "POST",
    data,
  });
}
