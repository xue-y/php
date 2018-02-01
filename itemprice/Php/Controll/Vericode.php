<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-26
 * Time: 下午5:12
 * 生成验证码
 */
header("content-type:image/gif");
require "../../Library/VeriCode/Code.php";
$code=new Code();
$code->c_img(100,43,18,4);
