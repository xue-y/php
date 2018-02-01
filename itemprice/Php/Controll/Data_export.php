<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-23
 * Time: 下午1:07
 * 数据管理----导出、还原数据
 */
require "../../Library/Com.php";
$Data=Com::getInstance("Data");
//---------------------------------------post
if(isset($_POST["export_edits"])) // 还原数据--批量
{
    $Data->export_edits();
    exit;
}
if(isset($_POST["export_txt"])) // 下载文件zip txt--批量
{
    $Data->export_txt();
    exit;
}
if(isset($_POST["export_excel"])) // 下载文件zip--批量
{
    $Data->export_excel();
    exit;
}
if(isset($_POST["export_dels"])) // 删除文件--批量
{
    $Data->file_dels($_SERVER['DOCUMENT_ROOT'].DATA_BACK);
    exit;
}
//------------------------------------------get
$get_action=filter_has_var(INPUT_GET,"h");
if(!empty($get_action) && $get_action==true)
{
    $get=$Data->add_slashes($_GET["h"]);
    $dir=$_SERVER['DOCUMENT_ROOT'].DATA_BACK;
    $n=$Data->field_n($dir); //-------------------判断文件名称
    if(!isset($n))
    {
        $Data->export(); //导出数据操作
    }
    switch($get)
    {
        case "del":
            $Data->file_del($dir,$n); // 删除单个
            break;
        case "down":
            $Data->export_down($n); // 导出 下载单个文件
            break;
        case "edit":
            $Data->export_edit($n);// 还原单个
            break;
    }
}else
{
    $Data->export(); //导出数据操作
}
