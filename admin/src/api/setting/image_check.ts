import request from "@/utils/request";

// 获取分享设置
export function getImageCheckConfig() {
  return request.get({ url: "/setting.imageCheckSetting/getConfig" });
}

export function setImageCheckConfig(params: any) {
  return request.post({ url: "/setting.imageCheckSetting/setConfig", params });
}
