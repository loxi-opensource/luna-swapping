import request from "../utils/request";

export function getStrategyList(data) {
  return request({
    url: "/api/swapTemplate/strategyList",
    method: "GET",
    preventLoading: true,
    loginRequired: false,
    data,
  });
}

export function getGroupList(data) {
  return request({
    url: "/api/swapTemplate/groupList",
    method: "GET",
    preventLoading: true,
    loginRequired: false,
    data,
  });
}

export function getTemplateList(data) {
  return request({
    url: "/api/swapTemplate/templateList",
    method: "GET",
    preventLoading: true,
    loginRequired: false,
    data,
  });
}
