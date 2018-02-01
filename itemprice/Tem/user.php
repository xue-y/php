<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title><?php echo $all_data["nav"].$all_data["action"];?></title>
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <link rel="stylesheet" href="/Static/css/admin.css">
    <script src="/Static/js/jquery.js"></script>
</head>
<body>
<ul class="bread  clearfix">
    <?php echo $this->index_url;?>
    <li><a href="<?php echo $this->g_url;?>" target="right"> <?php echo $this->c_name_g;?>管理</a></li>
    <li><a href="<?php echo $all_data["p_url"];?>" id="a_leader_txt"><?php echo $all_data["nav"];?>管理</a></li>
    <li><a href="<?php echo  $_SERVER['PHP_SELF'] ;?>"><?php echo $all_data["nav"].$all_data["action"];?></a></li>
</ul>
<div class="panel">
    <form  method="post">
    <div class="padding">
        <a class="button border-main" href="?h=add"><span class="icon-plus-square-o"></span> 添加<?php echo $all_data["nav"];?></a>
        <a href="javascript:;" class="button border-red" ><span class="icon-trash-o"></span>
            <input  value="批量删除" class="a_del" name="dels" type="button"  onclick="DelSelect($(this),'删除','<?php echo $this->id_field ;?>')">
        </a>
    </div><!--仅限管理组员有此权限-->
    <table class="table table-hover text-center">
        <tr>
            <th  width="15%" style="text-align:left; padding-left:20px;"><input type="checkbox" id="checkall"/><span>全选</span></th>
           <!-- <th>用户ID</th>-->
            <th><?php echo $all_data["nav"];?>名</th>
            <th><?php echo $all_data["nav"];?>所属组</th>
            <th>操作</th>
        </tr>
        <?php
            if(!isset($list) || empty($list))
            {   echo "<tr><td colspan='4'>暂无数据</td></tr>";}
            else
            {
                foreach($list as $v)
                {
                   echo '<tr>
                    <td><input type="checkbox" name="'.$this->id_field.'[]" value="'.$v["id"].'" /></td>
                    <td>'.$v["n"].'</td>
                    <td>';
                    ?>
                    <?php
                    if(is_array($all_data["g"]))
                    {
                        echo "<a href='?".$this->g_id_field.'='.$v[$this->g_id_field]."'>".$all_data["g"][$v[$this->g_id_field]]."</a>";
                    }else
                    {
                        echo $all_data["g"]; //点击类型分组
                    }
                      ;?>
                       </td>
                    <td>
              <a type="button" class="button border-yellow" href="?h=edit&<?php echo $this->id_field.'='.$v["id"]; ?>"><span class="icon-edit"></span>修改</a>
                        <a class="button border-red" data-href="?h=del&<?php echo $this->id_field.'='.$v["id"]; ?>" href="javascript:;"  onclick="del($(this),'删除')"><span class="icon-trash-o"></span> 删除</a><!--删除用户仅限管理组员可以删除，并且管理员不可删除自己-->
                    </td>
                </tr>
        <?php
                }
        } ?>

    </table>
    </form>
    <div class="pagelist">
        <?php
        if(isset($list) && !empty($list))
        {
            /*$page = Com::getInstance("Page") ;
                $page->show($total, $showrow, $curpage, $url);
                $page->myde_write(); *///Page.php

            $page = Com::getInstance("Spage") ;
            $page->show($total,$showrow,$show_page);// Spage.php

            /* $page=Com::getInstance("Tpage");
              $page->init($total,$showrow,$show_page);
              $page->show();*/ //1 2 3 >> Tpage.php
        }
        ?>
    </div>
</div>
<script src="/Static/js/arc_list.js"></script>
</body>
</html>