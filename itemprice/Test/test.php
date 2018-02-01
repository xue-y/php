<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-12-30
 * Time: 下午5:07
 */


/*require "../Library/Com.php";

$class_p=Com::getInstance("Clear");
$dir=$_SERVER['DOCUMENT_ROOT'].DATA_TXT;

$class_p->clear_data($dir,"group","user");*/
header("content-type:image/gif");
$code=new code();
$class_p->c_img(70,30,14,4);


