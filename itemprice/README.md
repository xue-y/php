#### 项目介绍：        
	 用途： 根据不同的用户分组，不同的用户组员看到的项目价格，价格最新单位是元      
	 模板字体地址 http://www.bootcss.com/p/font-awesome/            
	 上传附件：  文件名是以日期时间中间横线划分命名         
	 所有文件编号为 utf8 编码          
	 .htaccess 仅支持 apache，nginx 系统需要转换           
	 前台与后台使用了生成静态页面          

#### 功能模块   
	登录、退出登录
	后台首页、前台首页	
	数据清理（清除一些上传下载的文件及一些数据文件）
	用户组管理
	用户管理
	项目分类
	项目管理
	数据管理
		|-- 更新缓存
		|-- 数据备份(支持zip压缩、Excel格式)
		|-- 数据导入(支持解压zip、Excel格式)
		|-- 数据还原
	项目回收站
	日志管理
   
#### 文件目录结构  
	Common 公共文件   
	    |-----Conf/ 配置文件
	Data 数据文件------// 必须有写权限
	    |---- Txt/ 现在使用的数据文件
	    |---- Back/ 备份数据文件
	    |---- Export/ 导出数据文件暂存位置
	        |--- User/ 用户导出Excle时生成的数据文件
	        |--- Admin/ 管理员导出时生成的数据文件
	    |---- Import/ 导入数据文件位置
	    |---- Cache/ 前台用户导出数据缓存数据
	Library 库文件<br/>
	    |-----Excel/ 读取excel 表格 项目中没有用的--可以单独使用
	    |-----Validate/ 验证类
	    |-----Down 下载类
	    |-----Tem 模板类
	    |-----Upload 上传类
	    |-----Excel 模板类
	Log 日志文件
	    |-----error.txt 错误日志
	Php 程序执行文件
	    |-----Class/ 类文件
	    |-----Controll/ 执行操作文件	
	Static 静态文件
	    |---- js/
	    |---- css/
	    |---- images/
	View 后台静态文件
	Tem 后台操作模板文件

#### 数据   
	用户组数据 group
		组名
	用户人员数据  user_组id
		组id_人员id  姓名 密码
	项目数据  pro_类型id
		 项目名称[名称建议唯一]  标准价格 市场价格 上架时间 备注
	项目类型数据 type
		类型名称[名称建议唯一]
	删除项目数据 dpro_类型id
		项目id 项目名称 项目类型
	用户组数据文件命名为 group.txt  
	组成员数据文件命名为 group_编号.txt  
	项目数据文件为 product_编号.txt  
	删除的项目数据为 dpro_编号.txt  

#### 环境要求
	window平台：
	php 5.6.27
	Apache: Apache/2.4.23 (Win32) 
	SQLite3　Ver 3.8.10.2
	服务器解译引擎	Apache/2.4.23 (Win32) OpenSSL/1.0.2j PHP/5.4.45 建议php版本 5.4.45-nts 或 5.4.45+
	报一下错误：Fatal error: Cannot redeclare class Com in D:\phpStudy\WWW\cms\Library\Com.php on line 11

	Liunx 平台：
	PHP： 5.3.27p1 (cli) (built: Jul 19 2017 20:09:46) 
	Copyright (c) 1997-2013 The PHP Group
	Zend Engine v2.3.0, Copyright (c) 1998-2013 Zend Technologies
	nginx version: nginx/1.4.7
	系统：CentOS release 6.5
	Linux version 2.6.32-573.22.1.el6.x86_64 

	其它版本未测试   

#### 登录账号：
	 测试登录账号： 0_1  密码：123456   
	 登录账号为：  组id_管理员id   
	 其他用户可以修改删除    
