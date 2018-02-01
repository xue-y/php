<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-23
 * Time: 下午1:07
 * 数据管理----更新缓存
 */
require "../../Library/Com.php";
$Data=Com::getInstance("Data");
if(isset($_POST["cache"]))
{
    $cache=$Data->add_slashes($_POST["cache"]);
    $Data->cache($cache);
}else
{
    $all_data=$Data->cur_pos('Data',"index");
    $requer_file=strtolower(basename(__FILE__));
    $data_url=$Data->menu_data("data_url");
    $tit=$data_url[pathinfo(__FILE__,PATHINFO_FILENAME)];
    require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file;
    exit;
}