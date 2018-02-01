<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-19
 * Time: 上午11:30
 * 前台用户导出页面
 */
require "../../Library/Com.php";
$post_action=filter_has_var(INPUT_POST,"down"); // 判定传参
if(!isset($post_action))
{
    echo ERROR_CODE;
    exit;
}else
{
    //前台用户 导出数据
    $Price=Com::getInstance("Price");
    // 取得用户身份标识
    $g_u=explode(DE_LIMITER,$_SESSION["id"]);
    $biaoshi=$Price->price_she_biaoshi($g_u[0],$_SESSION["token"],PHP_CON."Login.php");

    $all_data=$Price->price_data($biaoshi);
    $down_n=$Price->add_slashes($_POST["down"]);
    $Down=Com::getInstance("Down");
    switch($down_n)
    {
        case "excel":
             $Down->down_excel($all_data,$biaoshi);
            break;
        case "html":
             $Down->down_html($all_data,'down_user',$biaoshi);
            break;
        default:
              echo ERROR_CODE;
            break;
    }
}




