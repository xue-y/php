<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台管理中心</title>
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <link rel="stylesheet" href="/Static/css/admin.css">
    <script src="/Static/js/jquery.js"></script>
</head>
<body>
<div class="header bg-main">
    <div class="logo margin-big-left fadein-top">
        <h1><img src="/Static/img/y.jpg" class="radius-circle rotate-hover" height="50" />后台管理中心</h1>
    </div>
    <div class="head-l">
        <a class="button button-little bg-green" href="/Php/Controll/Price.php" target="_blank"><span class="icon-home"></span> 前台首页</a> &nbsp;&nbsp;
        <a href="javascript:;" class="button button-little bg-blue" target="right"><span class="icon-wrench"></span> 数据清理</a> &nbsp;&nbsp;
        <a class="button button-little bg-red" href="/Php/Controll/Login.php?action=out"><span class="icon-power-off"></span> 退出登录</a> </div>
</div>
<div class="leftnav">
    <div class="leftnav-title"><strong><span class="icon-list"></span>菜单列表</strong></div>
    <p class="on"><a class="icon-home" href="/Php/Controll/Index.php" target="right">后台首页</a></p>
    <?php
    foreach($all_data["menu"] as $v)
    {
        if($v["n"]=="数据管理")
        {
            echo '<h2 class="click_tab"><span class="icon-book"></span>'.$v["n"].'</h2>
                      <ul>';
                       foreach($all_data["data_url"] as $kk=>$vv)
                       {
                          echo '<li><a href="'.PHP_CON.$kk.self::$conf_data["TEM_EXT"].'" target="right">'.$vv.'</a></li>';
                       }

             echo '</ul> ';
        }else
        {
            echo '<p class="on"><a class="'.$v["icon"].'" href="'.PHP_CON.$v["url"].'" target="right">'.$v["n"].'</a></p>';
        }
    }
    ?>
    <script type="text/javascript">
    $(function(){
        (function(){  //-------------------下来菜单
            $(".leftnav h2").click(function(){
                $(this).next().slideToggle(200);
                $(this).toggleClass("on");
            });
            $(".leftnav ul li a").click(function(){
                $("#a_leader_txt").text($(this).text());
                $(".leftnav ul li a").removeClass("on");
                $(this).addClass("on");
            });
        })();
        (function(){  //----------------清理文件
            $(".bg-blue").click(function(){
                $.get("/Php/Controll/Clear.php",function(data)
                {
                   var data=JSON.parse(data);
                  //  var data=jQuery.parseJSON(data);
                    $str=" 文件夹清理失败 请手动清理";
                    if( data.user!= true)
                    {
                        alert("/Data/Export/User/"+$str);
                    }
                    if( data.admin!= true)
                    {
                        alert("/Data/Export/Admin/"+$str);
                    }
                    if( data.uptemp!= true)
                    {
                        alert("/Data/Uptemp/"+$str);
                    }
                    if(data.u_data!=true || data.p_data!=true || data.d_data!=true)
                    {
                        alert("数据文件清理失败，请查看日志文件");
                    }
                    if(data.user==true &&  data.admin==true && data.uptemp==true && data.u_data==true && data.p_data==true && data.d_data==true)
                    {
                        alert("清理完成");
                    }
                });
            });
        })();
    })
    </script>
</div>
<div class="admin">
    <iframe rameborder="0" src="/Php/Controll/Index.php" name="right" width="100%" height="100%" scrolling="auto"></iframe>
</div>
</body>
</html>