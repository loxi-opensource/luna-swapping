import { merge } from "lodash";
import { Axios } from "./axios";
import {
  ContentTypeEnum,
  LunaRequestCodeEnum,
  RequestMethodsEnum,
} from "@/enums/requestEnums";
import type { AxiosHooks } from "./type";
import feedback from "../feedback";
import { AxiosError, type AxiosRequestConfig } from "axios";
import { LUNA_TOKEN_KEY } from "@/enums/cacheEnums";
import cache from "@/utils/cache";

const getLunaToken = () => {
  const token = cache.get(LUNA_TOKEN_KEY);
  return token;
};

// 处理axios的钩子函数
const axiosHooks: AxiosHooks = {
  requestInterceptorsHook(config) {
    const { withToken, isParamsToData } = config.requestOptions;
    const params = config.params || {};
    const headers = config.headers || {};

    // 添加token
    if (withToken) {
      const token = getLunaToken();
      headers.JWTHEADER = token;
    }
    // POST请求下如果无data，则将params视为data
    if (
      isParamsToData &&
      !Reflect.has(config, "data") &&
      config.method?.toUpperCase() === RequestMethodsEnum.POST
    ) {
      config.data = params;
      config.params = {};
    }

    config.headers = headers;
    return config;
  },
  requestInterceptorsCatchHook(err) {
    return err;
  },
  async responseInterceptorsHook(response) {
    const { isTransformResponse, isReturnDefaultResponse } =
      response.config.requestOptions;

    //返回默认响应，当需要获取响应头及其他数据时可使用
    if (isReturnDefaultResponse) {
      return response;
    }
    // 是否需要对数据进行处理
    if (!isTransformResponse) {
      return response.data;
    }
    const { code, data, message, show } = response.data;
    switch (code) {
      case LunaRequestCodeEnum.SUCCESS:
        show && message && feedback.msgSuccess(message);
        return data;
      case LunaRequestCodeEnum.FAIL:
        // "en: Access too frequent | cn: 访问的太频繁了"
        if (message?.indexOf("频繁") !== -1) {
          return data;
        }
        message && feedback.msgError(message);
        return Promise.reject(data);
      case LunaRequestCodeEnum.LOGIN_FAILURE:
        // todo 刷新jwt token
        return Promise.reject("luna token过期");
      default:
        console.log("luna resp unexpected error: ", response.data);
        message && feedback.msgError(message);
        return data;
    }
  },
  responseInterceptorsCatchHook(error) {
    if (error.code !== AxiosError.ERR_CANCELED) {
      error.message && feedback.msgError(error.message);
    }
    return Promise.reject(error);
  },
};

import appConfig from "@/config";
const config = {
  timeout: appConfig.timeout,
  baseUrl: appConfig.lunaBaseUrl,
  urlPrefix: "api",
};

// @ts-ignore
const defaultOptions: AxiosRequestConfig = {
  //接口超时时间
  timeout: config.timeout,
  // 基础接口地址
  baseURL: config.baseUrl,
  //请求头
  headers: { "Content-Type": ContentTypeEnum.JSON },
  // 处理 axios的钩子函数
  axiosHooks: axiosHooks,
  // 每个接口可以单独配置
  requestOptions: {
    // 是否将params视为data参数，仅限post请求
    isParamsToData: true,
    //是否返回默认的响应
    isReturnDefaultResponse: false,
    // 需要对返回数据进行处理
    isTransformResponse: true,
    // 接口拼接地址
    urlPrefix: config.urlPrefix,
    // 忽略重复请求
    ignoreCancelToken: false,
    // 是否携带token
    withToken: true,
    // 开启请求超时重新发起请求请求机制
    isOpenRetry: true,
    // 重新请求次数
    retryCount: 2,
  },
};

function createAxios(opt?: Partial<AxiosRequestConfig>) {
  return new Axios(
    // 深度合并
    merge(defaultOptions, opt || {})
  );
}

export const lunaRequest = createAxios();
