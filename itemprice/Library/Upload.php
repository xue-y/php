<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-12-31
 * Time: 下午2:39
 */
date_default_timezone_set("PRC");
error_reporting(0);
set_time_limit(0); //设置脚本执行时间--0 不限制时间
ini_set('max_execution_time', '600');
ini_set('max_input_time ', '600');
ini_set('memory_limit', '200M'); // 好像设置不成功，不起作用---如果不起作用请在php.ini 文件中设置
/*如果修改调整上传文件个数 修改php.ini max_file_uploads=你需要的文件个数
如果修改调整单个文件上传最多大小修改php.ini upload_max_filesize=你需要的XXMB
如果修改调整总文件上传大小修改php.ini post_max_size=你需要的XXMB*/

/*调用实例
$class_up=new Upload("file");
$up_status=$class_up->up("file","g");*/

class Upload extends Com{
    private $ext=array("zip","txt","xls");
    private $max_f=5; // 单个文件大小 2 MB
    private $max_up=20; // 总文件的大小8MB
    private $up_num=5; // 上传文件个数
    private $up_dir; // 上传路径

    // 判断是否有上传文件
    public  function __construct($parem)
    {
        $f_input_name=$parem[0];
        $this->up_dir=$parem[1];
        if(empty($_FILES[$f_input_name]["name"][0])) // 判断上传文件
        {
            exit("没有上传文件");
        }
      //  $this->up($f_input_name,$prefix_name=NULL); // 上传域 name 值  初始化的类中调用---调用页面取不到返回值
    }
   // 判断上传文件错误
    public function f_error($f_error)
    {
        if (!empty($f_error)) {
            switch($f_error){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择文件。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '9':
                default:
                    $error = '未知错误。';
            }
            return $error;
        }
    }


    /**判断上传文件大小
     * @parem $f_name  文件名称
     * @parem $f_size  大小限制 单位 字节
     * */
    public function  f_size($f_name,$f_size)
    {
        $f_size=round($f_size/1024/1024);
        if($f_size>$this->max_f)
        {
            exit("上传文件".$f_name."大于".$this->max_f."MB");
        }
    }

    /**判断上传文件后缀
     * @parem f_name 上传文件名称
     * @param ext  允许上传的后缀 可以是数组类型 可以是字符串
     * */
    public  function f_ext($f_name)
    {
        $f_ext=pathinfo($f_name,PATHINFO_EXTENSION);
        $f_ext=strtolower($f_ext);
        if($f_ext=="zip" && !isset(self::$zip_class))
        {
            exit("当前php 环境不支持 zip类 压缩");
        }
        if(is_array($this->ext))
        {
            if(!in_array($f_ext,$this->ext))
            {
                exit($f_name."文件后缀名系统不支持");
            }
        }else
        {
           if($f_ext!=$this->ext)
           {  exit($f_name."文件后缀名不允许");}
        }
       return $f_ext;
    }

    //
    /**文件执行上传
     * @parem $f_input_name // 上传域 name 值
     * @parem $prefix_name 上传文件名称前缀用于标识
     * @parem $is_new_name 是否从新命名 默认使用原名称
     * @parem return 成功返回ture 失败返回 false
     * */
    public  function  up($f_input_name,$prefix_name=NULL,$is_new_name=NULL)
    {
            $file_c=count($_FILES[$f_input_name]["name"]);// -----上传文件个数
            if($file_c>$this->up_num)
            {
                exit("本地上传文件超过".$this->up_num."个");
            }
            $file_array=array();
            $file_size_c2=0;

            for($i=0;$i<$file_c;$i++)
            {
                $file_array[$i]["ext"]=$this->f_ext($_FILES[$f_input_name]["name"][$i]);  // 后缀名
                $this->f_size($_FILES[$f_input_name]["name"][$i],$_FILES[$f_input_name]["size"][$i]); // 单个文件大小
                if($_FILES[$f_input_name]["error"][$i]>=1) // 文件上传状态
                {
                    $error=$this->f_error($_FILES[$f_input_name]["error"][$i]);
                    echo $error;break; exit;
                }
                $file_array[$i]["name"]=$this->utf_gbk($_FILES[$f_input_name]["name"][$i]);
                $file_array[$i]["type"]=$_FILES[$f_input_name]["type"][$i];
                $file_array[$i]["tmp_name"]=$_FILES[$f_input_name]["tmp_name"][$i];
                $file_array[$i]["error"]=$_FILES[$f_input_name]["error"][$i];
                $file_array[$i]["size"]=$_FILES[$f_input_name]["size"][$i];
                $file_size_c2+=$_FILES[$f_input_name]["size"][$i];
            }
            $file_size_c2=ceil($this->max_up/1024/1024);
            if($file_size_c2>$this->max_up)
            {
                echo "上传总文件大小".$file_size_c2."超过".$this->max_up."MB";exit;
            }
      //      $this->up_dir=$_SERVER['DOCUMENT_ROOT'].DATA_EXPORT;
            if(!is_dir($this->up_dir))
            {
                @mkdir($this->up_dir,0777);
            }
            if(isset($prefix_name))
                $new_name=$this->up_dir.$prefix_name."_";
            else
                $new_name=$this->up_dir;

            $up_is_num=0;
            foreach($file_array as $k=>$v)
            {
                if(isset($is_new_name)) // 使用新名称---使用的是原文件名称后缀
                {
                    $new_name2=$new_name.date("YmdHis",time()).'_'.rand().".".$v["ext"];
                }else
                {
                    $new_name2=$new_name.$v["name"];
                }
                if(is_file($new_name2))// 如果存在同名文件删除掉 ---- 默认可以覆盖 ，此步骤可以省略
                {
                    @unlink($new_name2);
                }
                $up_is=move_uploaded_file($v["tmp_name"],$new_name2);
                if(isset($up_is))
                {
                    $up_is_num+=intval($up_is);
                }else
                {
                    $this->write_log("上传文件 ".$v['tmp_name']." 失败");
                }
            }
            if($up_is_num==$file_c)
            {
                return true;
            }else
            {
                return false;
             //   echo "文件上传失败，请查看日志";
            }
    }
}