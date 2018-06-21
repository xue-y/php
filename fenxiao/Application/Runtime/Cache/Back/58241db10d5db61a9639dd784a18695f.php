<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>首页-<?php echo ($pos); ?></title>
    <link type="text/css" rel="stylesheet" href="/wx/Public/back/css/pintuer.css">
    <link type="text/css" rel="stylesheet" href="/wx/Public/back/css/admin.css">
    <script type="text/javascript" src="/wx/Public/back/js/jquery.js"></script>
    <script type="text/javascript" src="/wx/Public/back/js/pintuer.js"></script>
    <script type="text/javascript" src="/wx/Public/back/js/arc_list.js"></script>
</head>
<body class="text-center" style="max-width:500px;width:100%;margin:0 auto">
<h3 style="margin:50px auto 0px"><?php echo ($pos); ?></h3>

<!--用户找回密码第一步页面-->
<div class=" admin-panel">
  <div class="body-content">
    <form method="post" class="form-x" action="/wx/Back/Pass/index">
        <input type="hidden" value="1" name="setup">
      <div class="form-group">
        <div class="label">
          <label>用户名：</label>
        </div>
        <div class="field">
          <input type="text" class="input" name="u_name" size="50"  data-validate="required:,title:用户名长度2--10个字符" />
          <div class="tips"></div>
        </div>
      </div>
        <div class="form-group">
            <div class="label">
                <label >用户编号：</label>
            </div>
            <div class="field">
                <input type="text" class="input" name="id" size="50"  data-validate="required:请输入编号,num:请填写正确的编号" />
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>绑定邮箱：</label>
            </div>
            <div class="field">
                <input type="text" class="input" name="mail" size="50"  data-validate="required:请输入邮箱码,email:请输入正确的邮箱" />
            </div>
        </div>

     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main" type="submit">下一步</button>
             &nbsp;  &nbsp;  &nbsp;
         <button class="button bg-main"   type="button" onclick="javascript :history.back(-1);">直接返回</button>
        </div>
      </div>
    </form>
  </div>
</div>
</body></html>