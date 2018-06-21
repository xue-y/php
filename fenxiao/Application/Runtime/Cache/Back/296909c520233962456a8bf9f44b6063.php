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
<div class="admin-panel">
  <div class="body-content">
    <form method="post" class="form-x" action="/wx/Back/Pass/index">
        <input type="hidden" value="2" name="setup">
        <input type="hidden" value="<?php echo ($id); ?>" name="id">
        <p class="red">
            <?php if(($mail_state) != "1"): ?>邮箱未激活，请激活验证
            <input type="hidden" name="mail_state" value="0"><?php endif; ?>
        </p>
        <div class="form-group">
            <div class="label">
                <label>邮箱：</label>
            </div>
            <div class="field">
                <input type="text" class="input"  size="50" value="<?php echo ($mail); ?>"  name="mail"  />
                &nbsp;
                <p><a class="button bg-main margin-big-top"  href="javascript:;" onclick="email_send('<?php echo ($id); ?>','<?php echo ($mail); ?>')">发送验证</a></p>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label >邮箱验证码：</label>
            </div>
            <div class="field">
                <input type="text" class="input" name="mail_ver" size="50"  data-validate="required:请输入验证码,code:请填写正确的验证码" /> &nbsp;

            </div>
        </div>
     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main" type="submit">下一步</button>
             &nbsp;  &nbsp;  &nbsp;
         <a class="button bg-main" onclick="javascript :history.back(-1);">返回上一步</a>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="hidden mail_send_c"><?php echo ($mail_send_c); ?></div>
<div class="hidden mail_deny_t"><?php echo ($mail_deny_t); ?></div>
<div class="no-skin">
    <div class="error-container">
        <h1></h1><span class="close" onclick="javascript:$('.no-skin').fadeOut();"></span>
        <div class="errorcon">
            <p></p>
            <i class="clear"></i>
        </div>
    </div>
</div>

<style>
    .no-skin{display: none;}
    .close{ position: absolute;color:#fff;width:28px; height:28px;top:8px;right:10px;}
    .error-container{ position:fixed;bottom: 40%;left:50%;margin-left:-220px; background:#fff; border:1px solid #0ae;  text-align:center; width:450px;  font-family:Microsoft Yahei; padding-bottom:30px; border-top-left-radius:5px; border-top-right-radius:5px;  }
    .error-container h1{ font-size:16px; padding:12px 0; background:#0ae; color:#fff;}
    .errorcon{ padding:35px 0; text-align:center; font-size:16px;width: 90%; margin: 0 auto;}
    .errorcon input{display: inline-block;}
    .errorcon i{ margin:12px auto; font-size:14px;}
    h4 span{color:red;}
    .email_ver_info{visibility: hidden }
    h4{ font-size:14px; color:#666;}
    .input a,a.input{color:#0F85D4;}
</style>
        <script>
            function email_send(id,mail)
            {
                $.post("/wx/Back/Pass/emailSend",{'id':id,'mail':mail},function(data){
                    console.log(data);
                    if($(".no-skin").is(":hidden")) // 邮箱绑定时
                    {
                        $('.no-skin').fadeIn();
                    }
                    switch(data)
                    {
                        case "mail_error":
                            $('.no-skin').find("h1").html("验证邮箱");
                            $('.no-skin').find("p").html("邮箱格式或邮箱账户错误");
                        break;
                        case "mail_state_no":
                            $('.no-skin').find("h1").html("返回第一步");
                            $('.no-skin').find("p").html("<a href='/wx/Back/Pass/index'>由于您长时间未操作<br/>请点击返回第一步</a>")
                        break;
                        case "user_is_no":
                            $('.no-skin').find("h1").html("用户信息错误");
                            $('.no-skin').find("p").html("用户信息错误或用户不存在");
                        break;
                        case "send_mail_c":
                             var mail_send_c=$(".mail_send_c").text();
                            $('.no-skin').find("h1").html("今天邮件发送超过 "+mail_send_c+" 次");
                            $('.no-skin').find("p").html("请明天在验证");
                        break;
                        case "mail_send_error":
                            $('.no-skin').find("h1").html("邮件发送失败");
                            $('.no-skin').find("p").html("重新发送");
                        break;
                        case "mail_send_ok":
                            var mail_url = gotoEmail(mail);
                            mail_url='<a target="_blank" href="http://'+mail_url+'">去我的邮箱</a>';
                            $('.no-skin').find("h1").html("邮件发送成功");
                            $('.no-skin').find("p").html(mail_url);
                            $('.no-skin').find("i").text("验证码发送到邮箱后台，请一个小时内完成验证");
                        break;
                        case "delay_time":
                            var s=$(".mail_deny_t").text();
                            $('.no-skin').find("h1").html("请稍后发送");
                            $('.no-skin').find("p").html("发送邮件间隔时间 "+s+"秒");
                        break;
                    }
                    $('.no-skin').delay(2000).fadeOut();

                });//发送邮件

                //功能：根据用户输入的Email跳转到相应的电子邮箱首页
                function gotoEmail(mail) {
                    $t = mail.split('@')[1];
                    $t = $t.toLowerCase();
                    if ($t == '163.com') {
                        return 'mail.163.com';
                    } else if ($t == 'vip.163.com') {
                        return 'vip.163.com';
                    } else if ($t == '126.com') {
                        return 'mail.126.com';
                    } else if ($t == 'qq.com' || $t == 'vip.qq.com' || $t == 'foxmail.com') {
                        return 'mail.qq.com';
                    } else if ($t == 'gmail.com') {
                        return 'mail.google.com';
                    } else if ($t == 'sohu.com') {
                        return 'mail.sohu.com';
                    } else if ($t == 'tom.com') {
                        return 'mail.tom.com';
                    } else if ($t == 'vip.sina.com') {
                        return 'vip.sina.com';
                    } else if ($t == 'sina.com.cn' || $t == 'sina.com') {
                        return 'mail.sina.com.cn';
                    } else if ($t == 'tom.com') {
                        return 'mail.tom.com';
                    } else if ($t == 'yahoo.com.cn' || $t == 'yahoo.cn') {
                        return 'mail.cn.yahoo.com';
                    } else if ($t == 'tom.com') {
                        return 'mail.tom.com';
                    } else if ($t == 'yeah.net') {
                        return 'www.yeah.net';
                    } else if ($t == '21cn.com') {
                        return 'mail.21cn.com';
                    } else if ($t == 'hotmail.com') {
                        return 'www.hotmail.com';
                    } else if ($t == 'sogou.com') {
                        return 'mail.sogou.com';
                    } else if ($t == '188.com') {
                        return 'www.188.com';
                    } else if ($t == '139.com') {
                        return 'mail.10086.cn';
                    } else if ($t == '189.cn') {
                        return 'webmail15.189.cn/webmail';
                    } else if ($t == 'wo.com.cn') {
                        return 'mail.wo.com.cn/smsmail';
                    } else if ($t == '139.com') {
                        return 'mail.10086.cn';
                    } else {
                        return '';
                    }
                };
            }
        </script>

</body></html>