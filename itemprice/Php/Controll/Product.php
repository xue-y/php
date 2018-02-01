<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-9
 * Time: 下午5:18
 */
require "../../Library/Com.php";
$Product=Com::getInstance("Product");

if(empty($_POST))
{
  if(isset($_GET["p"]) ||  empty($_GET) || (isset($_GET["p"]) && isset($_GET[$Product->g_id_field])))
    {
        $Product->index();
    }
    else if(isset($_GET[$Product->g_id_field]) && (!isset($_GET["h"])))
    {
        $g_id=$Product->get_id($Product->g_id_field);
        $Product->index($g_id);
    }
}
$post_action=filter_has_var(INPUT_POST,"action"); //---------------------------------- 执行页面
if(!empty($_POST) && $post_action==true)
{
    $post=$Product->add_slashes($_POST["action"]);
    switch($post)
    {
        case "add_exct": // 执行添加
            $Product->add_exct();
            break;
        case "edit_exct":// 执行修改
            $Product->edit_exct();
            break;
    }
}

$get_action=filter_has_var(INPUT_GET,"h"); //----------------------进入页面
if(!empty($_GET) && $get_action==true)
{
    $get=$Product->add_slashes($_GET["h"]);
    switch($get)
    {
        case "index":
            $g_id=$Product->get_id($Product->g_id_field);
            $Product->index($g_id);
            break;
        case "add": // 进入添加页面
            $g_id=$Product->get_id($Product->g_id_field);
            $Product->add($g_id);
            break;
        case "edit": // 进入修改页面
            $u_id=$Product->is_requer(INPUT_GET,$Product->id_field);
            isset($u_id)?$Product->edit($_GET[$Product->id_field]): $Product->index();
            break;
        case "del":
            $u_id=$Product->is_requer(INPUT_GET,$Product->id_field);
            isset($u_id)?$Product->del($_GET[$Product->id_field]): $Product->index();
            break;
    }
}

if(isset($_POST["dels"]) && isset($_POST[$Product->id_field]) && !empty($_POST[$Product->id_field])) //-----进入批量删除页面
{
    $id_arr=array_filter($_POST[$Product->id_field]);
    $Product->del($id_arr);
}