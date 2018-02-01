<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-24
 * Time: 下午5:07 日志管理
 */
require "../../Library/Com.php";
$Log=Com::getInstance("Log");

$get_action=filter_has_var(INPUT_GET,"h");
if(!empty($get_action) && $get_action==true)
{
    $get=$Log->add_slashes($_GET["h"]);
    $n=$Log->field_n(DATA_LOG); //-------------------判断文件名
    if(!isset($n))
    {
        $Log->index(); //-------------------日志列表
    }
    switch($get)
    {
        case "del":
            $Log->file_del(DATA_LOG,$n); // 删除单个
            break;
        case "sel":
            $Log->log_sel(DATA_LOG,$n); // 查看日志文件
            break;
    }
}
else if(isset($_POST["dels"])) // 删除文件--批量
{
    $Log->file_dels(DATA_LOG);
    exit;
}else
{
    $Log->index();
}



