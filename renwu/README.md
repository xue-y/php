## 简介
	ThinkPHP 轻量级PHP开发框架 <br/>
	如果安装目录不是在域名根目录路径需要相应修改<br/>
	项目名称：任务平台<br/>
	以部门划分，可以不同的人分配不同的权限，可以限制部门主管只可修改自己部门的人员<br/>
	配置文件 renwu\Application\Back\Conf\define.php   define('A_S_C',FALSE);//为true<br/>
	也可 系统设置中设置 普通管理员添加其他部门人员--》是<br/>
#### 安装说明文件
	根目录Install/install.php
### 环境平台
	环境版本：PHP5.3以上版本（注意：PHP5.3dev版本和PHP6均不支持）<br/>
#### Liunx 平台：<br/>
	PHP： 5.3.27p1 (cli) (built: Jul 20 2017 12:58:56) <br/>
	Copyright (c) 1997-2013 The PHP Group<br/>
	Zend Engine v2.3.0, Copyright (c) 1998-2013 Zend Technologies<br/>
	nginx version: nginx/1.4.7<br/>
	系统:CentOS release 6.8 /CentOS release 6.5<br/>
	Linux version 2.6.32-642.13.1.el6.x86_64 /Linux version 2.6.32-573.22.1.el6.x86_64 <br/>
#### window平台：<br/>
	Apache/2.4.23 (Win32) OpenSSL/1.0.2j PHP/5.4.45<br/>
	PHP版本（php_version）：	5.4.45<br/>
	Zend版本	2.4.0<br/>
	SQLite3　Ver 3.8.10.2<br/>
	mysql  Ver 14.14 Distrib 5.5.53, for Win32 (AMD64)<br/>
	注：其他版本未测试<br/>
####安装说明
	根目录 install.html 安装文件-----> 安装完成后移动并更名为 /Install/lock.php
	根目录 生成 lock.txt 文件并写入 "lock"
	应用目录 Application\Back\Controller\InstallController.class.php 
	更名为 应用目录 Application\Back\Controller\Lock_Controller.class.php
    安装完成后设置目录权限
		#Application/Back/Cache/ 有写入读取权限 建议 755
		#Application/Back/Conf/ 有写入读取权限 建议 777
		#Application/Runtime/  有写入读取权限 建议 777
		#Application/Common/Conf/ 有写入读取权限 建议 777
		#Install/ 有创建文件权限 建议 755
		#ThinkPhp/ 有读取执行的权限建议 755

####注意事项
	U() liunx 平台会自动生成 .html 后缀页面访问不到，需要从新配置vhost.conf 文件
	nginx 下/usr/local/nginx/conf/vhost/XXX.conf 文件修改路由 -- 根目录 vhost.conf
	TP3 中 U()  $this->redirect() 跳转地址默认会添加html后缀  nginx 环境需要配置 vhost.conf 文件
    ajax 请求php 数据，服务端如果使用exit("数据")返回;如果数据是数字,前端接收不到,echo(数字)前端可以接收到
