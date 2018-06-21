<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>首页-<?php echo ($pos["c"]); ?></title>
    <link type="text/css" rel="stylesheet" href="/wx/Public/back/css/pintuer.css" >
    <link type="text/css" rel="stylesheet" href="/wx/Public/back/css/admin.css">
    <script  type="text/javascript" src="/wx/Public/back/js/jquery.js"></script>
    <script type="text/javascript" src="/wx/Public/back/js/pintuer.js"></script>

</head>
<body>
<ul class="bread  clearfix">
    <li><a href="/wx/Back/Index/index" target="right" class="icon-home"> 首页</a></li>
    <li><a href="/wx/Back/Line/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/wx/Back/Line/index"><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--客户下线列表页面-->
<style>
    .submit input{background: none; border:none;color:#f00;}
    .submit:hover input{color:#fff;}
</style>
<div class="panel">
    <form method="post" action="/wx/Back/Line/del">
        <div class="padding">
            <ul class="search" style="padding-left:10px;">
                <div class="right">
                    <li>
                        <select name="rw_state" class="input state"  style="width:150px; line-height:17px; display:inline-block" class="state">
                            <option value="-1">审核状态</option>
                            <option value="1">|-- 未审核状态</option>
                            <option value="2">|-- 通过审核</option>
                            <option value="3">|-- 未通过审核</option>
                        </select>
                    </li>
                    <li>
                        <select name="zx"  class="input zx"  style="width:150px; line-height:17px; display:inline-block" >
                            <option value="-1">按咨询师搜索</option>
                            <option value="all">全部客户</option>
                            <?php if(is_array($all_zx)): foreach($all_zx as $key=>$zx): ?><option value="<?php echo ($zx["id"]); ?>" ><?php echo ($zx["u_name"]); ?></option><?php endforeach; endif; ?>
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

                <th  width="8%" style="text-align:left; padding-left:20px;"><input type="checkbox" id="checkall"/>全选</th>
                <th>用户名</th>
                <th>咨询</th>
                <th>审核状态</th>
                <th>推荐时间</th>
                <th>操作</th>
            </tr>
            <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
                    <td>
                        <input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" />
                    </td>
                    <td><?php echo ($v["n"]); ?></td>
                    <td>
                        <?php if(is_array($all_zx)): foreach($all_zx as $key=>$zx): if(($v["cid"]) == $zx["id"]): echo ($zx["u_name"]); endif; endforeach; endif; ?>
                   </td>

                    <?php if($v["state"] == '1'): ?><td>未审核</td><?php endif; ?>
                    <?php if($v["state"] == '2'): ?><td style="color:#0099FF">通过审核</td><?php endif; ?>
                    <?php if($v["state"] == '3'): ?><td style="color:#ee3333">未通过审核</td><?php endif; ?>

                    <td><?php echo ($v["t"]); ?></td>
                    <td>
                        <div class="button-group">
                            <?php if($v["state"] == '1' AND $v["cid"] == $u_id): ?><a class="icon-edit" href="/wx/Back/Line/censor?id=<?php echo ($v["id"]); ?>&type=edit"> 审核</a>
                                <?php else: ?>
                                <a class="icon-eye-open" href="/wx/Back/Line/censor?id=<?php echo ($v["id"]); ?>&type=eye"> 查看</a><?php endif; ?>
                        </div>
                    </td>
                </tr><?php endforeach; endif; ?>
            </tbody>
        </table>
    </form>
    <ul class="pagelist">    <?php echo ($page); ?>  <span>共 <?php echo ($count); ?> 个用户</span></ul>
</div>
<script  type="text/javascript">
    $(".icon-search").click(function(){
        //
        var s="/wx/Back/Line/index?";
        var state=$(".search").find("select").eq(0).val();
        var zx=$(".search").find("select").eq(1).val();
        var key=$(".search").find("select").eq(2).val();

        var key=$(".search .keywords").val();

        if(state!="-1")
        {
            s+="state="+state+"&";
        }
        if(zx!="-1")
        {
            s+="cid="+zx+"&";
        }
        if(key!="" && key!=undefined)
        {
            s+="key="+key+"&";
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
    var state_id=Request["state"];
    var state_list=$(".state option");
    state_list.each(function(i,ele){
        if(state_list.eq(i).val()==state_id)
        {
            state_list.eq(i).attr("selected",true);
        }
    });
    var zx_id=Request['cid'];
    var zx_list=$(".zx option");
    zx_list.each(function(i,ele){
        if(zx_list.eq(i).val()==zx_id)
        {
            zx_list.eq(i).attr("selected",true);
        }
    });

</script>

<script type="text/javascript" src="/wx/Public/back/js/arc_list.js"></script>
</body></html>