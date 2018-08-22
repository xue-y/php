<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>首页-<?php echo ($pos); ?></title>
    <link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css">
    <link type="text/css" rel="stylesheet" href="/Public/back/css/admin.css">
    <script type="text/javascript" src="/Public/back/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
    <script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</head>
<body class="text-center" style="max-width:500px;width:100%;margin:0 auto">
<h3 style="margin:50px auto 0px"><?php echo ($pos); ?></h3>

<!--执行邮箱验证页面-->
<div class="panel  admin-panel">
  <div class="body-content">
    <form method="post" class="form-x" action="/Back/Personal/execeEailVer">
        <div class="form-group">
            <div class="label">
                <label>邮箱：</label>
            </div>
            <div class="field">
                <input type="text" class="input"  size="50" value="<?php echo ($mail); ?>" readonly />
            </div>
        </div>
        <input type="hidden"   value="<?php echo ($mail); ?>" name="mail">
        <input type="hidden"   value="<?php echo ($id); ?>" name="id">
        <input type="hidden"   value="<?php echo ($state); ?>" name="state">
        <div class="form-group">
            <div class="label">
                <label >邮箱验证码：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" name="mail_ver" size="50"  data-validate="required:请输入验证码,code:请填写正确的验证码" /> &nbsp;
                <button class="button bg-main" type="submit">验证邮箱</button>
            </div>
        </div>

    </form>
  </div>
</div>
</body></html>