
患者信息管理系统
===============

###系统主要框架：
 + bootstrap 3.0
 + thinkphp 5.1
 + jquery 3.4.1
 + layui

###系统插件包括：
 + 使用Echarts图形报表对数据进行统计展示
 + 使用bootstrap inputfile插件进行文件图片上传
   
###系统功能包括：

 + 团队简介
   - 登录首页
 + 患者信息
   - 患者信息录入界面入口
   - 患者信息的增、删、改、查
 + 统计报表
   - 基本信息表
      - 图形列表关联查询患者相关数据
   - 其他报表--待开发
 + 用户中心
   - 个人资料修改
   - 登录密码修改
 + 后台管理
   - 用户管理
      - 对系统现有用户的增、删、改、查
      - 用户权限及医生相关信息设置

###患者信息录入包括：

 + 基本信息
 + 既往病史
 + 症状体征
 + 术前检查
 + 诱导治疗
 + 手术信息
 + 术后并发症
 + 术后病理
 + 辅助治疗
 + 末次随访
 + 复诊信息


## 安装

使用composer安装

~~~
composer create-project topthink/think tp
~~~

启动服务

~~~
cd tp
php think run
~~~

然后就可以在浏览器中访问

~~~
http://localhost:8000
~~~

更新框架
~~~
composer update topthink/framework
~~~


## 在线手册

+ [完全开发手册](https://www.kancloud.cn/manual/thinkphp5_1/content)
+ [升级指导](https://www.kancloud.cn/manual/thinkphp5_1/354155) 

## 目录结构

初始的目录结构如下：


## 命名规范

`ThinkPHP5`遵循PSR-2命名规范和PSR-4自动加载规范，并且注意如下规范：

### 目录和文件

*   目录不强制规范，驼峰和小写+下划线模式均支持；
*   类库、函数文件统一以`.php`为后缀；
*   类的文件名均以命名空间定义，并且命名空间的路径和类库文件所在路径一致；
*   类名和类文件名保持一致，统一采用驼峰法命名（首字母大写）；

### 函数和类、属性命名

*   类的命名采用驼峰法，并且首字母大写，例如 `User`、`UserType`，默认不需要添加后缀，例如`UserController`应该直接命名为`User`；
*   函数的命名使用小写字母和下划线（小写字母开头）的方式，例如 `get_client_ip`；
*   方法的命名使用驼峰法，并且首字母小写，例如 `getUserName`；
*   属性的命名使用驼峰法，并且首字母小写，例如 `tableName`、`instance`；
*   以双下划线“__”打头的函数或方法作为魔法方法，例如 `__call` 和 `__autoload`；

### 常量和配置

*   常量以大写字母和下划线命名，例如 `APP_PATH`和 `THINK_PATH`；
*   配置参数以小写字母和下划线命名，例如 `url_route_on` 和`url_convert`；

### 数据表和字段

*   数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 `think_user` 表和 `user_name`字段，不建议使用驼峰和中文作为数据表字段命名。

## 参与开发

请参阅 [ThinkPHP5 核心框架包](https://github.com/top-think/framework)。

## 版权信息

ThinkPHP遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2020-2021 by youwei network ltd (https://www.ailaiyun.com)

All rights reserved。


