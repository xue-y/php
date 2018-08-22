<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>首页-<?php echo ($pos["c"]); ?></title>
    <link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
    <link type="text/css" rel="stylesheet" href="/Public/back/css/admin.css">
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/back/js/pintuer.js"></script>

</head>
<body>
<ul class="bread  clearfix">
    <li><a href="/Back/Index/index" target="right" class="icon-home"> 首页</a></li>
    <li><a href="/Back/Customer/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Customer/add"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--客户添加页面-->
<div class="panel  admin-panel">
  <div class="body-content">
    <p class="red">密码不填写默认123456，手机号或微信号至少必填其中一个,后期客户是使用手机号登录</p>
    <form method="post" class="form-x" action="/Back/Customer/execAdd">
       <div class="form-group">
            <div class="label">
                <label>手机号：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" name="phone"   data-validate="mobile:请输入正确的手机号"  value=""/>
                <div class="tips"></div>
            </div>
      </div>
      <div class="form-group">
            <div class="label">
                <label>微信号：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" name="wx"  data-validate="key:微信号字符长度3--20个字符"  value=""/>
                <div class="tips"></div>
            </div>
      </div>
      <div class="form-group">
            <div class="label">
                <label>年龄：</label>
            </div>
            <div class="field">
                <input type="number" class="input w50" name="age"  data-validate="number:请输入数字"  value=""/>
                <div class="tips"></div>
            </div>
      </div>
      <div class="form-group">
            <div class="label">
                <label>客户性别：</label>
            </div>
            <div class="field">
                <div class="button-group radio">

                        <label class="button active">
                            <input name="sex" value="女" type="radio" checked="checked">女
                        </label>

                        <label class="button">
                            <input name="sex" value="男" type="radio">男
                        </label>
                </div>
            </div>
        </div>
      <div class="form-group">
        <div class="label">
          <label>客户名：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="n"  data-validate="title:用户名长度2--10个字符"  value=""/>
          <div class="tips"></div>
        </div>
      </div>
      <div class="form-group">
            <div class="label">
                <label >客户密码：</label>
            </div>
            <div class="field">
                <input type="password" class="input w50" name="pass" size="50" placeholder="请输入新密码" data-validate="userp:密码6--12位字符英文 数字 .@ ! - _"  value=""/>
            </div>
        </div>
      <div class="form-group">
            <div class="label">
                <label>确认密码：</label>
            </div>
            <div class="field">
                <input type="password" class="input w50" name="pass2" size="50" placeholder="请再次输入新密码" data-validate="repeat#u_pass:两次输入的密码不一致"  value=""/>
            </div>
        </div>
      <div class="form-group">
            <div class="label">
                <label>备注信息：</label>
            </div>
            <div class="field">
                <textarea  name="descr"></textarea>
                <span>字符不得超过80个</span>
            </div>
        </div>

     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main" type="submit">保存提交</button>
             &nbsp;  &nbsp;  &nbsp;
         <button class="button bg-main" type="button" onclick="javascript :history.back(-1);">直接返回</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>