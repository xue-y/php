<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-9
 * Time: 下午5:18
 */
require "../../Library/Com.php";
$User=Com::getInstance("User");

if(empty($_POST))
{
    if(isset($_GET["p"]) || empty($_GET) || (isset($_GET["p"]) && isset($_GET[$User->g_id_field])))
    {
        $User->index();
    }
    else if(isset($_GET[$User->g_id_field]) && (!isset($_GET["h"])))
    {
        $g_id=$User->get_id($User->g_id_field);
        $User->index($g_id);
    }
}
$post_action=filter_has_var(INPUT_POST,"action"); //---------------------------------- 执行页面
if(!empty($_POST) && $post_action==true)
{
    $post=$User->add_slashes($_POST["action"]);
    switch($post)
    {
        case "add_exct": // 执行添加
            $User->add_exct();
            break;
        case "edit_exct":// 执行修改
            $User->edit_exct();
            break;
    }
}

$get_action=filter_has_var(INPUT_GET,"h"); //----------------------进入页面
if(!empty($_GET) && $get_action==true)
{
    $get=$User->add_slashes($_GET["h"]);
    switch($get)
    {
        case "index":
            $g_id=$User->get_id($User->g_id_field);
            $User->index($g_id);
            break;
        case "add": // 进入添加页面
            $g_id=$User->get_id($User->g_id_field);
            $User->add($g_id);
            break;
        case "edit": // 进入修改页面
            $u_id=$User->is_requer(INPUT_GET,$User->id_field);
            isset($u_id)?$User->edit($_GET[$User->id_field]): $User->index();
            break;
        case "del":
            $u_id=$User->is_requer(INPUT_GET,$User->id_field);
            isset($u_id)?$User->del($_GET[$User->id_field]): $User->index();
            break;
        case "edit_exct":
            $User->edit_exct(); //执行修改  ---普通用户
            break;
    }
}

if(isset($_POST["dels"]) && isset($_POST[$User->id_field]) && !empty($_POST[$User->id_field])) //-----进入批量删除页面
{
    $id_array=array_filter(array_unique($_POST[$User->id_field]));
    $User->del($id_array);
}