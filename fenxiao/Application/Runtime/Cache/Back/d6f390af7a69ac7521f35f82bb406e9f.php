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
    <li><a href="/Back/Role/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Role/update"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--角色添加页面-->
        <style>
            .ul{margin-bottom: 50px;}
            h4{padding-left: 40px; padding-top: 20px;}
            h5{padding-left: 60px; margin-bottom: 5px}
            .none{display: none}
        </style>
<div class="panel  admin-panel">
  <form method="post" action="/Back/Role/execUate" class="form-x">
  <div class="body-content">
      <p class="red">角色名称、角色描述 为空为不修改</p>
        <input type="hidden" name="id" value="<?php echo ($r_info["id"]); ?>">

      <div class="form-group">
        <div class="label">
          <label >角色名称：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="n" value="<?php echo ($r_info["n"]); ?>" data-validate="title:角色名称长度2--20个字符" />
          <div class="tips"></div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>角色描述：</label>
        </div>
        <div class="field">
        <textarea type="text"  class="input" name="descr" style="height:100px;" data-validate="descipt:角色描述长度5--50个字符"><?php echo ($r_info["descr"]); ?>
        </textarea>
        </div>
     </div>
    <?php if(isset($s_l_a)) { foreach($s_l_a[0] as $k=>$v) { if(in_array($v['id'],$my_l)) { echo '<ul class="ul"><h3><input type="checkbox" name="limit_id[]" checked value='.$v["id"].'>'.$v["n"].'</h3>'; }else { echo '<ul ><h3><input type="checkbox" name="limit_id[]" value='.$v["id"].'>'.$v["n"].'</h3>'; } foreach($s_l_a[1] as $kk=>$vv) { if($v["pid"]==$vv["pid"]) if(in_array($vv['id'],$my_l)) { echo '<h4 class="clear"><input type="checkbox" name="limit_id[]"  checked  value='.$vv["id"].' class='.$v["id"].' >'.$vv["n"].'</h4>'; } else { echo '<h4 class="clear"><input type="checkbox" name="limit_id[]"  value='.$vv["id"].' class='.$v["id"].' >'.$vv["n"].'</h4>'; } foreach($s_l_a[2] as $vvv) { if(stripos($vvv["execs"],$vv["execs"])!==FALSE && $vvv["pid"]==$v["pid"]) { if(stripos($vvv["execs"],"exec")!==FALSE) { if(in_array($vvv['id'],$my_l)){ echo '<h5 class="float-left w50 none"><input type="checkbox" checked  name="limit_id[]"  value='.$vvv["id"].'  class='.$vv["id"].' >'.$vvv["n"].'</h5>'; }else { echo '<h5 class="float-left w50 none"><input type="checkbox" name="limit_id[]"  value='.$vvv["id"].'  class='.$vv["id"].' >'.$vvv["n"].'</h5>'; } } else { if(in_array($vvv['id'],$my_l)) { echo '<h5 class="float-left w50"><input type="checkbox" checked name="limit_id[]"  value='.$vvv["id"].'  class='.$vv["id"].' >'.$vvv["n"].'</h5>'; }else { echo '<h5 class="float-left w50"><input type="checkbox" name="limit_id[]"  value='.$vvv["id"].'  class='.$vv["id"].' >'.$vvv["n"].'</h5>'; } } } } } echo '<hr/></ul>'; } } ?>

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
  </form>
</div>

<script>
    $("h4").each(function(){
        $(this).find("input").click(function(){
            var val=$(this).val();
            if($(this).is(':checked')==true)
            {
                $("."+val).prop("checked",true);
            }else
            {
                $("."+val).prop("checked",false);
            }
        });
    });
    $("h3").each(function(){
        $(this).find("input").click(function(){
            console.log($(this).is(':checked'));
            var val=$(this).val();
            if($(this).is(':checked')==true)
            {

                $("."+val).prop("checked",true);
                $("."+val).each(function(){
                    var val2=$(this).val();
                    $("."+val2).prop("checked",true);
                })
            }else
            {
                $("."+val).prop("checked",false);
                $("."+val).each(function(){
                    var val2=$(this).val();
                    $("."+val2).prop("checked",false);
                })
            }
        });
    });
    $("h5").each(function(){
        var h5_t=$(this);
        $(this).find("input").click(function(){
            if($(this).is(':checked')==true && h5_t.next().hasClass("none"))
            {
                h5_t.next().find('input').prop("checked",true);
            }
            else if($(this).is(':checked')!=true && h5_t.next().hasClass("none"))
            {
                h5_t.next().find('input').prop("checked",false);
            } //-------------------------------选择执行的添加。修改 id
            var T=$(this).attr('class');
            $("h4").each(function(){
                var val3=$(this).find("input").val();
                if(val3==T)
                {
                    var f_c=$(this).find("input").attr('class');
                    $("h3 input").each(function(){
                        if($(this).val()==f_c)
                            $(this).prop("checked",true);
                    });
                    $(this).find("input").prop("checked",true);
                }
            })
        });
    });
</script>
</body></html>
<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>