const config = {
    terminal: 1, //终端
    title: '后台管理系统', //网站默认标题
    version: '2.5.0', //版本号
    baseUrl: `${import.meta.env.VITE_APP_BASE_URL || ''}/`, //请求接口域名
    urlPrefix: 'adminapi', //请求默认前缀
    timeout: 10 * 1000, //请求超时时长
    // Luna算法API接口地址。默认无需改动
    lunaBaseUrl: 'https://prod.luna.aws.iartai.com',
    // Luna算法上传图片同步OSS地址。默认无需改动
    lunaOssBaseUrl: 'https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com'
}

export default config
