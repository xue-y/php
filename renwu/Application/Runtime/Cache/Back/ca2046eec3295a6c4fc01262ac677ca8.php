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
    <li><a href="/Back/Index/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Index/index"><?php echo ($pos["a"]); ?></a></li>
</ul>
<!--后台首页页面-->
        <style>
            h3{padding-left: 40px;}
            ul.list{line-height: 2; font-size:20px; margin-top: 50px;}
            ul.list li{width:25%; text-align: center;float: left; }
            ul.list li img{ display: block; margin:0 auto 10px;width:80px;}
            sup a,h2 a{color:#0F85D4;}
        </style>
<div class="padding">
<h2 class="padding">
    <?php echo ($n); ?> 用户欢迎进入<a><?php echo (WBE_NAME); ?></a>
</h2>
    <h3>快捷操作
        <hr/>
    </h3>
    <ul class="list">
        <?php if(is_array($task)): foreach($task as $k=>$v): ?><li><a href="/Back/<?php echo ($v["execs"]); ?>"><img src="/Public/back/images/<?php echo ($k); ?>.png">
               <?php if($k==0 && isset($p_c) && $p_c>0) { echo '<span style="margin-left:68px;">'. $v['n'].'</span></a><sup><a href="/Back/Task/index?p_id='.$p_id.'"><span class="red">'.$p_c.'</span> 条新任务</a></sup>'; }else { echo $v['n'].'</a>'; } ?>
           </li><?php endforeach; endif; ?>
        <?php if(isset($meg) && $meg>0) { $n=$k+1; echo '<li><a href="/Back/Personal/megList?id='.$u_id.'"><img src="/Public/back/images/'.$n.'.png">现有<span class="red"> '.$meg.' </span>条反馈信息</a></li>'; } ?>

    </ul>
</div>

<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>