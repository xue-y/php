<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-16
 * Time: 下午4:11
 * 安装说明
 */
#PHP版本要求
//PHP5.3以上版本（注意：PHP5.3dev版本和PHP6均不支持）
#Thinkphp 版本： ThinkPHP3.2.3完整版
#支持的服务器和数据库环境
/*支持Windows/Unix服务器环境
可运行于包括Apache、IIS和nginx在内的多种WEB服务器和模式
支持Mysql、MsSQL、PgSQL、Sqlite、Oracle、Ibase、Mongo以及PDO等多种数据库和连接*/

#模板字体地址 http://www.bootcss.com/p/font-awesome/
#thinkphp模板中 __MODULE__ 自动转为转小写 部署阶段
#安装完成之后请将应用文件夹的公共配置文件注释打开
#安装完成之后请检查 ./Application/Log/admin.contrroll.txt  //错误日志文件
#入口文件 如果是 index.php define('APP_DEBUG',True); 改为false

安装访问根目录下的install.html， 请求的控制器是InstallControll.class.php
安装完成锁定文件是 LockController.class.php
install.html 更改文件为/Install/lock.php;
InstallController.class.php 更改为 Lock_Controller.class.php
根目录下创建 lock.txt 文件
如果需要重装 文件名一一改回就可以

