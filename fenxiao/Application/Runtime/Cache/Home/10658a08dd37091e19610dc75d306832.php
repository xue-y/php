<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>


    <title>基本信息</title>
</head>
<body class="cusBase">
<h3>
    <a onclick="javascript :history.back(-1)" >←</a> | 基本信息 <a class="float-right" href="/Home/Set/index">设置</a>
</h3>
<form action="/Home/Set/execBase" method="post">
    <div class="form-group">
        <div class="label">
            <label class="x0-left">姓名：</label>
        </div>
        <div class="field">
            <input type="text" class="input  x2-move x10" name="n" data-validate="title:用户名长度2--10个字符"  value="<?php echo ($info["n"]); ?>"/>
            <div class="tips"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="label">
            <label class="x0-left">年龄：</label>
        </div>
        <div class="field">
            <input type="number" class="input x2-move x10" name="age"   data-validate="number:请输入数字"   value="<?php echo ($info["age"]); ?>"/>
            <div class="tips"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="label">
            <label class="x0-left">性别：</label>
        </div>
        <div class="field">
            <div class="button-group radio x2-move x10">
                <?php if($info["sex"] == '女'): ?><label class="button active">
                        <input name="sex" value="女" type="radio" checked="checked">女
                    </label>
                    <label class="button">
                        <input name="sex" value="男" type="radio">男
                    </label>
                    <?php else: ?>
                    <label class="button">
                        <input name="sex" value="女" type="radio">女
                    </label>
                    <label class="button active">
                        <input name="sex" value="男" type="radio" checked="checked">男
                    </label><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="label">
            <label class="x0-left">微信号：</label>
        </div>
        <div class="field">
            <?php if(empty($info["wx"])): ?><input type="text" class="input  x2-move x10" name="wx" data-validate="key:微信号字符长度3--20个字符" value=""
                        placeholder="请设置微信号用于微信登录或找回密码"/>
                <?php else: ?>
                <input type="text" class="input  x2-move x10" name="wx" data-validate="key:微信号字符长度3--20个字符" value="<?php echo ($info["wx"]); ?>"/>

                <?php if(($info["is_wx"]) != "1"): ?><a href="/Home/Wx/index?state=<?php echo (WX_VALIWX); ?>_<?php echo ($info["id"]); ?>&history=<?php echo ($info["history"]); ?>"  class="clear" style="margin-left:10%;padding-top:5px; display: block">
                        &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; 请验证微信用于微信登录或找回密码</a><?php endif; endif; ?>

            <div class="tips"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="label">
            <label  class="x0-left">下线：</label>
        </div>
        <div class="field">
            <p  class="input  x2-move x10" readonly >
                <?php if(($info["sub_num"]) < "1"): ?>暂无下线<a href="/Home/Line/add"> 现在去推荐 </a>
                    <?php else: ?>
                    <a href="/Home/Line/index"><?php echo ($info["sub_num"]); ?> 个</a><?php endif; ?>
            </p>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
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
<script  type="text/javascript" src="/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
</body>
</html>