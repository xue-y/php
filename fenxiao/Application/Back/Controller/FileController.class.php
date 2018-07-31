<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-31
 * Time: 下午3:51
 * 文件管理
 */

namespace Back\Controller;
use My\MyController;

class FileController extends  MyController {

    public function index()
    {
        $this->pos_tag();  //当前位置标签

        // 取得但前控制器的所有方法
        $class=lcfirst(CONTROLLER_NAME); // 取得系统控制器的其他方法 --模板页面js（ajax）方式点击哪个方法就执行哪个方法
        $limit=D("Limit");
        $operate=$limit->sysset_operate($class);
        if(!empty($operate))
        {
            foreach($operate as $k=>$v)
            {
                $execs=explode("-",$v["execs"]);
                $operate[$k]["execs"]=$execs[1];
            }
        }
        $this->assign("operate",$operate);
        $this->display("Sysset/file"); // 输出模板
    }

    // 清理文件
    public function file()
    {
        if(!IS_AJAX)
            exit("您访问的页面不存在");
        if(!isset($_POST) || empty($_POST))
            exit("您请求的参数为空");

        $post=add_slashes($_POST);

        // 清理文件
        if(isset($post["file"]))
        {
            $clear_dir=$_SERVER["DOCUMENT_ROOT"]."/Public/".$post["file"]."/";
            if(is_dir($clear_dir))
            {
                // 判断文件夹下是否有文件
                $is_success=$this->clear_file($clear_dir);
                if($is_success==true)
                {
                    exit("ok");
                }else{
                    exit("删除失败");
                }
            }else
            {
                exit($clear_dir."目录不存在");
            }
        }
    }

    /** 删除一级目录
     * @parem  $dir 要删除的目录
     * */
    private function clear_file($dir)
    {
        $info=''; // 存放错误信息
        $file_re=opendir($dir); // 打开目录
        if(isset($file_re))
        {
            while(($file=readdir($file_re))!==false)
            {
                //  $file=$this->utf_gbk($file);  // 操作中文文件名
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

    /**utf8 转 gbk
     * @parem $str //输入的字符集  输出的字符集 要转换的字符串
     * @return 转换后的字符串
     * */
    private function utf_gbk($str) {

        $new_str=iconv('UTF-8', 'GBK', $str);
        if(!isset($str) || empty($str))
        {
            return $str;
        }
        return $new_str;
    }
    /**gbk 转 utf8
     * @parem $str 输入的字符集  输出的字符集 要转换的字符串
     * @return 转换后的字符串
     * */
    private function gbk_utf($str) {
        $new_str=iconv('GBK','UTF-8', $str);
        if(!isset($str) || empty($str))
        {
            return $str;
        }
        return $new_str;
    }

} 