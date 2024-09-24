import request from "@/utils/request";

// 获取分享设置
export function getshareConfig() {
  return request.get({ url: "/setting.shareSetting/getConfig" });
}

export function setshareConfig(params: any) {
  return request.post({ url: "/setting.shareSetting/setConfig", params });
}
