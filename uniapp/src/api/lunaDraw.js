import request, { baseUrl, requireLogin } from "../utils/request";

export function getMyGalleryList(data, preventLoading) {
  if (!data.only_thumb) {
    data.only_thumb = 1;
  }

  return request({
    url: "/api/lunaDraw/myGalleryListV3",
    method: "GET",
    data,
    preventLoading,
    loginParam: {
      redirectTab: "/pages/user/index",
    },
  });
}

export function uploadImage(filePath) {
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
    url: `${baseUrl}/api/lunaDraw/uploadImage`,
    filePath: filePath,
    name: "file",
    header: header,
  });
}

export function submitDrawingTask(data) {
  let params = {
    url: "/api/lunaDraw/submitDrawingV3",
    method: "POST",
    data: data,
  };
  if (data.is_share || false) {
    params.loadingToastTips =
      "正在分析照片，请不要退出当前页面（预计需要10-20秒）";
  }
  return request(params);
}

export function pollTaskStatus(upTaskId) {
  return request({
    url: "/api/lunaDraw/taskStatusV3",
    method: "GET",
    data: { up_task_id: upTaskId },
    preventLoading: true,
  });
}

export function getPolicy(data) {
  return request({
    url: "/api/index/policy",
    method: "GET",
    preventLoading: true,
    loginRequired: false,
    data,
  });
}
