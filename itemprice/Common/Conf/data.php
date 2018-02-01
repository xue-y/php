<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-12-31
 * Time: 下午2:44
 */
//公共配置数据; $_SERVER['DOCUMENT_ROOT'].
return array(
    "DATA_EXT"=>".txt",
    "LIB_EXT"=>".php",
    "HTML_EXT"=>".html",
    "TEM_EXT"=>".php",
    "COMPRESS_EXT"=>".zip",
    "EXCEL_EXT"=>".xls",
    "TEM_DIR"=>$_SERVER['DOCUMENT_ROOT']."/Tem/",
    "VIEW_DIR"=>$_SERVER['DOCUMENT_ROOT']."/View/",
    "HTML_DIR"=>$_SERVER['DOCUMENT_ROOT']."/Html/",
    "DEFAULT_PASS"=>'123456', //默认初始密码
    "COOKIE_TIME"=>7*24*3600, // cookie 存储时间
    "SESSION_TIME"=>10*3600  // session 存储时间
);
