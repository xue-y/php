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
    <li><a href="/Back/User/index"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--用户列表页面-->
<style>
    .submit input{background: none; border:none;color:#f00;}
    .submit:hover input{color:#fff;}
</style>
<div class="panel">
<form method="post" action="/Back/User/del">
  <div class="padding">
      <ul class="search" style="padding-left:10px;">
          <!-- 超级管理员 或普通管理员可以操作用户 -->
          <?php if($u_id==$a_id || $u_role_id==P_R_ID) { ?>
          <a class="button border-blue" href="/Back/User/add"><span class="icon-plus-square-o"></span><?php echo ($action_name["add"]); ?>用户</a>
          <a class="button border-red submit" href="javascript:;" ><span class="icon-trash-o"></span><input type="submit"  value="批量<?php echo ($action_name["del"]); ?>" ></a>
          <?php } ?>
          <div class="right">
              <li>部门
                  <select name="bumen" class="input"  style="width:160px; line-height:17px; display:inline-block" class="bumen">
                      <option value="-1">选择部门</option>
                      <?php if(is_array($bu_men)): foreach($bu_men as $k=>$v): ?><option value="<?php echo ($v); ?>">&nbsp;├ &nbsp;<?php echo ($v); ?></option><?php endforeach; endif; ?>
                  </select>
              </li>
              <li>角色
                  <select name="role_id" class="input"  style="width:160px; line-height:17px; display:inline-block" class="role">
                      <option value="-1">选择角色</option>
                      <?php if(is_array($role_n)): foreach($role_n as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>">&nbsp;├ &nbsp;<?php echo ($v["n"]); ?></option><?php endforeach; endif; ?>
                  </select>
              </li>
              <li>创建人
                  <select name="role_id" class="input"  style="width:160px; line-height:17px; display:inline-block" class="found">
                      <option value="-1">选择创建人</option>
                      <?php if(is_array($found)): foreach($found as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>">&nbsp;├ &nbsp;<?php echo ($v["u_name"]); ?></option><?php endforeach; endif; ?>
                  </select>
              </li>
              <li>
                  <input type="text" placeholder="请输入搜索姓名" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" />
                  <a href="javascript:void(0)" class="button border-main icon-search" > 搜索</a></li>
          </div>

      </ul>
  </div> 
  <table class="table table-hover text-center">
    <tr>
        <?php if($u_id==$a_id || $u_role_id==P_R_ID) { ?>
      <th  width="8%" style="text-align:left; padding-left:20px;"><input type="checkbox" id="checkall"/>全选</th>
        <?php } ?>
      <?php if(($u_id) == $a_id): ?><th>用户编号</th><?php endif; ?>
      <th>用户名</th>
      <th>所在部门</th>
      <th>身份角色</th>
      <th>用户创建人</th>
      <th>操作</th>
    </tr>
   <tbody>
     <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
       <?php if($u_id==$a_id || $u_role_id==P_R_ID) { ?>
       <td>
       <?php if(($a_id) != $v["id"]): if(($u_id) != $v["id"]): ?><input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" /><?php endif; endif; ?>
       </td>
       <?php } ?>
       <?php if(($u_id) == $a_id): ?><td><?php echo ($v["id"]); ?></td><?php endif; ?>
       <td><?php echo ($v["u_name"]); ?></td>
      <td><a href="/Back/User/index?bumen=<?php echo ($v["bumen"]); ?>"><?php echo ($v["bumen"]); ?></a></td>
       <?php if(is_array($role_n)): foreach($role_n as $key=>$role): if(($role["id"]) == $v["role_id"]): ?><td><a href="/Back/User/index?role=<?php echo ($role["id"]); ?>"><?php echo ($role["n"]); ?></a></td><?php endif; endforeach; endif; ?>
      <td>
      <?php if(is_array($found)): foreach($found as $key=>$fv): if(($fv["id"]) == $v["found"]): echo ($fv["u_name"]); endif; endforeach; endif; ?>
      </td>
      <td>
      <div class="button-group">
      <?php if($u_id==$a_id || $u_role_id==P_R_ID) { ?>
       <a class="icon-edit" href="/Back/User/update?id=<?php echo ($v["id"]); ?>"> <?php echo ($action_name["update"]); ?></a>
          <?php if(($u_id) != $v["id"]): ?><a class="icon-trash-o" href="/Back/User/del?id=<?php echo ($v["id"]); ?>" onclick="return confirm('您确定要删除吗?')"> <?php echo ($action_name["del"]); ?></a><?php endif; ?>
       <?php }else{ ?>
      <?php if(($u_id) == $v["id"]): ?><a class="icon-edit" href="/Back/User/update?id=<?php echo ($v["id"]); ?>"> <?php echo ($action_name["update"]); ?></a>
          <?php else: ?>
        <span class="color:#666">不可操作</span><?php endif; ?>
     <?php } ?>
      </div>
      </td>
    </tr><?php endforeach; endif; ?>
  </tbody>  
  </table>
</form>
    <ul class="pagelist">    <?php echo ($show); ?> <span>共 <?php echo ($count); ?> 个用户</span></ul>
</div>
<script>
    $(".icon-search").click(function(){

        var s="/Back/User/index?";

        var bumen=$("select").eq(0).val();
        var role=$("select").eq(1).val();
        var found=$("select").eq(2).val();
        var key=$("ul li>input").val();

        if(bumen!="-1" && role!=undefined)
        {
            s+="bumen="+bumen+"&";
        }
        if(role!="-1" && role!=undefined)
        {
            s+="role="+role+"&";
        }
        if(found!="-1" && role!=undefined)
        {
            s+="found="+found+"&";
        }
        if(key!="")
        {
            s+="key="+key+"&";
        }
        s=s.substring(0,s.length-1);
        $(this).attr("href",s);
    })

</script>

<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>