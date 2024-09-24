import request from "@/utils/request";

// 获取分享设置
export function getLunaServiceConfig() {
  return request.get({ url: "/setting.lunaServiceSetting/getConfig" });
}

export function setLunaServiceConfig(params: any) {
  return request.post({ url: "/setting.lunaServiceSetting/setConfig", params });
}
