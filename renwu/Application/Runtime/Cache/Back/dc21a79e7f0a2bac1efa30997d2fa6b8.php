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
    <li><a href="/Back/Recovery/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Recovery/index"><?php echo ($pos["a"]); ?></a></li>
</ul>
<!--任务列表页面-->
<link rel="stylesheet" type="text/css" href="/Public/back/css/time.css">
<script src="/Public/back/js/time.js"></script>
<style>
    .submit input{background: none; border:none;}
    .border-yellow  input{color:#f60;}
    .border-red  input{color:#f00;}
    .submit:hover input{color:#fff;}
    .font-size16{font-size: 16px; margin-top: 20px;margin-left:5px;}
    .font-size16 a{margin: 0px 10px;}
</style>
<div class="panel">
<form method="post" action="" >
  <div class="padding">
      <ul class="search" style="padding-left:10px;">
          <a class="button border-yellow submit" href="javascript:;" ><span class="icon-edit"></span><input type="submit"  value="批量<?php echo ($action_name["restore"]); ?>" onclick="restore()" ></a>
          <a class="button border-red submit" href="javascript:;" ><span class="icon-trash-o"></span><input type="submit"  value="批量<?php echo ($action_name["del"]); ?>" onclick="del()"></a>

        <?php if(isset($is_admin)): ?><div class="right">
              <li>状态
                  <select name="rw_state" class="input"  style="width:120px; line-height:17px; display:inline-block" class="state">
                      <option value="-1">任务状态</option>
                      <?php if(is_array($rw_state)): foreach($rw_state as $k=>$v): ?><option value="<?php echo ($v); ?>">&nbsp;├ &nbsp;<?php echo ($k); ?></option><?php endforeach; endif; ?>
                  </select>
              </li>
              <li>
                  <input class="datainp input" id="inpstart" type="text" placeholder="开始日期" readonly="">
              </li>
              <li><input class="datainp input" id="inpend" type="text" placeholder="结束日期" readonly=""></li>
              <li>部门
                  <select name="bumen" class="input"  style="width:120px; line-height:17px; display:inline-block"  class="bm">
                      <option value="-1">选择部门</option>
                      <?php if(is_array($bu_men)): foreach($bu_men as $k=>$v): ?><option value="<?php echo ($v); ?>">&nbsp;├ &nbsp;<?php echo ($v); ?></option><?php endforeach; endif; ?>
                  </select>
              </li>
              <li>
                  <input type="text" placeholder="搜索用户名或问题标题"  class="input keywords" style="width:190px; line-height:17px;display:inline-block" />
                  <a href="#" class="button border-main icon-search" > 搜索</a></li>
          </div><?php endif; ?>
      </ul>
<hr/>

  </div> 
  <table class="table table-hover text-center">
    <tr>
      <th  width="8%" style="text-align:left; padding-left:20px;"><input type="checkbox" id="checkall"/>全选</th> 
      <th width="5%">问题ID</th>
      <th>问题标题</th>
      <th>所在部门</th>
      <th>提交任务人员</th>
      <th>任务状态</th>
      <th>时间</th>
      <th width="450">操作</th>
    </tr>
   <tbody>

     <?php if($count > 0 ): if(is_array($list)): foreach($list as $key=>$v): ?><tr>
       <td>
           <input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" />
       </td>
       <td><?php echo ($v["id"]); ?></td>
       <td><?php echo (mb_substr($v["tit"],0,10,'utf-8')); ?></td>
        <?php if(is_array($u_info)): foreach($u_info as $key=>$uv): if(($uv["id"]) == $v["u_id"]): ?><td><a href="/Back/Recovery/index?bumen=<?php echo ($uv["bumen"]); ?>"><?php echo ($uv["bumen"]); ?></a></td>
                <td><a href="/Back/Recovery/index?u_id=<?php echo ($uv["id"]); ?>"><?php echo ($uv["u_name"]); ?></a></td><?php endif; endforeach; endif; ?>
       <?php if(is_array($rw_state)): foreach($rw_state as $key=>$rwv): if(($v["state"]) == $rwv): ?><td><a href="/Back/Recovery/index?state=<?php echo ($v["state"]); ?>"><?php echo ($key); ?></a></td><?php endif; endforeach; endif; ?>

       <td><?php echo (date("Y-m-d H:i:s",$v["times"])); ?></td>
       <td>
       <a class="icon-edit" href="/Back/Recovery/restore?id=<?php echo ($v["id"]); ?>"> <?php echo ($action_name["restore"]); ?></a>
       <a class="icon-trash-o" href="/Back/Recovery/del?id=<?php echo ($v["id"]); ?>" onclick="return confirm('您确定要删除吗?')"> <?php echo ($action_name["del"]); ?></a>
      </div>
      </td>
     </tr><?php endforeach; endif; ?>

    <?php else: ?>
       <tr class="font-size16"><td colspan="8">暂无任务</td></tr><?php endif; ?>

   </tbody>
  </table>
</form>
    <ul class="pagelist">    <?php echo ($show); ?> <span>共 <?php echo ($count); ?> 个任务</span></ul>
</div>
<script type="text/javascript">
//时间插件
    var start = {
        dateCell: '#inpstart',
  //      format: 'YYYY-MM-DD hh:mm',
        format: 'YYYY-MM-DD',
        minDate: '2014-06-16 23:59:59', //设定最小日期为当前日期
        festival:true,
        maxDate: '2099-06-16 23:59:59', //最大日期
        isTime: true,
        choosefun: function(datas){
            end.minDate = datas; //开始日选好后，重置结束日的最小日期
        }
    };
    var end = {
        dateCell: '#inpend',
  //     format: 'YYYY-MM-DD hh:mm',
        format: 'YYYY-MM-DD',
        minDate: jeDate.now(0), //设定最小日期为当前日期
        festival:true,
        maxDate: '2099-06-16 23:59:59', //最大日期
        isTime: true,
        choosefun: function(datas){
            start.maxDate = datas; //将结束日的初始值设定为开始日的最大日期
        },
        okfun:function(val){alert(val)}
    };
    jeDate(start);
    jeDate(end);

    jeDate({
        dateCell:"#textymdhms",
        format:"YYYY-MM-DD hh:mm:ss",
        //isinitVal:true,
        isTime:true,
        festival: true, //显示节日
        minDate:"2015-09-19 00:00:00",
        maxDate:"2019-09-19 00:00:00"
    })

    jeDate({
        dateCell:"#texthms",
        format:"hh:mm"
    });

    jeDate({
        dateCell: '#testym',
        isinitVal:true,
        format: 'YYYY/MM', // 分隔符可以任意定义，该例子表示只显示年月
        minDate: '2015-06-01', //最小日期
        maxDate: '2017-06-01',  //最大日期
        choosefun:function(val){alert(val)}
    });
    jeDate({
        dateCell: '#testy',
        isinitVal:true,
        format: 'YYYY-MM-DD', // 分隔符可以任意定义，该例子表示只显示年月
        minDate: '2010-06-01', //最小日期
        maxDate: '2020-06-01' //最大日期
    })
    jeDate({
        dateCell:"input.datep",
        format:"YYYY-MM-DD hh:mm:ss",
        minDate:"2015-09-19 00:00:00",
        isinitVal:true,
        isDisplay:true,
        displayCell:".discls",
        isTime:true,
        festival: true //显示节日
    })
</script>
<script>
   $(".icon-search").click(function(){
     //
      var s="/Back/Recovery/index?";
      var t_start=$("#inpstart").val()
      var t_end=$("#inpend").val()
      var state=$(".search").find("select").eq(0).val();
      var bumen=$(".search").find("select").eq(1).val();

      var key=$(".search .keywords").val();
      if(t_start!='')
      {
        s+="t_start="+t_start+"&";
      }
      if(t_end!="")
      {
        s+="t_end="+t_end+"&";
      }
      if(bumen!="-1")
      {
        s+="bumen="+bumen+"&";
      }
       if(state!="-1")
       {
           s+="state="+state+"&";
       }
      if(key!="")
      {
          s+="key="+key+"&";
      }
       s=s.substring(0,s.length-1);
     $(this).attr("href",s);
   })

</script>
<script>
    function restore()
    {
        $("form").attr("action","/Back/Recovery/restore");
    }
    function del()
    {
        $("form").attr("action","/Back/Recovery/del");
    }
</script>
<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>