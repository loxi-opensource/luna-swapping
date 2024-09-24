# LunaAI管理后台部署文档

## 0. 介绍

技术栈：vue3 + typescript + ElementUI Plus

## 1. 环境要求

- Node.js: 开发编译使用Node版本号需要不低于18.0

## 2. 安装依赖

进入项目根目录，执行以下命令：

```shell
yarn install
```

## 3. 配置后端域名

- 复制项目根目录`.env.development.example`文件为`.env.development`，把后端域名填写到对应位置
- 复制项目根目录`.env.production.example`文件为`.env.production`，把后端域名填写到对应位置

## 4. 运行 & 编译

执行以下命令：

```shell
yarn dev 
```

- 浏览器访问：http://localhost:5173/admin/

- 填写管理员账号/密码是 loxi / 123456

- 登录进入后台，如果能进入后台则运行成功

执行以下命令编译打包：

```shell
yarn build
```

## 5. 部署

- 将`dist`文件夹上传到服务器，重命名为`admin`
- 配置Nginx指向`admin`目录即可
- 访问域名+后缀`/admin`即可进入打开后台登录页面

