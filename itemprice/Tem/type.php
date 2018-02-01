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
    <li><a href="<?php echo $all_data["p_url"];?>" id="a_leader_txt"><?php echo $all_data["nav"];?>管理</a></li>
    <li><a href="<?php echo  $_SERVER['PHP_SELF'] ;?>"><?php echo $all_data["nav"].$all_data["action"];?></a></li>
</ul>
<div class="panel">
    <form  method="post">
        <div class="padding">
            <a class="button border-main" href="?h=add"><span class="icon-plus-square-o"></span> 添加<?php echo $all_data["nav"];?></a>
            <a class="button border-yellow"><span class="icon-edit"></span><input  value="批量修改" class="a_edit"  onclick="DelSelect($(this),'修改','id')" name="edits" type="button" ></a>
            <a href="javascript:;" class="button border-red" >
                <span class="icon-trash-o"></span><input  value="批量删除" class="a_del" name="dels" type="button"  onclick="DelSelect($(this),'删除','id')"></a>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th  width="15%" style="text-align:left; padding-left:20px;"><input type="checkbox" id="checkall"/><span>全选</span></th>
                <th>编号</th>
                <th><?php echo $all_data["nav"];?></th>
                <th>操作</th>
            </tr>
                <?php
                    if(!isset($list) || empty($list))
                    {   echo "<tr><td colspan='4'>暂无数据</td></tr>";}
                    else
                    {
                        foreach($list as $v)
                        {
                            echo '<tr><td><input type="checkbox" name="id[]" value="'.$v["id"].'" /></td><td>'.$v["id"].'</td>';
                            echo '<td><a href="'.PHP_CON.$this->c_file.'.php?h=index&'.$this->g_id_field.'='.($v["id"]-1).'">'.$v["n"].'</a></td>
                            <td><div class="button-group">
                        <a class="button border-blue" href="'.PHP_CON.$this->c_file.'.php?h=add&'.$this->g_id_field.'='.($v["id"]-1).'"><span class="icon-plus-square-o"></span>添加'.$this->c_name_c.'</a>
                        <a class="button border-yellow" href="?h=edit&id='.$v["id"].'"><span class="icon-edit"></span>修改</a>
                        <a class="button border-red" data-href="?h=del&id='.$v["id"].'" href="javascript:;"  onclick="del($(this),\'删除\')"><span class="icon-trash-o"></span> 删除</a>
                         </div></td></tr>';
                        }
                    }
                ?>

        </table>
    </form>
    <div class="pagelist">
        <?php
        if(isset($list) && !empty($list))
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
</div>
<script src="/Static/js/arc_list.js"></script>
</body></html>