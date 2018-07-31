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
    <li><a href="/Back/User/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/User/update"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--用户修改页面-->
<div class="panel  admin-panel">
  <div class="body-content">
    <form method="post" class="form-x" action="/Back/User/execUate">
      <div class="form-group">
        <div class="label">
          <label>用户名：</label>
        </div>
        <div class="field">
          <input type="text"  value="<?php echo ($u_info["u_name"]); ?>" class="input w50" name="u_name"  data-validate="required:,title:用户名长度2--10个字符" />
          <div class="tips"></div>
        </div>
      </div>
        <div class="form-group">
            <div class="label">
                <label >原密码：</label>
            </div>
            <div class="field">
                <input type="password" class="input w50" name="old_pass" size="50" placeholder="请输入新密码" data-validate="userp:密码6--12位字符英文 数字 .@ ! - _" value="" />
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label >新密码：</label>
            </div>
            <div class="field">
                <input type="password" class="input w50" name="u_pass" size="50" placeholder="请输入新密码" data-validate="userp:密码6--12位字符英文 数字 .@ ! - _" value="" />
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>确认密码：</label>
            </div>
            <div class="field">
                <input type="password" class="input w50" name="u_pass2" size="50" placeholder="请再次输入新密码" data-validate="repeat#u_pass:两次输入的密码不一致"  value=""/>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>所在部门：</label>
            </div>
            <div class="field">

               <select name="bumen" class="input" style="width:160px; line-height:17px; display:inline-block;" >
                   <option  value="-1">请选择用户所在部门</option>
                   <?php if($u_ide=="part" && A_S_C==FALSE) { echo '<option value="'.$bu_men.'" selected>&nbsp;├ &nbsp;'.$bu_men.'</option>'; }else { ?>
                   <?php if(is_array($bu_men)): foreach($bu_men as $k=>$v): ?><option value="<?php echo ($v); ?>" <?php if(($u_info["bumen"]) == $v): ?>selected<?php endif; ?>>&nbsp;├ &nbsp;<?php echo ($v); ?></option><?php endforeach; endif; ?>
                   <?php } ?>
              </select>
                &nbsp;  &nbsp;<span class="red" style="display: none">请选择用户所在部门</span>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>用户角色：</label>
            </div>
                <div class="field">
                    <select  name="role_id" class="input"  style="width:160px; line-height:17px; display:inline-block;" >
                       <!--超级管理员/普通管理员-->
                        <?php if($u_ide == 'self'): ?><option value="<?php echo ($u_info["role_id"]); ?>" selected><?php echo ($u_info["role_n"]); ?></option>
                       <?php else: ?>
                        <option  value="-1">请选择用户的角色</option>
                          <?php if(is_array($role_all)): foreach($role_all as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"<?php if(($u_info["role_id"]) == $v["id"]): ?>selected<?php endif; ?> >&nbsp;├ &nbsp;<?php echo ($v["n"]); ?></option><?php endforeach; endif; endif; ?>
                    </select>
                    &nbsp;  &nbsp;<span class="red" style="display: none">请选择用户的角色</span>
                </div>
        </div>

      <!-- 附加表信息---如果管理需要自行修改-->
            <div class="form-group">
                <div class="label">
                    <label>手机号：</label>
                </div>
                <div class="field">
                    <input type="text"  value="<?php echo ($u_fj["phone"]); ?>" class="input w50" name="phone" data-validate="mobile:请输入正确的手机号" />
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label>微信号：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" name="wx" value="<?php echo ($u_fj["wx"]); ?>"  data-validate="key:微信号字符长度3--20个字符"  value=""/>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label>消息个数：</label>
                </div>
                <div class="field">
                    <p class="input w50"  readonly> <a href="/Back/Line/index?state=1"><?php echo ($u_fj["meg"]); ?></a></p>
                    <div class="tips"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="label">
                    <label>个人说明：</label>
                </div>
                <div class="field">
                    <textarea  name="info"><?php echo (str_slashes($u_fj["info"])); ?></textarea>
                    <span>字符不得超过80个</span>
                </div>
            </div>

    <input type="hidden" value="<?php echo ($u_info["id"]); ?>" name="id">
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
<script>
    $('select').each(function(){
        var t=$(this);
        $(this).blur(function(){
            if($(this).val()=="-1")
            {
                t.css('borderColor','red').siblings('.red').css('display','inline-block');
                return false;
            }
       });
        $(this).focus(function(){
            t.css('borderColor','#ddd').siblings('.red').css('display','none');
        });
        $('form').submit(function(){
            $('select').each(function(){
                $(this).trigger("blur");
            })
        })
    });
</script>

<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>