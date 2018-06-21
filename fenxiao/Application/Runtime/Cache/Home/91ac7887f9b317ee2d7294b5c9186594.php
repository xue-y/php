<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

   <title>添加推荐</title>
</head>
<body class="line-add">
<h3>
   <a  onclick="javascript :history.back(-1)" >←</a> | 添加推荐 <a class="float-right" href="/wx/Home/Line/index">推荐</a>
</h3>
    <p class="red">用户一天(24 小时内)最多推荐不得超过20个 手机号或微信号至少必填其中一个</p>
<form action="/wx/Home/Line/execAdd" method="post">
    <div class="form-group">
        <div class="field">
            <input type="text" class="input w100" name="phone" placeholder="请输入好友手机号"  data-validate="mobile:请输入正确的手机号"  value=""/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <input type="text" class="input w100 " name="wx"  placeholder="请输入好友微信号"  data-validate="key:微信号字符长度3--20个字符"  value=""/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <input type="text" class="input w100" name="n"  placeholder="请输入好友姓名" data-validate="title:用户名长度2--10个字符"  value=""/>
            <div class="tips"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="field">
            <input type="number" class="input w100" name="age"  placeholder="请输入好友年龄" data-validate="number:请输入数字"  value=""/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <div class="button-group radio">
                <div class="label">
                    <label>好友性别：</label>
                </div>
                <label class="button active">
                    <input name="sex" value="女" type="radio" checked="checked">女
                </label>

                <label class="button">
                    <input name="sex" value="男" type="radio">男
                </label>
            </div>
        </div>
    </div>
    <textarea name="remarks" class="w100">
    </textarea><p>请输入好友需要做的项目或其他的一些相关备注信息,字符不得超过80个</p>
    <div class="form-group">
        <div class="field" style="text-align: center">
            <button class="button bg-main" type="submit">保存提交</button>
            &nbsp;  &nbsp;  &nbsp;
            <input type="reset" value="清空重置" class="button bg-main" >
        </div>
    </div>
</form>

<link type="text/css" rel="stylesheet" href="/wx/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/wx/Public/home/home.css" >
<script  type="text/javascript" src="/wx/Public/back/js/jquery.js"></script>
<script  type="text/javascript" src="/wx/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/wx/Public/back/js/pintuer.js"></script>
</body>
</html>