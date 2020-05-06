## 下载代码

```
git clone git@github.com/curder/laravel-todo.git
```                                             

## composer 安装组件

```
composer install -vvv
```                  
> 如果不存在 composer 命令。通过链接 [https://getcomposer.org/download/](https://getcomposer.org/download/) 下载并安装 composer 命令。

## 环境文件
在项目的根目录中附带一个 `.env.example` 文件，请将其拷贝重命名为 `.env`。可以使用如下命令完成操作

```
cp .env.example .env
```                                                                                       
## artisan命令
设置 Laravel 在进行加密时使用的密钥。

```
php artisan key:generate
```
## 6. 创建数据库

在MySQL数据库上创建数据库，然后更新项目根目录下的`.env`文件上如下的相关行：

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```
根据当前环境，更新上面的行以完成数据库相关设置。

## 接口说明

| 方法 | 地址 | 返回状态码 | 简单说明 |
| ---- | ---- | ---- | ---- |
| GET | `/api/todos` | 200 | 获取todos列表 |
| POST | `/api/todos` | 201 | 新增todo |
| PATCH、PUT | `/api/todos/check-all` | 200 | 选中或反选所有todos |
| PATCH、PUT| `/api/todos/{todoID}` | 200 | 更新todo |
| DELETE | `/api/todos/delete-completed` | 200 | 删除已完成的todos | 
| DELETE | `/api/todos/{todoID}` | 200 | 删除todo |
|  GET | `/api/user` | 200 | 获取用户 |
| POST | `/api/login` | 200 | 登录 |
| POST | `/api/logout` | 200 | 退出登录 |
| POST | `/api/register` | 200 | 注册 |
| POST | `oauth/token` | 200 | 获取access_token |
| DELETE | `oauth/tokens/{token_id}` | 200 | 回收token |                       
