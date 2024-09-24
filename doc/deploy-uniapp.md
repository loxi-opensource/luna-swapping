# LunaAI小程序部署文档

## 0. 介绍

技术栈：uniapp + vue3

## 1. 环境要求

- Node.js: 开发编译使用Node版本号需要不低于18.0

## 2. 安装依赖

进入项目根目录，执行以下命令：

```shell
yarn install
```

## 3. 配置后端域名

修改`src/utils/request.js`文件baseUrl为服务端接口地址

## 4. 运行 & 编译

### 使用命令行构建

执行以下命令：

```shell
yarn dev:mp-weixin
```

### 使用Hbuilder构建

1. 使用Hbuilder打开项目
2. 点击运行按钮，选择微信小程序

