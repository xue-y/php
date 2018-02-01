<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-5
 * Time: 下午3:13
 */

/*require "../../Library/Com.php";
$strTimeToString = "000111222334455556666667";
$strWenhou = array('夜深了，','凌晨了，','早上好！','上午好！','中午好！','下午好！','晚上好！','夜深了，');
$data["user"]="admin";
$data["time"]=$strWenhou[(int)$strTimeToString[(int)date('G',time())]];
$Tem=Com::getInstance("Tem");
$Tem->write_html("index",$data,true); //生成静态页面 无法判断用户是否登录*/
require "../../Library/Com.php";
$Pro=Com::getInstance("Pro");
$file=pathinfo(__FILE__,PATHINFO_FILENAME);
$file=strtolower($file);
require $Pro::$conf_data["VIEW_DIR"].$file.$Pro::$conf_data["HTML_EXT"]; // 更新页面使用数据更新缓存--后台页面
