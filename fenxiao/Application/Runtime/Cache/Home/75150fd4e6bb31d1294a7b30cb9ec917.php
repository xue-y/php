<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>

<!--wx 端客户登录-->
<title>客户微信<?php echo ($title); ?>页面</title>
</head>
<body class="login">
<h3>
    微信<?php echo ($title); ?> <a class="float-right"  onclick="javascript :history.back(-1)" >返回</a>
</h3>
<p class="text-center">长按识别二维码<?php echo ($title); ?></p>
<img src="<?php echo ($img); ?>" style="margin: 0 auto; display: block">
<link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/Public/home/home.css" >
<script  type="text/javascript" src="/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
</body>
</html>