## 简介
ThinkPHP 轻量级PHP开发框架<br/> 
安装目录 Install 以及说明文件<br/>
如果安装目录不是在域名根目录路径需要相应修改<br/>
项目名称：任务平台<br/>
以部门划分，可以不同的人分配不同的权限，可以限制部门主管只可操作（增删改查）自己部门的人员<br/>
配置文件 renwu\Application\Back\Conf\define.php   define('A_S_C',FALSE);//为true<br/>
也可 系统设置中设置 普通管理员添加其他部门人员--》是<br/>
安装请请手动创建数据库，数据库仅支持mysql(sql语句针对mysql)<br/>
安装文件install.html 安装完成之后 更名为 /Install/lock.php<br/>
安装控制器文件 InstllController.class.php 更名为同目录下的Lock_Controller.class.php
