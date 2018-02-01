<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-17
 * Time: 上午10:14
 */
require "../../Library/Com.php";
$Dpro=Com::getInstance("Dpro");
if(empty($_POST))
{
    if(isset($_GET["p"]) ||  empty($_GET) || (isset($_GET["p"]) && isset($_GET[$Dpro->g_id_field])))
    {
        $Dpro->index();
    }
    else if(isset($_GET[$Dpro->g_id_field]) && (!isset($_GET["h"])))
    {
        $g_id=$Dpro->get_id($Dpro->g_id_field);
        $Dpro->index($g_id);
    }
}

$get_action=filter_has_var(INPUT_GET,"h"); //----------------------进入页面
if(!empty($_GET) && $get_action==true)
{
    $get=$Dpro->add_slashes($_GET["h"]);
    switch($get)
    {
        case "index":
            $g_id=$Dpro->get_id($Dpro->g_id_field);
            $Dpro->index($g_id);
            break;
        case "edit":
            $u_id=$Dpro->is_requer(INPUT_GET,$Dpro->id_field);
            isset($u_id)?$Dpro->edit($_GET[$Dpro->id_field]): $Dpro->index();
            break;
        case "del":
            $u_id=$Dpro->is_requer(INPUT_GET,$Dpro->id_field);
            isset($u_id)?$Dpro->del($_GET[$Dpro->id_field]): $Dpro->index();
            break;
    }
}
if(isset($_POST["edits"]) && isset($_POST[$Dpro->id_field]) && !empty($_POST[$Dpro->id_field])) //-----进入批量删除页面
{
    $id_arr=array_filter($_POST[$Dpro->id_field]);
    $Dpro->edit($id_arr);
}
if(isset($_POST["dels"]) && isset($_POST[$Dpro->id_field]) && !empty($_POST[$Dpro->id_field])) //-----进入批量删除页面
{
    $id_arr=array_filter($_POST[$Dpro->id_field]);
    $Dpro->del($id_arr);
}