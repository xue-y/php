<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-29
 * Time: 下午4:08
 */
if(!defined('IN_SYS')) {
    header("Location:/404.html");
    exit();  // 跳转404
}
class Clear extends Pro{

    /* 清空文件夹
 * $parem $dir 要清空的目录
 * @return  成功返回 true  失败返回false  错误信息写入日志信息
 * */
    public function clear_file($dir)
    {
        $info=''; // 存放错误信息
        $file_re=opendir($dir); // 打开目录
        if(isset($file_re))
        {
            while(($file=readdir($file_re))!==false)
            {
                $file=$this->utf_gbk($file);  // 操作中文文件名
                if(is_file($dir.$file))  // 如果是个文件就删除
                {
                    $bool=unlink($dir.$file);
                    if(!isset($bool))
                    {
                        $info.=$dir.$file.'文件删除失败';
                    }
                }
            }
        }
        closedir($file_re);
        if(empty($info))
        {
            return true;
        }else
        {
            $this->write_log($info);
            return false;
        }
    }

    /** 以上级文件为标准
     * 1. 用户组与用户_用户组id文件是否一一对应
     * 2. 项目类型与项目_项目类型id 是否一一对应
     * @parem $sup  上级文件名
     * @parem $sub  下级文件名
    */
    public function clear_data($dir,$sup,$sub)
    {
        $info=''; // 存放错误信息
        $sub=$sub.DE_LIMITER;
        $f_ext=self::$conf_data["DATA_EXT"];
        $sup_data=$this->read_data($sup);
        $exis=array();//存在的文件---保留的文件
        $exis_all=array(); // 全部的文件
        if(!empty($sup_data)) //----------------------------------如果存在上级数据
        {
            $sup_key=array_keys($sup_data); // 取得下标
            foreach($sup_key as $v)
            {
                $file_temp_name=$sub.$v.$f_ext;
                array_push($exis,$file_temp_name);
            }
            $file_re=opendir($dir); // 打开目录
            if(isset($file_re))
            {
                while(($file=readdir($file_re))!==false)
                {
                    $re="/($sub\d+)$f_ext/";
                    if(preg_match($re,$file,$f_arr))
                    {
                        array_push($exis_all,$f_arr[0]);
                    }
                }
            }
            $remove_file=array_diff($exis_all,$exis);
            foreach($remove_file as $v)
            {
              $bool=unlink($dir.$v);
              if(!isset($bool))
              {
                  $info.=$dir.$file.'文件删除失败';
              }
            }
        }else           //----------------------------------如果不存在上级数据
        {
            $file_re=opendir($dir); // 打开目录
            if(isset($file_re))
            {
                while(($file=readdir($file_re))!==false)
                {
                    $file=$this->utf_gbk($file);  // 操作中文文件名
                    $re="/($sub\d+)$f_ext/";
                    if(preg_match($re,$file,$f_arr))
                    {
                        $bool=unlink($dir.$f_arr[0]);
                        if(!isset($bool))
                        {
                            $info.=$dir.$file.'文件删除失败';
                        }
                    }
                }
            }
        }
        closedir($file_re);
        if(empty($info))
        {
            return true;
        }else
        {
            $this->write_log($info);
            return false;
        }
    }
} 