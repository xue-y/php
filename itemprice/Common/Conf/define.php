<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-2
 * Time: 下午5:17
 * 公共常量文件 $_SERVER['DOCUMENT_ROOT']
 */
define("DATA_BACK","/Data/Back/"); //  数据备份
define("DATA_TXT","/Data/Txt/"); // 现在正在使用的数据
define("DATA_EXPORT","/Data/Export/"); //导出数据文件暂存位置
define("DATA_EXPORT_U","/Data/Export/User/");//用户导出Excle zip 时生成的数据文件
define("DATA_EXPORT_A","/Data/Export/Admin/");//管理员导出下载 Excle zip 时生成的数据文件
define("DATA_IMPORT","/Data/Import/"); //导入数据文件位置
define("DATA_CACHE","/Data/Cache/"); //临时缓存数据文件
define("DATA_UPTEMP",$_SERVER['DOCUMENT_ROOT']."/Data/Uptemp/"); //临时上传目录
define("DATA_LOG",$_SERVER['DOCUMENT_ROOT']."/Log/");
define("PHP_CON","/Php/Controll/");
define("DATA_GROUP","group"); // 正在使用数据文件文件名
define("DATA_USER","user");
define("DATA_TYPE","type");
define("DATA_PRO","product");
define("DATA_DPRO","dpro");
define("DATA_CACHE_USER","cache_user");
define("DATA_ID_S",1); // id 起始值 user product
define("DE_LIMITER","_");
define("SUCCESS","success");// 成功提示符信息
define("ERROR_CODE",-1);// ajax错误提示符信息
define("SUCCESS_CODE",1);// ajax成功提示符信息
define("PAGE_SIZE",10); //一页的条数
define("PAGE_SHOW",5);// 显示几个页码
define("TOKEN","Superadministrator");// 超级管理员身份标识