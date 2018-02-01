<!DOCTYPE html>
<html lang="zh-cn">
<head><!-- 用户访问html  模板页面-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title><?php echo $all_data["web_name"];?></title>
    <link rel="stylesheet" href="/Static/css/admin.css">
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <script src="/Static/js/jquery.js"></script>
    <script>
        $(function(){
            $(".down_f").each(function(i,ele){
                var t=$(this);
                $(this).click(function(){
                    var down=t.attr('data-down');
                    ajax_re(down);
                });
            })
            function ajax_re(down)
            {
                $.post("/Php/Controll/Down.php",{down:down},function(data){
                    if(data!="-1")
                    {
                        window.location.href=data;
                    }else
                    {
                        alert("下载失败");
                    }
                });
            }
        })
    </script>
</head>
<body>

<div class="panel padding">
    <h1 class="text-center padding"><?php echo $all_data["web_name"];?></h1>
    <p class="down left">
        <a href="<?php echo  $_SERVER['PHP_SELF'] ;?>"  class="button border-main"><span class="icon-home"></span> 查看全部</a>
        <a href="javascript:;"  data-down="excel" class="button border-main down_f"><span class="icon-download-alt"></span> 导出Excel</a>
        <a href="javascript:;"  data-down="html" class="button border-main down_f"><span class="icon-cloud-download"></span> 导出网页Html</a>
        <b>如果您的电脑不支持 office 2007 Excel 请选择网页格式</b>
    </p>
    <p class="right" style="margin:15px 20px"><?php echo $_SESSION["n"]; ?>
    <a href="/Php/Controll/User.php?h=edit&u_id=<?php echo $_SESSION["id"]; ?>" style="margin: 0px 10px">修改信息</a>
    <a href="/Php/Controll/Login.php?action=out">退出</a>
    </p>
    <table class="table table-hover text-center">
        <tr>
            <?php
            foreach($all_data["tit"] as $v)
            {
                echo "<th>$v</th>";
            }
            ?>
        </tr>
        <?php
        if(empty($all_data["con"]))
        {
            if(empty($all_data["price"]))
            {
                echo "<tr><td colspan='".count($all_data["tit"])."'>暂无数据</td></tr>";
            }else
            {
                echo "<tr><td colspan='".count($all_data["tit"])."'>暂无数据</td></tr>";
            }
        }else
        {
            foreach($all_data["con"] as $v)
            {
                echo '<tr>
                    <td>'.$v["n"].'</td>';
                  if(empty($all_data["price"])) // 管理员
                  {
                    foreach($all_data["key"] as $price_v)
                    {
                        echo '<td>'.$v[$price_v].'</td>';
                    }
                      echo '<td><a href="?t_id='.$v["t_id"].'">'.$v["t_n"].'</a></td>';
                  }else                           // 普通组员
                  {
                      echo  '<td>'.$v[$all_data["price"]].'</td><td>'.$v["t_n"].'</td>';
                  }
                echo'<td>'.$v["t"].'</td>
                    <td>'.$v["bz"].'</td>
                   </tr>' ;
            }
        }
        ?>

    </table>
</div>
<div class="pagelist">
    <?php
    if(isset($list) && !empty($list)) //$all_data["con"]
    {
        /*$page = Com::getInstance("Page") ;
       $page->show($total, $showrow, $curpage, $url);
       $page->myde_write();*/ //Page.php

        $page = Com::getInstance("Spage") ;
        $page->show($total,$showrow,$show_page); // Spage.php

        /*$page=Com::getInstance("Tpage");
         $page->init($total,$showrow,$show_page);
         $page->show();*/ //1 2 3 >> Tpage.php
    }
    ?>
</div>
</body>
</html>