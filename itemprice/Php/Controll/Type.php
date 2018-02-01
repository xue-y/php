<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-3
 * Time: 下午4:08
 */
require "../../Library/Com.php";
$Type=Com::getInstance("Type");

if(empty($_POST))
{
    if(isset($_GET["p"]) || empty($_GET))
        $Type->index();
}
$post_action=filter_has_var(INPUT_POST,"action"); //---------------------------------- 执行页面
if(!empty($_POST) && $post_action==true)
{
    $post=$Type->add_slashes($_POST["action"]);
    switch($post)
    {
        case "add_exct": // 执行添加
            $Type->add_exct();
            break;
        case "edit_exct":// 执行修改
            $Type->edit_exct();
            break;
    }
}
$get_action=filter_has_var(INPUT_GET,"h"); //----------------------进入页面
if(!empty($_GET) && $get_action==true)
{
    $get=$Type->add_slashes($_GET["h"]);
    switch($get)
    {
        case "add": // 进入添加页面
          $Type->add();
            break;
        case "edit": // 进入修改单个页面
           $id=$Type->get_id("id");
           isset($id)?$Type->edit($id): $Type->index();
           break;
        case "del":
            $id=$Type->get_id("id");
            isset($id)?$Type->del($id): $Type->index();
            break;
    }
}
if(isset($_POST["edits"]) && isset($_POST["id"]) && !empty($_POST["id"])) // ------进入批量修改页面
{
    $id_array=array_filter(array_unique($_POST["id"]));
    $Type->edit($id_array);
}
if(isset($_POST["dels"]) && isset($_POST["id"]) && !empty($_POST["id"])) //-----进入批量删除页面
{
    $id_array=array_filter(array_unique($_POST["id"]));
    $Type->del($id_array);
}
