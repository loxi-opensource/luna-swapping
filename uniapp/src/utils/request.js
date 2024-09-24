import { defaultLoadingTitle } from "../common/variable.js";

const baseUrl = "https://test.luna-api.duimixinyifu.com"; // 接口基础 URL
export { baseUrl };
// 添加请求拦截器
uni.addInterceptor("request", {
  invoke(args) {
    !args.preventLoading &&
      uni.showLoading({ title: args.loadingToastTips || defaultLoadingTitle });
    // console.log(args, 'invoke')
    if (args.loginRequired) {
      const token = uni.getStorageSync("token");

      // 如果存在 token，则将其添加到请求头中
      if (token) {
        args.header = {
          ...args.header,
          token: `${token}`,
        };
      } else {
        requireLogin(args.loginParam);
        return false;
      }
    }
  },
  success() {
    uni.hideLoading();
  },
  fail(err) {
    uni.hideLoading();
    console.log("interceptor on fail", err);
    uni.showToast({
      title: "请求失败，请检查网络设置",
      icon: "none",
    });
  },
  complete(res) {
    uni.hideLoading();
    //console.log('interceptor-complete', res)
    if (res.statusCode && res.statusCode == 500) {
      if (res?.data?.msg.indexOf("访问的太频繁了") !== -1) {
        console.log(res, "skip 500 toast");
        return;
      }
      // 后端崩溃时前端展示兜底文案
      uni.showToast({
        // title: res?.data?.msg || '服务器开小差了',
        title: "服务器开小差了",
        icon: "none",
      });
    }
  },
});

// 封装请求方法
export default function request(options) {
  return new Promise((resolve, reject) => {
    uni.request({
      url: `${baseUrl}${options.url}`,
      method: options.method || "GET",
      data: options.data || {},
      header: options.header || {},
      loginParam: options.loginParam,
      loginRequired:
        options.loginRequired == undefined ? true : options.loginRequired, // 默认要求登录
      preventLoading:
        options.preventLoading == undefined ? false : options.preventLoading,
      loadingToastTips:
        options.loadingToastTips == undefined
          ? defaultLoadingTitle
          : options.loadingToastTips,
      success: (res) => {
        if (res.data.code === -1) {
          // 如果返回状态码为 -1，则提示用户重新登录
          requireLogin({
            ...options.loginParam,
            toastMsg: "登录态已失效，请重新登录",
          });
        } else {
          resolve(res.data);
        }
      },
      fail: (err) => {
        reject(err);
      },
    });
  });
}

export function requireLogin(
  { toastMsg, disabledToast, redirect = "", delta = 0, redirectTab = "" } = {
    toastMsg: "请先登录",
    disabledToast: false,
    redirect: "",
    delta: 0,
    redirectTab: "",
  }
) {
  const routerFN = redirect || delta ? uni.navigateTo : uni.reLaunch;
  if (disabledToast) {
    routerFN({
      url:
        "/pages/login/login?redirect=" +
        encodeURIComponent(redirect) +
        "&delta=" +
        encodeURIComponent(delta) +
        "&redirectTab=" +
        encodeURIComponent(redirectTab),
    });
  } else {
    uni.showToast({
      title: toastMsg,
      icon: "none",
      duration: 3000,
      complete() {
        routerFN({
          url:
            "/pages/login/login?redirect=" +
            encodeURIComponent(redirect) +
            "&delta=" +
            encodeURIComponent(delta) +
            "&redirectTab=" +
            encodeURIComponent(redirectTab),
        });
      },
    });
  }
}
