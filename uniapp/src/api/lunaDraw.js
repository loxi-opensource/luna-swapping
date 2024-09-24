import request, { baseUrl, requireLogin } from "../utils/request";

export function getTagList(tagGroupID = 1) {
  return request({
    url: "/api/lunaDraw/tagList",
    method: "GET",
    data: { tagGroupID },
    loginRequired: false,
  });
}

export function getMaterialFiles(data) {
  const id = data.tagID;
  return request({
    url: "/api/lunaDraw/materialFiles",
    method: "GET",
    data: { id },
    loginRequired: false,
    preventLoading: true,
  });
}

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

export function getIndex() {
  return request({
    url: "/api/index/index",
    method: "GET",
    preventLoading: true,
    loginRequired: false,
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

export function getTagListWithPreview(data, requrestParams) {
  let preventLoading = false;
  if (requrestParams && requrestParams.preventLoading) {
    preventLoading = true;
  }

  return request({
    url: "/api/lunaDraw/multiTagListWithPreview",
    method: "GET",
    data,
    preventLoading,
    loginRequired: false,
  });
}

export function getFaceSwapPopularMaterialFiles() {
  return request({
    url: "/api/lunaDraw/popularMaterialFiles",
    method: "GET",
    data: {
      category: "1v1",
      fetch_num: 16,
    },
    loginRequired: false,
  });
}
