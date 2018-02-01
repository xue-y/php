<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-26
 * Time: 下午5:06
 * 用户登录页面
 */

require "../../Library/Com.php";
$Login=Com::getInstance("Login");
if(isset($_GET["action"]) && $_GET["action"]=="out")
{
    $Login->out_login(); // 退出登录
}
else if(isset($_POST) && !empty($_POST))
 {
    $Login->exce_login(); //  执行登录
 }else
 {
    $Login->is_login(); // 验证是否登录
 }







