import request from "../utils/request";
import { miniProgramAppID } from "./user";

export function requestPrepay(data) {
  data.appid = miniProgramAppID;
  return request({
    url: "/api/pay/prepay",
    method: "POST",
    data,
  });
}

export function queryPayStatus(data) {
  return request({
    url: "/api/pay/payStatus",
    method: "GET",
    preventLoading: true,
    data,
  });
}
