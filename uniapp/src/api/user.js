import request, { baseUrl, requireLogin } from "../utils/request";
export const miniProgramAppID = uni.getAccountInfoSync
  ? uni.getAccountInfoSync().miniProgram.appId
  : "";

export function getUserInfo(loginParam) {
  return request({
    url: "/api/user/info",
    method: "GET",
    preventLoading: true,
    loginParam,
  });
}

// 微信小程序登录
export function mnpLogin(data) {
  data.appid = miniProgramAppID;
  return request({
    url: "/api/login/mnpLogin",
    method: "POST",
    data,
    loginRequired: false,
  });
}

// 抖音小程序登录
export function douyinMnpLogin(data) {
  return request({
    url: "/api/login/douyinMnpLogin",
    method: "POST",
    data,
    loginRequired: false,
  });
}

export function submitFeedback(data) {
  return request({
    url: "/api/feedback/add",
    method: "POST",
    data,
  });
}

export function uploadCommonImage(filePath) {
  const token = uni.getStorageSync("token");

  // 如果存在 token，则将其添加到请求头中
  let header;
  if (token) {
    header = { token };
  } else {
    requireLogin();
    return Promise.reject(new Error("No token found"));
  }

  return uni.uploadFile({
    url: `${baseUrl}/api/upload/image`,
    filePath: filePath,
    name: "file",
    header: header,
  });
}
