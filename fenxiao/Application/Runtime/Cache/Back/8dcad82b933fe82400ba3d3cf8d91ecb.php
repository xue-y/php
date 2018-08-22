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
<!--    <script type="text/javascript" src="/Public/back/js/pintuer.js"></script>-->

</head>
<body>
<ul class="bread  clearfix">
    <li><a href="/Back/Index/index" target="right" class="icon-home"> 首页</a></li>
    <li><a href="/Back/Sysset/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Sysset/index"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--系统设置-->
        <style>
            .form-x .form-group .label{width:200px;}
            .input{float: left;}
            .form-x .form-group .field {
                float: left;
                width: 81%;
            }
            .body-content ul{ padding-left: 0px; font-size: 16px; margin-bottom: 30px;}
            .body-content ul li{float: left; margin-right: 10px; margin-bottom: 10px;}
        </style>
<div class="panel admin-panel">
  <div class="body-content">
    <form method="post" class="form-x" action="/Back/Sysset/index">
      <div class="form-group">
        <div class="label">
          <label>快捷操作列表ID：</label>
        </div>
        <div class="field">
          <input type="number" class="input" name="quick" value="<?php echo ($con["quick"]); ?>" data-validate="number:必须是数字类型" />
          <div class="tips"></div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>普通管理员角色ID：</label>
        </div>
        <div class="field">
            <input type="number" class="input" name="p_r_id" value="<?php echo ($con["prid"]); ?>"  data-validate="number:必须是数字类型"/>
            <div class="tips"></div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>普通管理员授权的角色ID：</label>
        </div>
        <div class="field">
          <input type="text" class="input" name="p_r_id_c" value="<?php echo ($con["pridc"]); ?>" />
            <span class="tipss">多个ID 号中间用逗号隔开</span>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>是否添加多个超级管理员：</label>
        </div>
        <div class="field">
            <div class="button-group radio">
                <?php if(($con["asa"]) == "FALSE"): ?><label class="button active">
                        <input name="a_s_a" value="FALSE" type="radio" checked="checked">否
                    </label>
                    <label class="button">
                        <input name="a_s_a" value="TRUE" type="radio" >是
                    </label>
                <?php else: ?>
                    <label class="button">
                        <input name="a_s_a" value="FALSE" type="radio">否
                    </label>
                    <label class="button  active">
                        <input name="a_s_a" value="TRUE" type="radio" checked="checked">是
                    </label><?php endif; ?>
            </div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
            <label>普通管理员添加其他部门人员：</label>
        </div>
        <div class="field">
            <div class="button-group radio">
                <?php if(($con["asc"]) == "FALSE"): ?><label class="button active">
                        <input name="a_s_c" value="FALSE" type="radio" checked="checked">否
                    </label>
                    <label class="button">
                        <input name="a_s_c" value="TRUE" type="radio" >是
                    </label>
                    <?php else: ?>
                    <label class="button">
                        <input name="a_s_c" value="FALSE" type="radio">否
                    </label>
                    <label class="button  active">
                        <input name="a_s_c" value="TRUE" type="radio" checked="checked">是
                    </label><?php endif; ?>
            </div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>列表页 一页 数量：</label>
        </div>
        <div class="field">
            <input type="number" class="input" name="p_o_c" value="<?php echo ($con["poc"]); ?>" />
          <div class="tips"></div>
        </div>
      </div>
        <div class="form-group">
            <div class="label">
                <label>任务提醒间隔 时间：</label>
            </div>
            <div class="field">
                <input type="number" class="input" name="t_i" value="<?php echo ($con["tint"]); ?>" />
                <div class="tipss">单位为分钟</div>
            </div>
        </div>
      <div class="form-group">
        <div class="label">
          <label>拒绝其他用户访问的权限ID： </label>
        </div>
        <div class="field">
          <input type="text" class="input" name="deny_l_id"   value="<?php echo ($con["denylid"]); ?>"/>
            <span class="tipss">多个ID 号中间用逗号隔开</span>
        </div>
      </div>
        <div class="form-group">
            <div class="label">
                <label>系统管理名称： </label>
            </div>
            <div class="field">
                <input type="text" class="input" name="web_name"   value="<?php echo ($con["webname"]); ?>"/>
                <span class="tipss"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>邮箱服务器端口： </label>
            </div>
            <div class="field">
                <input type="text" class="input" name="s_port"   value="<?php echo ($con["sport"]); ?>"/>
                <span class="tipss"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>邮箱SMTP服务器： </label>
            </div>
            <div class="field">
                <input type="text" class="input" name="s_server"   value="<?php echo ($con["sserver"]); ?>"/>
                <span class="tipss"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>邮箱SMTP账号： </label>
            </div>
            <div class="field">
                <input type="text" class="input" name="s_mail"   value="<?php echo ($con["smail"]); ?>"/>
                <span class="tipss"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>邮箱SMTP密码： </label>
            </div>
            <div class="field">
                <input type="text" class="input" name="s_pass"   value="<?php echo ($con["spass"]); ?>"/>
                <span class="tipss"></span>
            </div>
        </div>
        <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main" type="submit"  onclick="return confirm('系统设置修改可能出现其他文件错误\n 请谨慎操作')"> 保存提交</button>
          &nbsp; &nbsp; &nbsp;
           <button class="button bg-main" type="button" onclick="javascript :history.back(-1);"> 直接返回</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>