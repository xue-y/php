<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-5
 * Time: 下午1:10
 */
require "../../Library/Com.php";
$Pro=Com::getInstance("Pro");
$file=pathinfo(__FILE__,PATHINFO_FILENAME);
$file=strtolower($file);
require $Pro::$conf_data["VIEW_DIR"].$file.$Pro::$conf_data["HTML_EXT"];