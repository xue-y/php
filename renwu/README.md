## 简介
	ThinkPHP 轻量级PHP开发框架   
	如果安装目录不是在域名根目录路径需要相应修改  
	项目名称：任务平台 
	以部门划分，可以不同的人分配不同的权限，可以限制部门主管只可修改自己部门的人员  
	配置文件 renwu\Application\Back\Conf\define.php   define('A_S_C',FALSE);//为true  
	也可 系统设置中设置 普通管理员添加其他部门人员--》是  
    管理员发布过自己的任务后，其他人员登录后会看到新任务个数，当用户执行过任务，任务个数减一  
    缺点：用户执行过任务后，提交任务的人是否通过验证对于执行任务的人没有反馈提示，如需查看可进入任务列表     
    系统用于公司内部各部门问题---统计分析      
	
#### 项目功能：     
	管理员管理、RBAC权限管理、任务管理（发布、审核、反馈、统计）、缓存清理、STMP邮箱绑定、密码找回、部门划分、个人设置 、登录信息加密  
  
#### 安装说明文件
	根目录Install/install.php  
	
#### 环境平台
	环境版本：PHP5.3以上版本（注意：PHP5.3dev版本和PHP6均不支持）  
	
#### Liunx 平台：  
	PHP： 5.3.27p1 (cli) (built: Jul 20 2017 12:58:56)   
	Copyright (c) 1997-2013 The PHP Group  
	Zend Engine v2.3.0, Copyright (c) 1998-2013 Zend Technologies   
	nginx version: nginx/1.4.7   
	系统:CentOS release 6.8 /CentOS release 6.5  
	Linux version 2.6.32-642.13.1.el6.x86_64 /Linux version 2.6.32-573.22.1.el6.x86_64  
	
#### window平台：  
	Apache/2.4.23 (Win32) OpenSSL/1.0.2j PHP/5.4.45  
	PHP版本（php_version）：	5.4.45   
	Zend版本	2.4.0  
	SQLite3　Ver 3.8.10.2  
	mysql  Ver 14.14 Distrib 5.5.53, for Win32 (AMD64)   
	注：其他版本未测试  
	
#### 安装说明
    #模板字体地址 http://www.bootcss.com/p/font-awesome/
    #thinkphp模板中 __MODULE__ 自动转为转小写 部署阶段
    #安装完成之后请将应用文件夹的公共配置文件注释打开
    #安装完成之后请检查 ./Application/Log/admin.contrroll.txt  //错误日志文件
    #入口文件 如果是 index.php define('APP_DEBUG',True); 改为false
	根目录 install.html 安装文件-----> 安装完成后移动并更名为 /Install/lock.php
	根目录 生成 lock.txt 文件并写入 "lock"
	应用目录 Application\Back\Controller\InstallController.class.php 
	更名为 应用目录 Application\Back\Controller\Lock_Controller.class.php
	vhosts.conf nginx环境下的配置pathinfo模式

    安装完成后设置目录权限
		#Application/Back/Cache/ 有写入读取权限 建议 755
		#Application/Back/Conf/ 有写入读取权限 建议 777
		#Application/Runtime/  有写入读取权限 建议 777
		#Application/Common/Conf/ 有写入读取权限 建议 777
		#Install/ 有创建文件权限 建议 755
		#ThinkPhp/ 有读取执行的权限建议 755
		#/Public/kindeditor-4.1.10/attached 有写入创建读取权限 建议777

#### 注意事项
	U() liunx 平台会自动生成 .html 后缀页面访问不到，需要从新配置vhost.conf 文件
	nginx 下/usr/local/nginx/conf/vhost/XXX.conf 文件修改路由 -- 根目录 vhost.conf
	TP3 中 U()  $this->redirect() 跳转地址默认会添加html后缀  nginx 环境需要配置 vhost.conf 文件
    ajax 请求php 数据，服务端如果使用exit("数据")返回;如果数据是数字,前端接收不到,echo(数字)前端可以接收到
    用户登录信息使用 cookie 方式存储，任务信息 使用 session 方式，临时存储使用 TP框架 S() 方式缓存
    字段加密使用Think 类
	
#### 系统附件
	根目录下 attached 文件夹 用户添加附件（图片存放位置）, 文件夹按照年月命名，文件按照年月日时分秒命名
	文件清理 系统设置--->图片清理 操作文件名：Application\Back\Controller\SyssetController.class.php  oldf()函数
	上传插件 Public\kindeditor-4.1.10 官网地址 http://kindeditor.net/demo.php
	数据库文件 renwu.sql---用于查看
	
	
