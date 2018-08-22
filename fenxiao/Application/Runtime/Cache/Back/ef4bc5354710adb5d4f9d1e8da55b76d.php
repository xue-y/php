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
    <li><a href="/Back/Line/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Line/censor"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--咨询审核客户推荐的下线页面-->
<div class="panel  admin-panel">
    <div class="body-content">

        <?php if($type == 'eye' ): ?><p class="red">
            您已经审核过了，只可查看<br/>
            审核状态：
            <?php if($info["state"] == '2'): ?>通过审核<?php endif; ?>
            <?php if($info["state"] == '3'): ?>未通过审核<?php endif; ?>
            -- 审核时间：<?php echo ($info["s_t"]); ?>
          </p>
          <form  class="form-x" >
        <?php else: ?>
              <p class="red">审核通过后此客户为当前管理员的客户</p>
        <form method="post" class="form-x" action="/Back/Line/execEnsor"><?php endif; ?>
            <div class="form-group">
                <div class="label">
                    <label>推荐人姓名：</label>
                </div>
                <div class="field">
                    <p class="input w50"  style="height:40px;">
                        <a href="/Back/Customer/update?id=<?php echo ($info["tid"]); ?>" title="查看推荐人信息"><?php echo ($info["t_n"]); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>客户手机号：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="phone"    value="<?php echo ($info["phone"]); ?>"/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>客户微信号：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="wx"    value="<?php echo ($info["wx"]); ?>"/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>客户姓名：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="n"    value="<?php echo ($info["n"]); ?>"/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>年龄：</label>
                </div>
                <div class="field">
                    <input type="number" class="input w50" name="age"  value="<?php echo ($info["age"]); ?>" data-validate="number:请输入数字"  value=""/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>客户性别：</label>
                </div>
                <div class="button-group radio">
                    <?php if($info["sex"] == '男' ): ?><label class="button">
                            <input name="sex" value="女" type="radio">女
                        </label>
                        <label class="button active">
                            <input name="sex" value="男" type="radio" checked="checked">男
                        </label>
                        <?php else: ?>
                        <label class="button active">
                            <input name="sex" value="女" type="radio" checked="checked">女
                        </label>

                        <label class="button">
                            <input name="sex" value="男" type="radio">男
                        </label><?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>推荐时间：</label>
                </div>
                <div class="field">
                    <p  class="input w50"><?php echo ($info["t"]); ?></p>
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label>备注信息：</label>
                </div>
                <div class="field">
                    <textarea><?php echo (str_slashes($info["remarks"])); ?></textarea>
                </div>
            </div>
         <?php if($type == 'edit' ): ?><hr/>
            <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
            <input type="hidden" name="tid" value="<?php echo ($info["tid"]); ?>">
            <div class="form-group">
                <div class="label">
                    <label>审核：</label>
                </div>
                <div class="button-group radio">
                    <label class="button active">
                        <input name="state" value="2" type="radio" checked="checked">通过
                    </label>

                    <label class="button">
                        <input name="state" value="3" type="radio">未通过
                    </label>
                </div>
             </div>

            <div class="form-group">
                <div class="label">
                    <label>审核备注：</label>
                </div>
                <div class="field">
                    <textarea name="descr"><?php echo (str_slashes($info["remarks"])); ?></textarea>
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
                </div><?php endif; ?>
        </form>
    </div>
</div>
<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>