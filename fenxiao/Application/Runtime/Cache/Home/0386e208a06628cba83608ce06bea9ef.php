<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>修改密码</title>
</head>
<body class="line-add">
<h3>
    <a  onclick="javascript :history.back(-1)" >←</a> | 修改密码 <a class="float-right" href="/Home/Set/index">设置</a>
</h3>
<p class="red">其中一个值为空默认不修改</p>

<form action="/Home/Set/execPass" method="post" >
    <div class="form-group margin-large-top">
        <div class="field">
            <input type="password" class="input w100" name="old_pass" placeholder="原密码"  data-validate="userp:密码6--12位字符英文 数字 .@ ! - _"   value=""/>
            <div class="tips"></div>
            <a href="/Home/Wx/index?state=<?php echo (WX_PASS); ?>&history=<?php echo ($history); ?>" class="clear margin-small-top" style="display: block;"  >忘记原密码微信找回</a>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <input type="password" class="input w100" name="pass" placeholder="新密码"  data-validate="userp:密码6--12位字符英文 数字 .@ ! - _"   value=""/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <input type="password" class="input w100" name="pass2" placeholder="确认密码" data-validate="repeat#pass:两次输入的密码不一致"    value=""/>
            <div class="tips"></div>
        </div>
    </div>
    &nbsp;
    <div class="form-group margin-large-top">
        <div class="field" style="text-align: center">
            <button class="button bg-main" type="submit">保存提交</button>
            &nbsp;  &nbsp;  &nbsp;
            <button class="button bg-main" type="button" onclick="javascript :history.back(-1);">直接返回</button>
        </div>
    </div>
</form>

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