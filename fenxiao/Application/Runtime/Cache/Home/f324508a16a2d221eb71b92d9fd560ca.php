<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />


    <title>个人主页</title>
</head>
<body class="index-index">
<h3>
    我的主页--<?php echo ($info["n"]); ?> <a class="float-right" href="/Home/Set/index">设置</a> <!--后期改为设置-->
</h3>
    <h2>
        <img src="<?php echo ($info["headimg"]); ?>" width="70">
        <div  style="font-size:18px;">
        佣金金额：
        <?php if(($info["money"]) < "1"): ?>暂无佣金
            <p><a href="/Home/Line/add">推荐好友赚佣金</a></p>
            <?php else: ?>
            <?php echo ($info["money"]); endif; ?>
        </div>
   </h2>
    <p  class="text-center">佣金提现说明：<a href="/Home/Zx/index">请联系您的咨询师</a></p>
    <ul class="padding">

        <li><a href="/Home/Set/cusBase"><span class="icon-cog"></span> 基本信息</a></li>
        <li><a href="/Home/Line/index"><span  class="icon-star"></span> 我的推荐</a></li>
        <li><a href="/Home/Info/index?status=1"><span  class=" icon-bell"></span>
            我的消息<?php if(($meg) >= "1"): ?><sup><?php echo ($meg); ?></sup><?php endif; ?>
            </a></li>
        <li><a href="/Home/Set/index"><span class="icon-cogs"></span> 我的设置</a></li>
        <li><a href="/Home/Zx/index"><span class="icon-user"></span> &nbsp;我的客服</a></li>
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