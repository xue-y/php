<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!--wx 端客户登录-->
    <title>客户登录页面</title>
</head>
<body class="login">
<h3>
    登录
</h3>
  <form class="form-x" action="/Home/Login/login"  method="post">
      <input type="hidden" value="<?php echo ($history); ?>" name="history">
      <div class="form-group">
          <div class="field">
              <input type="text" class="input w100" name="phone" placeholder="请输入手机号" data-validate="mobile:请输入正确的手机号"  value='<?php if(isset($id)): echo ($id); endif; ?>' required="" />
              <div class="tips"></div>
          </div>
      </div>

      <div class="form-group">
          <div class="field">
              <input type="password" class="input w100" name="pass" size="50" placeholder="请输入密码" data-validate="userp:密码6--12位字符英文 数字 .@ ! - _"  value="" required=""/>
          </div>
      </div>

      <div class="form-group" >
          <div class="field"  class="input-big"  >
              <input   type="checkbox"  value="1" name="Long-term"/> 记住手机号
              <a  class="float-right" href="/Home/Wx/index?state=<?php echo (WX_PASS); ?>&history=<?php echo ($history); ?>">忘记密码微信找回</a>
          </div>
      </div>
     <p><a href="/Home/Wx/index?state=<?php echo (WX_LOGIN); ?>&history=<?php echo ($history); ?>">微信授权登录</a></p>
      <div class="form-group margin-large-top" >
          <div class="field" style="text-align: center">
              <button class="button bg-main" type="submit">登录</button>
              &nbsp;  &nbsp;  &nbsp;
              <input type="reset" value="重置" class="button bg-main" >
          </div>
      </div>

  </form>


<link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/Public/home/home.css" >
<script  type="text/javascript" src="/Public/back/js/jquery.js"></script>
<script  type="text/javascript" src="/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
</body>
</html>