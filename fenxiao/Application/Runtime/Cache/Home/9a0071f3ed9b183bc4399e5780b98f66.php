<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>读取信息</title>
</head>
<body class="cusBase">
<h3>
    <a  onclick="javascript :history.back(-1)" >←</a> | 信息 <a class="float-right" href="/Home/Info/index">列表</a>
</h3>
    <ul class="padding-big">
       <?php echo ($info["con"]); ?>
    </ul>

<div class="fixed-bottom"> <!-- 只可在 Info 控制器中使用-->
    <a href="/Home/Money/index">¥ 佣金</a>
    <a href="http://m.caoboshi.cn/" class=" icon-gift"> 项目</a>
    <a href="/Home/Line/index" class="icon-star-empty"> 推荐</a>
    <a href="/Home/Info/index?status=1" class="icon-bell-alt"> 我的
        <?php if(($meg) >= "1"): ?><sup><?php echo ($meg); ?></sup><?php endif; ?></a>
</div>
<link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/Public/home/home.css" >
<script  type="text/javascript" src="/Public/back/js/jquery.js"></script>
<script  type="text/javascript" src="/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
</body>
</html>