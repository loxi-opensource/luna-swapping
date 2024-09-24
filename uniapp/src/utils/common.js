import { getUserInfo } from "../api/user";
import { requireLogin } from "./request";

const storageKey = "drawing_draft";

export function saveDrawingDraft(obj) {
  let payload = uni.getStorageSync(storageKey);
  if (payload) {
    payload = JSON.parse(payload);
  } else {
    payload = {};
  }
  console.log(payload, obj);
  for (const key in obj) {
    payload[key] = obj[key];
  }
  payload = JSON.stringify(payload);
  uni.setStorageSync(storageKey, payload);
}

export function getDrawingDraft(key) {
  let payload = uni.getStorageSync(storageKey);
  if (payload) {
    payload = JSON.parse(payload);
  } else {
    payload = {};
  }
  if (key) {
    return payload[key] || null;
  }
  return payload;
}

export async function checkLogin(loginParam) {
  const token = uni.getStorageSync("token");
  let res;
  if (token) {
    res = await getUserInfo(loginParam);
  } else {
    requireLogin(loginParam);
  }
  return res && res.code == 1;
}

// 保存图片到本地相册
export async function saveImages(images) {
  const promises = images.map((image) => {
    const res = uni.getSystemInfoSync() || {};
    const { osName } = res;
    if (osName == "ios") {
      // #ifdef MP-WEIXIN
      return new Promise((resolve, reject) => {
        uni.downloadFile({
          url: image,
          success(res) {
            if (res.statusCode === 200) {
              uni.saveImageToPhotosAlbum({
                filePath: res.tempFilePath,
                success() {
                  resolve();
                },
                fail(error) {
                  reject(error);
                },
              });
            } else {
              console.log("下载失败", res);
              reject(new Error("下载失败"));
            }
          },
          fail(error) {
            reject(error);
          },
        });
      });
      // #endif
    }
    // 微信小程序安卓端保存图片兼容处理
    return new Promise((resolve, reject) => {
      uni.getImageInfo({
        src: image,
        success: function () {
          // filePath = wx.env.USER_DATA_PATH + "/" + new Date().valueOf() + '.' + e.type;
          let filePath =
            wx.env.USER_DATA_PATH + "/" + new Date().valueOf() + ".png";
          console.log(filePath, "filepath");
          uni.downloadFile({
            url: image,
            filePath: filePath,
            success(res) {
              // const filePath = res.tempFilePath + "file.png"
              console.log(filePath);
              if (res.statusCode === 200) {
                uni.saveImageToPhotosAlbum({
                  filePath: filePath,
                  success() {
                    resolve();
                  },
                  fail(error) {
                    reject(error);
                  },
                });
              } else {
                console.log("下载失败");
                reject(new Error("下载失败"));
              }
            },
            fail(error) {
              console.log("下载失败 fail: ", error);
              reject(error);
            },
          });
        },
        fail(error) {
          console.log("下载失败 fail: ", error);
          reject(error);
        },
      });
    });
  });

  try {
    await Promise.all(promises);
    uni.showToast({
      title: "保存成功",
      icon: "success",
    });
  } catch (error) {
    console.log("保存失败", error);
    uni.showToast({
      title: "保存失败",
      icon: "none",
      duration: 2000,
    });
  }
}

// 请求授权然后下载保存图片
export function requestAuthThenSaveImages(images) {
  // 请求用户授权访问相册
  let scope = "scope.writePhotosAlbum";
  // #ifdef MP-TOUTIAO
  scope = "scope.album";
  // #endif

  return new Promise((resolve, reject) => {
    uni.authorize({
      scope: scope,
      async success() {
        console.log("授权成功");
        uni.showLoading({
          title: "保存中，请稍候",
        });
        await saveImages(images);
      },
      fail(error) {
        console.log("授权失败", error);
        uni.hideLoading();
        uni.showToast({
          icon: "error",
          title: "请授权访问相册",
        });
        reject("fail");
        // 在授权失败时可以给出相应的提示或处理逻辑
      },
    });
  });
}

export const setTopNavBar2DarkTheme = () => {
  uni.setNavigationBarColor({
    frontColor: "#ffffff",
    backgroundColor: "#111111",
  });
};

export const getMediumFontWeight = (osName) => {
  if (osName == "android") {
    return {
      "font-weight": 700,
    };
  }
  return {};
};

// 防止处理多次点击
export function debounceClick(fn, delay = 1000) {
  let isAlreadyClick = false;
  return function () {
    if (isAlreadyClick) return;
    fn.apply(this, arguments);
    isAlreadyClick = true;
    setTimeout(() => {
      isAlreadyClick = false;
    }, delay);
  };
}

export const replaceDomain = (url, newDomain) => {
  if (newDomain.startsWith("http://") || newDomain.startsWith("https://")) {
    return url.replace(/https?:\/\/[^/]+/, newDomain);
  } else {
    return url.replace(/(https?:\/\/)[^/]+/, "$1" + newDomain);
  }
};
