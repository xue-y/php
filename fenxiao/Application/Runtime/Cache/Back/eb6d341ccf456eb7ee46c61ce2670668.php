<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>首页-<?php echo ($pos["c"]); ?></title>
    <link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
    <link type="text/css" rel="stylesheet" href="/Public/back/css/admin.css">
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/back/js/pintuer.js"></script>

</head>
<body>
<ul class="bread  clearfix">
    <li><a href="/Back/Index/index" target="right" class="icon-home"> 首页</a></li>
    <li><a href="/Back/Limit/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Limit/index"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--角色列表页面-->
        <style>
            ul{margin-bottom: 20px;}
            h4{padding-left: 40px; padding-top: 20px;}
            h5{padding-left: 60px; margin-bottom: 5px}
            .none{display: none}
            .table{font-size:14px;}
        </style>
<div class="panel">
         <?php foreach($s_l_a[0] as $k=>$v) { echo '<ul><h3>'.$v["n"].'</h3>'; foreach($s_l_a[1] as $kk=>$vv) { if($v["pid"]==$vv["pid"]) echo '<h4 class="clear">'.$vv["n"].'</h4>'; foreach($s_l_a[2] as $vvv) { if(stripos($vvv["execs"],$vv["execs"])!==FALSE && $vvv["pid"]==$v["pid"]) { if(stripos($vvv["execs"],"exec")===FALSE) { echo '<h5 class="float-left w50">'.$vvv["n"].'</h5>';} } } } echo '<hr/></ul>'; } ?>
     <!-- </tbody>
      </table>-->
</div>

<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>