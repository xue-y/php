<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-20
 * Time: 上午9:27
 * 使用用户访问动态页面
 */
require "../../Library/Com.php";
// 判定身份-- s_price 市场价格  b_price 标准价格

$Price=Com::getInstance("Price");
$Price->index();

