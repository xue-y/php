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
    <li><a href="/Back/Money/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Money/index"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--客户佣金详情列表页面-->
 <div class="padding">
      <ul class="search" style="padding-left:10px;">

          <div class="right">
              <li>
                  <select name="zx"  class="input"  style="width:150px; line-height:17px; display:inline-block" >
                      <option value="-1">按咨询师搜索</option>
                      <?php if(is_array($all_zx)): foreach($all_zx as $key=>$zx): ?><option value="<?php echo ($zx["id"]); ?>" ><?php echo ($zx["u_name"]); ?></option><?php endforeach; endif; ?>
                  </select>
              </li>
             
              <li>
                  <input type="text" placeholder="请输入客户编号" class="input id" style="width:250px; line-height:17px;display:inline-block" />
                  <a href="javascript:void(0)" class="button border-main icon-search" > 搜索</a></li>
          </div>
      </ul>
  </div> 
<div class="panel padding-big-top">
  <table class="table table-hover text-center">
    <tr>
      <th>客户编号</th>
      <th>操作管理员</th>
      <th>交易金额</th>
      <th>操作后金额</th>
      <th>时间</th>
      <th>备注</th>
    </tr>
   <tbody>
    <?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$v): ?><tr>
       <td><a href="/Back/Customer/update?id=<?php echo ($v["id"]); ?>"><?php echo ($v["id"]); ?></a></td>
       <td>
           <?php if(is_array($all_zx)): foreach($all_zx as $key=>$zx): if($v["cid"] == $zx["id"] ): echo ($zx["u_name"]); endif; endforeach; endif; ?>
       </td>
       <td><?php echo ($v["num"]); ?></td>
       <td><?php echo ($v["jine"]); ?></td>
       <td><?php echo ($v["t"]); ?></td>
       <td><?php echo (str_slashes($v["info"])); ?></td>
      </tr><?php endforeach; endif; endif; ?>
  </tbody>  
  </table>
 <?php if(!empty($list)): ?><ul class="pagelist"> <?php echo ($page); ?> <span>共 <?php echo ($count); ?> 条记录</span></ul><?php endif; ?>
</div>
<script  type="text/javascript">
    $(".icon-search").click(function(){
        //
        var s="/Back/Money/index?";
        
        var id=$(".id").val();
        var zx=$(".search").find("select").eq(0).val();
        
        if(id!="" && id!=undefined)
        {
            s+="id="+id+"&";
        }
        if(zx!="-1")
        {
            s+="zx="+zx+"&";
        }
        s=s.substring(0,s.length-1);
        $(this).attr("href",s);
    });

    // 取得url 参数-- 必须当前页面调用否则无效
    function GetRequest() {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
              //  theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
              theRequest[strs[i].split("=")[0]]=strs[i].split("=")[1];
            }
        }
        return theRequest;
    }
    var Request = new Object();
    Request = GetRequest();
    // 选中搜索条件
    $("#inpstart").val(Request['t_start']);
    $("#inpend").val(Request['t_end']);
    var zx_id=Request['zx'];

    var zx_list=$("select option");
    zx_list.each(function(i,ele){
      if(zx_list.eq(i).val()==zx_id)
      {
          zx_list.eq(i).attr("selected",true);
      }
    });
</script>


<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>