<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
//应用公共文件

/** @parem $str 要转义的字符
 *  @return  返回转义后的字符
 * */
function trim_str($str)
{
    if(!is_array($str))
    {
        if(!get_magic_quotes_gpc())
        {
            return strip_tags(addslashes(trim($str)));
        }else
        {
            return strip_tags(trim($str));
        }
    }
    foreach($str as $k=>$v)
    {
       $str[$k]=trim_str($v);
    }
    return $str;
}
/*addslashes 反转义字符
 * */
function str_slashes($str)
{
    if(!is_array($str))
    {
      return  stripslashes($str);
    }
    foreach($str as $k=>$v)
    {
        $str[$k]=str_slashes($v);
    }
    return $str;
}


/** 自定义日志
 * @parem $e php 捕获的异常
 * @return viod 写入系统指定的log文件位置
 * */
function  log_info($e)
{
    $info=date("Y-m-d H:i:s",time())." | ".$e->getFile().' | line:'.$e->getLine().PHP_EOL;
    $info.="[info] ".$e->getMessage();
    $info.=PHP_EOL.str_repeat("----",30).PHP_EOL;

    $dir=config("log_dir").date("Ym",time()).'/';
    if(!is_dir($dir))
    {
        @mkdir($dir,0777);
    }
    $log_file=$dir.date("d").".log";
    file_put_contents($log_file,$info,FILE_APPEND);
}
// 自定义日志文件
function log_txt($error)
{
    $info=date("Y-m-d H:i:s",time())." | ".PHP_EOL;
    $info.="[txt] ".$error;
    $info.=PHP_EOL.str_repeat("----",30).PHP_EOL;

    $dir=config("log_dir").date("Ym",time()).'/';
    if(!is_dir($dir))
    {
        @mkdir($dir,0777);
    }
    $log_file=$dir.date("d").".log";
    file_put_contents($log_file,$info,FILE_APPEND);
}
// 循环删除文件夹下 文件 保留文件夹
function dirfile($dir)
{
    $r_dir_arr=array();
    if(is_dir($dir))
    {
        $run=opendir($dir);
        while(($run_f=readdir($run))!==FALSE)
        {
            if($run_f!="." && $run_f!="..")
            {
                if(is_dir($dir.$run_f))
                {
                    dirfile($dir.$run_f.'/');
                }else
                {
                   $bool=unlink($dir.$run_f);
                    if(!$bool)
                        log_txt(" 删除运行文件失败".$dir.$run_f);
                }
            }
        }
    }
}
