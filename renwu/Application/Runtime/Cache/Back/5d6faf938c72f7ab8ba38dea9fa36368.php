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
    <li><a href="/Back/Task/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Task/ute"><?php echo ($pos["a"]); ?></a></li>
</ul>
<!--任务添加页面-->
        <style>
            h4{padding-left: 40px; padding-top: 20px;}
            h5{padding-left: 60px; margin-bottom: 5px}
            .none{display: none}
        </style>

<link rel="stylesheet" href="/Public/kindeditor-4.1.10/themes/default/default.css" />
<script charset="utf-8" src="/Public/kindeditor-4.1.10/kindeditor-min.js"></script>
<script charset="utf-8" src="/Public/kindeditor-4.1.10/lang/zh_CN.js"></script>

<script>
    KindEditor.ready(function(K) {
        var editor = K.create('textarea[name="readonly"]', {
            readonlyMode : true
        });
        // 设置成只读状态
        editor.readonly();
        var editor2 = K.create('textarea[name="content"]', {
            readonlyMode : true
        });
        editor2.readonly(false);
    });

</script>

<div class="panel  admin-panel">

  <div class="body-content">
    <div  class="form-x">
      <div class="form-group">
        <div class="label">
          <label >问题标题：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" readonly="" value="<?php echo ($pro["tit"]); ?>"  />
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>问题描述：</label>
        </div>
        <div class="field">
            <textarea  name="readonly"    class="text_read"><?php echo ($pro["descr"]); ?></textarea>
        </div>
     </div>
     <div class="form-group">
            <div class="label">
                <label >提交人姓名：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" readonly="" value="<?php echo ($pro["u_name"]); ?>"  />
            </div>
     </div>
        <div class="form-group">
            <div class="label">
                <label >提交人部门：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" readonly="" value="<?php echo ($pro["bumen"]); ?>"  />
            </div>
        </div>
      <div class="form-group">
            <div class="label">
                <label>任务状态：</label>
            </div>
            <div class="field">
                <div class="button-group radio">
                    <?php foreach($state as $k=>$v) { if($pro["state"]==$v) { echo '<label class="button active" ><input value="'.$v.'" type="radio">'.$k.'</label>'; } } ?>

                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label >提交时间：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" readonly="" value='<?php echo (date("Y-m-d H:i:s",$pro["times"])); ?>'  />
            </div>
        </div>
        <!--如果未完成的 任务 有一个去执行的选框  自己的提交的任务不可执行-->
        <?php if(($pro["state"] == 0) AND ($this_u == 0) ): ?><form method="post" action="/Back/Task/execUte">
            <input type="hidden" value="<?php echo ($pro["id"]); ?>" name="id"><!--问题ID-->
            <div class="form-group">
                <div class="label">
                    <label >执行任务：</label>
                </div>
                <div class="field" style=" line-height: 38px">
                    <input type="checkbox" class="input" name="state" value='0' style="display: inline-block;" />去执行
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>解决方案：</label>
                </div>
                <div class="field">
                    <textarea  name="content"></textarea>
                </div>
            </div>

            <div class="field">
                <?php if(($pro["state"]) == "0"): ?><button class="button bg-main" type="submit">保存提交</button>
                    &nbsp;  &nbsp;  &nbsp;<?php endif; ?>
                <button class="button bg-main" type="button" onClick="javascript :history.back(-1);">直接返回</button>
            </div>
          </form><?php endif; ?>

        <div  style="font-size:16px; margin:20px 30px 50px">
            <a href="javascript:;" style="color:#09F;margin-right: 40px" onclick="see_task(<?php echo ($pro["id"]); ?>)">查看是否有人执行过此任务</a>
            <?php if(($pro["state"]) != "0"): ?><button class="button bg-main" type="button" onClick="javascript :history.back(-1);">直接返回</button><?php endif; ?>
        </div>

        <div class="ajax none">
        <!--查看是否有人执行过此任务-->
        <div class="form-group">
                <label  class="label">执行人数：</label>
                <input type="text" class="input w50 sum" readonly=""  value="" />
        </div>
        <hr/>

        <div class="for"></div>

     </div>

  </div>
</div>
</div>
<script>
    $("form").submit(function(){
      if($(this).find("input[type=checkbox]").is(':checked')==false)
      {alert("请勾选执行任务选框");return false;};
    })
    function see_task(id)
    {
        $.ajax({
            type:"POST",
            url:"/Back/Task/ute",
            data:{see:"task",id:id},
            success:function(data){
               if(data==0)
               {
                   $(".ajax").before("<p style='margin:-30px 0px 0px 20px'>暂无人执行此任务</p>");
                   return;
               }
                else
               {
                    var c=data.length-1;
                    $('.ajax').removeClass("none");
                    var html="";
                    $('.sum').val("此任务共 "+data[c].sum+" 人执行过 "+c+" 次");
                    $.each(data,function(i,obj){
                      if(i<c)
                      {
                          html='<div class="form-group"><label  class="label"> 执行人：</label><input type="text" class="input" readonly=""  value="'+data[i].u_name+'" /></div>';
                          html+='<div class="form-group"><label  class="label"> 执行人部门：</label><input type="text" class="input"  readonly="" value="'+data[i].bumen+'" /></div>';
                          html+='<div class="form-group"><label  class="label"> 执行时间：</label><input type="text" class="input"  readonly="" value="'+data[i].times+'" /></div>';
                          html+='<div class="form-group"><label  class="label"> 执行方案：</label><div class="border"  readonly="" >'+data[i].plan+'</div></div>';
                          html+='<div class="form-group"><label  class="label"> 执行状态：</label><div class="button-group radio"><label class="button active" ><input type="radio" class="state"   value="">'+data[i].state+'</label></div> <div class="bor"></div>';
                          $('.for').append(html);
                      }
                    })
               }

            },
            error:function(xhr){},
            timeout:5000
        });
    }


</script>
</body></html>
<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>