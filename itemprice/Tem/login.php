<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>登录</title>
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <link rel="stylesheet" href="/Static/css/admin.css">
    <script src="/Static/js/jquery.js"></script>
    <script src="/Static/js/pintuer.js"></script>
</head>
<body>
<div class="bg"></div>
<div class="container">
    <div class="line bouncein">
        <div class="xs6 xm4 xs3-move xm4-move">
            <div style="height:150px;"></div>
            <div class="media media-y margin-big-bottom">
            </div>
            <form action="/Php/Controll/Login.php" method="post">
                <div class="panel loginbox">
                    <div class="text-center margin-big padding-big-top"><h1>后台管理中心</h1></div>
                    <div class="panel-body" style="padding:30px; padding-bottom:10px; padding-top:10px;">
                        <div class="form-group">
                            <div class="field field-icon-right">
                         <input type="text" class="input-big" name="id" placeholder="登录编号" data-validate="required:请填写编号"  
								value="<?php echo isset($_COOKIE[$this->u_id])?$_COOKIE[$this->u_id]:'';?>">
                                <span class="icon icon-laptop margin-small"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="field field-icon-right">
                                <input type="password" class="input-big" name="pw" placeholder="登录密码" data-validate="required:请填写密码" />
                                <span class="icon icon-key margin-small"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="field">
                                <input type="text" class="input-big" name="code" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码" />
                                <img src="/Php/Controll/Vericode.php"  width="100"  class="passcode" style="height:43px;cursor:pointer;" onclick="this.src=this.src+'?t='+Math.random()">

                            </div>
                        </div>
                    </div>
                    <p  class="clear clearfix" style="padding-left: 32px;"> <input type="checkbox" name="keep" value="true"> 记住编号 &nbsp; <span class="text-dot">保存一个星期</span></p>
                    <div style="padding:0px 27px 30px;"><input type="submit" class="button button-block bg-main input-big" value="登录"></div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>