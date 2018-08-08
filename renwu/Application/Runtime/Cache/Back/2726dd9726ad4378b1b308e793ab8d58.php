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

<!--用户找回密码第三步页面-->
<div class="admin-panel">
  <div class="body-content">
    <form method="post" class="form-x" action="/Back/Pass/index">
        <p class="red">请在1个小时内完成操作</p>
        <input type="hidden" value="<?php echo ($id); ?>" name="id">
        <input type="hidden" value="3" name="setup">
        <div class="form-group">
            <div class="label">
                <label >新密码：</label>
            </div>
            <div class="field">
                <input type="password" class="input" name="u_pass" size="50" placeholder="请输入新密码" data-validate="required:请输入新密码,userp:密码6--12位字符英文 数字 .@ ! - _" />
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>确认密码：</label>
            </div>
            <div class="field">
                <input type="password" class="input" name="u_pass2" size="50" placeholder="请再次输入新密码" data-validate="required:请再次输入新密码,repeat#u_pass:两次输入的密码不一致" />
            </div>
        </div>

     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
         <div class="field">
             <button class="button bg-main" type="submit">确认完成</button>
             &nbsp;  &nbsp;  &nbsp;
             <a class="button bg-main" onclick="javascript :history.back(-1);">返回上一步</a>
         </div>
      </div>
    </form>
  </div>
</div>
</body></html>