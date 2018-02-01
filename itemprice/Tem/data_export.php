<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title><?php echo $all_data["nav"].$all_data["action"].'---'.$tit;?></title>
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <link rel="stylesheet" href="/Static/css/admin.css">
    <script src="/Static/js/jquery.js"></script>
</head>
<body><!--数据管理----导出 还原 备份列表-->
<ul class="bread  clearfix">
    <?php echo $this->index_url;?>
    <li><a href="<?php echo  $_SERVER['PHP_SELF'] ;?>" id="a_leader_txt"><?php echo $all_data["nav"];?>管理</a></li>
    <li><a href="<?php echo  $_SERVER['PHP_SELF'] ;?>"><?php echo $all_data["nav"].$all_data["action"].'---'.$tit;?></a></li>
</ul>
<div class="panel">
        <form   method="post">
                <p class="export clear">
                    <a href="javascript:;" class="button border-blue" ><span class="icon-reply"></span>
                        <input type="button" onclick="DelSelect($(this),'还原','id')" class="a_reply"  value="还原数据" name="export_edits">
                    </a>
                    <a href="javascript:;"  class="button border-main"><span class="icon-cloud-download"></span>
                        <input type="button" onclick="DelSelect($(this),'导出','id')"  value="导出数据txt" name="export_txt">
                    </a>
                    <a href="javascript:;"  class="button border-main"><span class="icon-download-alt"></span>
                        <input type="button" onclick="DelSelect($(this),'导出','id')"  value="导出数据excel" name="export_excel">
                    </a>
                    <a href="javascript:;" class="button border-red" ><span class="icon-trash-o"></span>
                        <input type="button" onclick="DelSelect($(this),'删除','id')" class="a_del" value="批量删除" name="export_dels">
                    </a>
                </p>
            <table class="table table-hover text-center">
                <tr>
                    <th  width="15%" style="text-align:left; padding-left:20px;"><input type="checkbox" /><span>全选</span></th>
                    <th >文件名称</th>
                    <th >文件分组</th>
                    <th >文件时间</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!isset($list) || empty($list))
                {
                    echo "<tr><td  colspan='5'>暂无数据</td></tr>";
                }else
                {
                 foreach($list as $k=>$v)
                  echo ' <tr>
                <td><input type="checkbox" name="id['.$v['g'].'][]" value="'.$v['n'].'" /></td>
                <td>'.$v['n'].'</td>
                 <td><a href="?g='.$v['g'].'">'.$v['g'].'</a></td>
                <td>'.$v['t'].'</td>
                <td><div class="button-group">
               <a class="button border-green" href="?h=edit&n='.$v['n'].'"><span class="icon-refresh"></span>还原</a>
               <a class="button border-blue" href="?h=down&type=txt&n='.$v['n'].'"><span class="icon-download"></span>下载导出txt</a><a class="button border-blue" href="?h=down&type=excel&n='.$v['n'].'"><span class="icon-download"></span>下载导出Excel</a>
               <a class="button border-red" data-href="?h=del&n='.$v['n'].'"  href="javascript:;"  onclick="del($(this),\'删除\')"><span class="icon-trash-o"></span> 删除</a>
                    </div></td>
            </tr>';
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
<script src="/Static/js/arc_list.js" charset="UTF-8"></script>
</body></html>