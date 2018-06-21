<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>手机号</title>
</head>
<body class="cusBase">
<h3>
    <a    onclick="javascript :history.back(-1)" >←</a> | 手机号 <a class="float-right" href="/wx/Home/Set/index">设置</a>
</h3>
<form action="/wx/Home/Set/execPhone" method="post" >
    <div class="form-group">
        <div class="label">
            <label class="x0-left">手机号：</label>
        </div>
        <div class="field">
            <input type="text" class="input  x2-move x10" name="phone"  data-validate="mobile:请输入正确的手机号"  value="<?php echo ($phone); ?>"/>
            <div class="tips"></div>
        </div>
    </div>
    &nbsp;
    <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <div class="form-group margin-large-top">
        <div class="field" style="text-align: center">
            <button class="button bg-main" type="submit">保存提交</button>
            &nbsp;  &nbsp;  &nbsp;
            <button class="button bg-main" type="button" onclick="javascript :history.back(-1);">直接返回</button>
        </div>
    </div>
</form>

<div class="fixed-bottom"> <!-- 只可在 Info 控制器中使用-->
    <a href="/wx/Home/Money/index">¥ 佣金</a>
    <a href="http://m.caoboshi.cn/" class=" icon-gift"> 项目</a>
    <a href="/wx/Home/Line/index" class="icon-star-empty"> 推荐</a>
    <a href="/wx/Home/Info/index?status=1" class="icon-bell-alt"> 我的
        <?php if(($meg) >= "1"): ?><sup><?php echo ($meg); ?></sup><?php endif; ?></a>
</div>
<link type="text/css" rel="stylesheet" href="/wx/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/wx/Public/home/home.css" >
<script  type="text/javascript" src="/wx/Public/back/js/jquery.js"></script>
<script  type="text/javascript" src="/wx/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/wx/Public/back/js/pintuer.js"></script>
</body>
</html>