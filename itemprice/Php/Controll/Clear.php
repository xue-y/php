<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-26
 * Time: 下午2:03
 * 清理文件 ---Export  Uptemp  用于自动删除文件失败的处理
 */
require "../../Library/Com.php";
$Clear=Com::getInstance("Clear");

$export_user=$_SERVER['DOCUMENT_ROOT'].DATA_EXPORT_U;
$export_admin=$_SERVER['DOCUMENT_ROOT'].DATA_EXPORT_A;
$data_txt=$_SERVER['DOCUMENT_ROOT'].DATA_TXT;
$info=array();

/*1. 用户组与用户_用户组id文件是否一一对应
2. 项目类型与项目_项目类型id 是否一一对应
3. 项目类型与项目回收站_项目类型id 是否一一对应
*/
$info["u_data"]=$Clear->clear_data($data_txt,DATA_GROUP,DATA_USER);
$info["p_data"]=$Clear->clear_data($data_txt,DATA_TYPE,DATA_PRO);
$info["d_data"]=$Clear->clear_data($data_txt,DATA_TYPE,DATA_DPRO);

// 清空上传下载 临时存放文件夹
$info["user"]=$Clear->clear_file($export_user);
$info["admin"]=$Clear->clear_file($export_admin);
$info["uptemp"]=$Clear->clear_file(DATA_UPTEMP);
//echo json_encode($info);
exit(json_encode($info));


