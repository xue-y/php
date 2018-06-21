<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>修改推荐</title>
</head>
<body class="line-add">
<h3>
    <a   onclick="javascript :history.back(-1)" >←</a> | 修改推荐 <a class="float-right" href="/wx/Home/Line/index">推荐</a>
</h3>
<p class="red">值为空，默认不修改</p>
<?php if(($line_info["state"]) != "1"): ?><p class="red">此推荐人已经被审核过，不可修改</p><?php endif; ?>

<form <?php if(($line_info["state"]) == "1"): ?>action="/wx/Home/Line/execUate" method="post"<?php endif; ?> >
    <div class="form-group">
        <div class="field">
            <input type="text" class="input w100" name="phone" placeholder="请输入好友手机号"  data-validate="mobile:请输入正确的手机号"  value="<?php echo ($line_info["phone"]); ?>"/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <input type="text" class="input w100 " name="wx"  placeholder="请输入好友微信号"  data-validate="key:微信号字符长度3--20个字符"  value="<?php echo ($line_info["wx"]); ?>"/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <input type="text" class="input w100" name="n"  placeholder="请输入好友姓名" data-validate="title:用户名长度2--10个字符"  value="<?php echo ($line_info["n"]); ?>"/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <input type="number" class="input w100" name="age"  placeholder="请输入好友年龄" data-validate="number:请输入数字"  value="<?php echo ($line_info["age"]); ?>"/>
            <div class="tips"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="field">
            <div class="button-group radio">
                <div class="label">
                    <label>好友性别：</label>
                </div>
                <?php if($line_info["sex"] == '女'): ?><label class="button active">
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

    <input type="hidden" value="<?php echo ($line_info["id"]); ?>" name="id">
    <textarea name="remarks"  class="w100"><?php echo (str_slashes($line_info["remarks"])); ?>
    </textarea><p>请输入好友需要做的项目或其他的一些相关备注信息</p>

    <?php if(($line_info["state"]) == "1"): ?><div class="form-group">
            <div class="field" style="text-align: center">
                <button class="button bg-main" type="submit">保存提交</button>
                &nbsp;  &nbsp;  &nbsp;
                <input type="reset" value="清空重置" class="button bg-main" >
            </div>
        </div><?php endif; ?>
</form>

<link type="text/css" rel="stylesheet" href="/wx/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/wx/Public/home/home.css" >
<script  type="text/javascript" src="/wx/Public/back/js/jquery.js"></script>
<script  type="text/javascript" src="/wx/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/wx/Public/back/js/pintuer.js"></script>
</body>
</html>