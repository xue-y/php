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

            ul.list{/*line-height: 2;*/ font-size:16px; margin-top: 10px;}
           /* ul.list li{width:25%; text-align: center;float: left; }
            ul.list li img{ display: block; margin:0 auto 10px;width:80px;}
            sup a,h2 a{color:#0F85D4;}*/
        </style>
<div class="padding-large">
    <h2 >
        <?php echo ($n); ?> 用户欢迎进入 <a><?php echo (WBE_NAME); ?></a>
    </h2>
     <h3 class="padding-left">快捷操作</h3>
    <?php if(is_array($quick)): foreach($quick as $key=>$v): ?><ul  class="list">
        <li><a href="/Back/<?php echo ($v["execs"]); ?>"><?php echo ($v["n"]); ?></a></li>
    </ul><?php endforeach; endif; ?>
</div>

<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>