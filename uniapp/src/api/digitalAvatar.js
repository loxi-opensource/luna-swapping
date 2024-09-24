import request from "../utils/request";

export function getDigitalAvatarList(data) {
  return request({
    url: "/api/digitalAvatar/list",
    method: "GET",
    preventLoading: true,
    loginRequired: true,
    data,
    loginParam: {
      delta: 1,
    },
  });
}

export function removeDigitalAvatar(data) {
  return request({
    url: "/api/digitalAvatar/remove",
    method: "POST",
    preventLoading: true,
    loginRequired: true,
    data,
    loginParam: {
      delta: 1,
    },
  });
}
