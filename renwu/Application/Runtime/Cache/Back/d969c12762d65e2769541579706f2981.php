<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>登录</title>  
    <link rel="stylesheet" href="/Public/back/css/pintuer.css" type="text/css">
    <link rel="stylesheet" href="/Public/back/css/admin.css" type="text/css">
    <script src="/Public/back/js/jquery.js" type="text/javascript"></script>
    <script src="/Public/back/js/pintuer.js"  type="text/javascript"></script>
    <script>
     $(function(){
         $('input[type=button]').click(function(){
             $.ajax({
                 type:"POST",
                 url:"/Back/Login/login",  //Login/login
                 data:$('form').serialize(),
                 success: function(data,status,xhr){
                     console.log(data);
                     if(data=="okok")
                     { window.location.href="/Back/Main/index";}
                     else
                     {alert(data);}
                 },
                 error: function(xhr,status,errorinfo){
                     console.log('登录失败'+errorinfo);
                 },
                 timeout:10000
             });
         });// -------------------登录
     });
    </script>
</head>
<body>
<div class="bg"></div>
<div class="container">
    <div class="line bouncein">
        <div class="xs6 xm4 xs3-move xm4-move">
            <div style="height:100px;"></div>
            <form>
            <div class="panel loginbox">
                <div class="text-center margin-big padding-big-top"><h1>后台管理中心</h1></div>
                <div class="panel-body" style="padding:30px; padding-bottom:10px; padding-top:10px;">
                    <!--<div class="form-group">
                        <div class="field field-icon-right">
                            <input type="text" class="input-big" name="u_name" placeholder="登录账号" data-validate="required:请填写账号,title:请输入用户名"  value="<?php if(isset($n)): echo ($n); endif; ?>" />
                            <span class="icon icon-user margin-small"></span>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="text" class="input-big" name="id" placeholder="登录编号" data-validate="required:请填写编号,num:请填写正确的编号"   value="<?php if(isset($id)): echo ($id); endif; ?>"/>
                            <span class="icon  icon-laptop margin-small"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="password" class="input-big" name="u_pass" placeholder="登录密码" data-validate="required:请填写密码,userp:6--12位数字、英文 . ! @ - _" onpaste="return false"/>
                            <span class="icon icon-key margin-small"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="field code">
                            <input type="text" class="input-big" name="code" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码,code:请填写正确的5位 验证码"/>
                           <img src="/Back/Login/verify"  width="100" height="32" class="passcode" style="height:43px;cursor:pointer;" onclick="this.src='/Back/Login/verify?r='+Math.random()"  title="验证码仅此一次有效，输入错误后请点击刷新">
                        </div>
                        <i style="font-size:12px;color:#666666">验证码仅此一次有效，输入错误后请点击图片刷新 <?php echo ($code); ?></i>
                    </div>
                    <div class="form-group" >
                        <div class="field"  class="input-big"  >
                            <input   type="checkbox"  value="1" name="Long-term"/> 记住用户名
                            <a  class="float-right" href="/Back/Pass/index">忘记密码</a>
                        </div>
                    </div>
                </div>
             <div style="padding:0px 30px 30px;"><input type="button" class="button button-block bg-main text-big input-big" value="登录"></div>
            </div>
            </form>          
        </div>
    </div>
</div>

</body>
</html>