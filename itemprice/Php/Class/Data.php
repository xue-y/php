<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-17
 * Time: 上午11:50
 * 数据管理类
 */

if(!defined('IN_SYS')) {
    header("Location:/404.html");
    exit();  // 跳转404
}
class Data extends Pro {

    private $table=DATA_PRO;// 当前操作的表名
    private $table_parent=DATA_TYPE;//当前操作的父表
    public  $g_id_field="t_id"; // 当前操作组字段名称
    public  $g_n_field="t_n"; //   //当前操作元素名称字段
    private  static  $export_all_data=null; // 导出备份列表数据
    private $u_n="n";// 用户名称字段；

    public function  __construct()
    {
        parent::__construct();
    }
    // 更新缓存区域
    public function cache($val)
    {
          switch($val)
          {
              case "all":
                  $this->cache_all();
                  break;
              case "back":
                  $this->cache_back();
                  break;
              case "front":
                  $this->cache_front();
                  break;
          }
    }

    //导入数据
    public function import()
    {
      $parem=array();
      array_push($parem,"file",DATA_UPTEMP);
      $upload=self::getInstance("Upload",$parem);
      $bool=$upload->up("file");
      if($bool!==true)
      {
          $this->skip("error",null,"上传文件");
      }
     // 实例化 excel
      $excel=self::getInstance("Excel");
     // 读取上传到临时目录文件
      if ($dh = opendir(DATA_UPTEMP))
      {
          while (($file = readdir($dh)) !== false)
          {
              // 中文转码
              $file=$this->gbk_utf($file);
              // 文件全名--全路径
              $new_file=DATA_UPTEMP.$file;
              if(is_file($new_file))
              {
              //判断文件格式
               $f_ext=strtolower(pathinfo($new_file,PATHINFO_EXTENSION));
               switch($f_ext)
               {
                   case "xls":
                       // 读取的是目录文件， 如果有不需要的文件 也会读取-- 所有操作失败删除上传文件
                       $this->excel_data($excel,$new_file);
                       break;
                   case "zip":
                       if(!isset(self::$zip_class))
                       {
                           unlink($new_file); // 删除上传的文件
                           $this->skip("error",null,"当前php 环境不支持 zip类 压缩 操作");
                       }
                       $info=$this->read_zip($excel,$new_file);
                       if(!empty($info))
                       {
                           @unlink($new_file); // 删除刚才上传的 多层嵌套的文件夹 zip
                           $this->skip("error",null,$info);
                       }
                       break;
                   case "txt":
                       $this->remove_file($new_file,$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$file);
                       break;
               }
              }
          }//----------------------如果读取的是个文件
      }
         $this->skip(SUCCESS,null,"导入数据");
    }

    //备份数据
    public function back($val)
    {
        $data_dir=$_SERVER['DOCUMENT_ROOT'].DATA_TXT;
        $back_dir=$_SERVER['DOCUMENT_ROOT'].DATA_BACK;
        if((!is_dir($data_dir)) || (!is_dir($back_dir)))
        {
            $info="数据文件目录".$data_dir."<br/>或备份".$back_dir."文件目录不存在<br/>操作";
            $this->skip("error",null,$info);
        }
        switch($val)
        {
            case "all":
                $this->back_all($data_dir,$back_dir);
                break;
            case "group":
                $this->back_file_one($data_dir,$back_dir,DATA_GROUP);
                break;
            case "user":
                $this->back_file_multiple($data_dir,$back_dir,DATA_USER);
                break;
            case "product":
                $this->back_file_multiple($data_dir,$back_dir,DATA_PRO);
                break;
            case "type":
                 $this->back_file_one($data_dir,$back_dir,DATA_TYPE);
                break;
        }
    }

    //导出数据
    public function export()
    {
       $all_data=$this->cur_pos(__CLASS__,"index");
        if(!isset(self::$export_all_data))  // 取得备份列表数据
        {
            $back_dir=$_SERVER['DOCUMENT_ROOT'].DATA_BACK;
            $file_arr=array();
            $back_resource=opendir($back_dir);
            $i=0;
            $f_ext=self::$conf_data["DATA_EXT"];
            while (($file = readdir($back_resource)) !== false)
            {
                $i++;
                if(isset($_GET["g"]))  //-------------------------- 按组查询
                {
                    $g=$this->add_slashes($_GET["g"]);
                    $re="/$g/";
                    preg_match($re,$file,$prge_file);
                    if(!empty($prge_file))
                    {
                        $file_arr[$i]["t"]=date("Y-d-m H:i:s", filectime($back_dir.$file));
                        $file_arr[$i]["n"]=$file;
                        $file_arr[$i]["g"]=$g;
                    }
                }
                else if($file!="" && $file!="." && $file!="..")
                {
                    $file_arr[$i]["t"]=date("Y-m-d H:i:s", filemtime($back_dir.$file));
                    $file_arr[$i]["n"]=$file;
                    $g=explode(DE_LIMITER,pathinfo($file,PATHINFO_FILENAME));
                    $file_arr[$i]["g"]=$g[0];
                }
            }
            closedir($back_resource);
            self::$export_all_data=$file_arr;
        }

        $all_data=$this->cur_pos('Data',"index");
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__.self::$conf_data["TEM_EXT"];
        $data_url=$this->menu_data("data_url");
        $tit=$data_url[__CLASS__.DE_LIMITER.__FUNCTION__];
        if(!empty(self::$export_all_data))
        {
            $file_arr_list=self::$export_all_data;

            $showrow =PAGE_SIZE; //一页显示的行数
            $show_page=PAGE_SHOW; // 显示几个页码
            if(isset($_GET['p']))
            {
                $curpage=max(intval($_GET['p']),1);
            }else
            {
                $curpage=1;  // 当前页数
            }
            //当前的页,还应该处理非数字的情况
            $url = "?p={page}"; //分页地址，如果有检索条件 ="?page={page}&q=".$_GET['q']-- Page.php  这个类中使用
            $total=count(self::$export_all_data);                // 数据开始位置    数组截取的结束位置
            $list=array_slice($file_arr_list,($curpage-1)*$showrow,$showrow);
            rsort($list); // 文件排序
        }
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file;
    }


//------------------------更新缓存--------------------------------------
    /** 更新所有静态页面
     * */
    private  function cache_all()
    {
       $back=$this->cache_back(true);
       $front=$this->cache_front(true);
       if( $front==1)
       {
           $this->skip(SUCCESS,null,"更新缓存");
        //   echo SUCCESS_CODE;
       }else
       {
           $this->skip("error",null,"更新缓存");
        //   echo ERROR_CODE;
       }
    }

    /** 更新缓存 生成后台静态页面
     * @parem $is_return  有返回值 默认输出
     * @return  默认输出
     * */
    private  function  cache_back($is_return=null)
    {
            $strTimeToString = "000111222334455556666667";
            $strWenhou = array('夜深了，','凌晨了，','早上好！','上午好！','中午好！','下午好！','晚上好！','夜深了，');
            $data["user"]=$_SESSION[$this->u_n];
            $data["time"]=$strWenhou[(int)$strTimeToString[(int)date('G',time())]];

            $Tem=Com::getInstance("Tem");
            $Tem->write_html("index",$data,true); //--------------生成主页

            $all_data["menu"]=$Tem->menu_data("menu");
            $all_data["data_url"]=$Tem->menu_data("data_url");
            $Tem->write_html("main",$all_data,true); //--------------生成首页

            $index=self::$conf_data["VIEW_DIR"].'index'.self::$conf_data["HTML_EXT"];
            $main=self::$conf_data["VIEW_DIR"].'main'.self::$conf_data["HTML_EXT"];
            if(isset($is_return))
            {
                if(is_file($main) && is_file($index))
                {
                    return SUCCESS_CODE;
                }else
                {
                   return ERROR_CODE;
                }
            }else
            {
                if(is_file($main) && is_file($index))
                {
                    $this->skip(SUCCESS,null,"更新后台缓存");
                }else
                {
                    $this->skip("error",null,"更新后台缓存");
                }
            }
    }

    /** 更新缓存 生成前台静态页面
     * @parem $is_return  有返回值 默认输出
     * @return  默认直接输出
     * */
    private function cache_front($is_return=null)
    {
            $this->wrtie_front(); // 取得项目数据
            if(isset($is_return))
            {
                return SUCCESS_CODE;
            }else
            {
                $this->skip(SUCCESS,null,"更新前台缓存");
            }
    }

    /**价格列表：生成前台静态页面 数据
     * @return  数组数据
     * */
    public  function wrtie_front()
    {
        $user_f=$this->table.DE_LIMITER;
        $f_ext=self::$conf_data["DATA_EXT"];
        $data_f_arr=array();
        $g_arr=array();
        $g_arr_g=$this->read_data($this->table_parent);
        if ($dh = opendir($_SERVER['DOCUMENT_ROOT'].DATA_TXT))
        {
            $list=array();
            while (($file = readdir($dh)) !== false)
            {
                $re="/(($user_f)(\d+))$f_ext/";
                if(preg_match($re,$file,$f_arr))
                {
                    array_push($g_arr,$f_arr[3]);
                    array_push($data_f_arr,$f_arr[1]);
                };
            }
            closedir($dh);
        }//-------------------------------------取得要取得数据的文件名
        $all_data=array();

        foreach($data_f_arr as $k=>$v)
        {
            $data=$this->read_data($v);
            if(!empty($data) && isset($g_arr_g[$g_arr[$k]]))
            {
                foreach($data as $kk=>$vv)
                {
                    $vv[$this->g_id_field]=$g_arr[$k];
                    $vv[$this->g_n_field]=$g_arr_g[$g_arr[$k]];
                    array_push($all_data,$vv);
                }
            }
        }
        // 生成用户下载 备用缓存数据
        $f_len=$this->write_date($all_data,DATA_CACHE_USER,true,DATA_CACHE);
        if($f_len<1)
        {
            $this->write_log("生成前台数据缓存文件失败",false);
        }
        return $all_data;
    }

//-------------------------导入数据-------------------------------------
    /** 读取压缩文件
     *@parem $excel 实例化 Excel
     *@parem $f_name 压缩文件名全路径
     * */
    private function read_zip($excel,$f_name)
    {
        $info=''; // 错误信息存放变量
        $remove_dir=$_SERVER['DOCUMENT_ROOT'].DATA_TXT; // 必须有写权限
        $zip_files=zip_open($f_name);   //打开压缩包
        $contents = array();
        if ($zip_files){
            while($zip_entry = zip_read($zip_files)){ // 读取压缩目录文件
                $name = zip_entry_name($zip_entry); //检索目录项的名称
                if ($name != '.' && $name != '..' && $name != ''){
                    // 中文转码
                    $name=$this->utf_gbk($name);

                    //读取一个打开了的压缩文件内容
                    $file_content = zip_entry_read($zip_entry,zip_entry_filesize($zip_entry));
                    $f_ext=strtolower(pathinfo($name,PATHINFO_EXTENSION));
                    switch($f_ext)
                    {
                        case "xls":
                            // 创建 一个新的 xls 存放在上传临时文件夹
                            $f_len=file_put_contents(DATA_UPTEMP.$name,$file_content);
                            if(isset($f_len) && is_file(DATA_UPTEMP.$name))
                            {
                                $this->excel_data($excel,DATA_UPTEMP.$name);
                            }
                            break;
                        case "zip":
                            $info="导入数据文件不支持多层嵌套压缩文件<br/>".$f_name.'/'.$name."<br/>";
                            return $info;
                            break;
                        case "txt":
                            file_put_contents($remove_dir.$name,$file_content); // 写入需要移动的目录中
                            break;
                    }
                }
            }
        }
        zip_close($zip_files);
        $this->unlink_file($f_name);
    }


    /** excle 文件转txt 文件 移动到数据【正在使用的数据】文件夹  之后删除 excle 文件
     * @parem $excel 实例化excel
     * @parem $file  excel 文件名 全路径名称
     * @return 无返回值
     * */
    private function excel_data($excel,$file)
    {
        $excel_f_new=$excel->read_excel($file);
        //替换路径 ----移动文件
        $excel_f_new3=str_replace("/Data/Uptemp/",DATA_TXT,$excel_f_new);
        $this->remove_file($excel_f_new,$excel_f_new3);
        //删除原文件 excel 文件
        $this->unlink_file($file);
    }

//-------------------------备份数据-------------------------------------

    /** 备份全部
     * @parem $data_dir 数据文件目录
     * @parem $back_dir 备份文件目录
     * @return 成功失败 状态码
     * */
    private function back_all($data_dir,$back_dir)
    {
        $info="";// 错误信息存放数组
        $back_resource=opendir($data_dir);
        while (($file = readdir($back_resource)) !== false)
        {
            if($file!="" && $file!="." && $file!="..")
            {
                $bool=copy($data_dir.$file,$back_dir.$file);
                if(!isset($bool))
                {
                    $info.=$data_dir.$file."文件备份";
                }
            }
        }
        closedir($back_resource);
        if(!empty($info))
        {
          $this->skip("error",null,$info);
        }
        $this->skip(SUCCESS,null,"备份");
    }

    /** 备份单个文件
     * @parem $data_dir 数据文件目录
     * @parem $back_dir 备份文件目录
     * @parem $file_name  备份文件
     * @return 成功失败 状态码
     * */
    private function back_file_one($data_dir,$back_dir,$file_name)
    {
        $f_n=$file_name.self::$conf_data["DATA_EXT"];
        if(is_file($data_dir.$f_n))
        {
            $bool=copy($data_dir.$f_n,$back_dir.$f_n);
            if(!isset($bool))
            {
                $info=$data_dir.$f_n."<br/>文件备份";
                $this->skip("error",null,$info);
            }
            $this->skip(SUCCESS,null,"文件备份");
        }else
        {
            $this->skip("error",null,$data_dir.$f_n."<br/>原数据文件不存在 操作");
        }
    }

    /** 备份多个指定标识文件名
     * @parem $data_dir 数据文件目录
     * @parem $back_dir 备份文件目录
     * @parem $file_prefix  文件标识名
     * @return 成功失败 状态码
     * */
    private function back_file_multiple($data_dir,$back_dir,$file_prefix)
    {
        $info="";// 错误信息存放数组
        $file_prefix=$file_prefix.DE_LIMITER;
        $f_ext=self::$conf_data["DATA_EXT"];
        $back_resource=opendir($data_dir);
        while (($file = readdir($back_resource)) !== false)
        {
            $re="/($file_prefix\d+)$f_ext/";
            if(preg_match($re,$file,$f_arr))
            {
                $bool=copy($data_dir.$f_arr[0],$back_dir.$f_arr[0]);
                if(!isset($bool))
                {
                    $info.=$data_dir.$f_arr[0]."<br/>文件备份";
                }
            }
        }
        closedir($back_resource);
        if(!empty($info))
        {
            $this->skip("error",null,$info);
        }
        $this->skip(SUCCESS,null,"文件备份");
    }

//-------------------------导出还原-------------------------------------

    public function export_down($n) // 下载单个文件
    {
        $back_dir=$_SERVER['DOCUMENT_ROOT'].DATA_BACK; // 备份数据目录；
        $export_dir=$_SERVER['DOCUMENT_ROOT'].DATA_EXPORT_A;// 导出数据生成文件存放位置 ----
        if(!isset($_GET["type"]))
        {
            $this->skip("error",null,"参数错误下载");
        }
        $down_type=$_GET["type"];
        if($down_type=="excel")             //-----------------------下载excel
        {
            $new_n=pathinfo($n,PATHINFO_FILENAME);
            // 判断用户导出的表是 一维数据还是二维数据
            if($new_n==DATA_GROUP) // 人员组文件
            {
              $tit="人员组名";
            }
            else if($new_n==DATA_TYPE)
            {
                $tit="项目类型名称";
            }else
            {
                $tit="";
            }
           $f_name=$this->export_excel_one($tit,DATA_BACK,$new_n,$export_dir);
            if(is_file($f_name)) //----------------------- excel 文件生成完成 执行下载
            {
                $down=Com::getInstance("Down");
                $down->down_f($f_name);
            }else
            {
                $this->skip("error",null,"生成文件失败或数据表数据为空 导出Excel文件");
            }
        }else                                //-----------------------下载txt
        {
            $down=Com::getInstance("Down");
            $down->down_f($back_dir.$n);
        }
    }

    public function export_edit($n)  // 还原单个文件
    {
        $back_dir=$_SERVER['DOCUMENT_ROOT'].DATA_BACK;
        $data_dir=$_SERVER['DOCUMENT_ROOT'].DATA_TXT;
        $bool=rename($back_dir.$n,$data_dir.$n);
        if(isset($bool))
        {
            $this->skip(SUCCESS,null,"还原");
        }else
        {
            $this->skip("error",null,"还原");
        }
    }

    /**   原文件txt  生成 excel 单个文件
     * @parem $data_tit  一维数组 的tit
     * @parem  $f_dir  txt 文件路径 ----不需要全路径 不需要后缀名
     * @parem  $f_name  txt 文件名
     * @parem $excel_dir excel 存放路径-----需要全路径
     * @parem $excel_name 默认原名称 如果从新命名传入名称 --不加后缀
     * @return 生成后的文件全名称
     * */
    public function export_excel_one($data_tit,$f_dir,$f_name,$excel_dir,$excel_name=null)
    {
        $data=$this->read_data($f_name,$f_dir);
        if(count($data)<1)
        {
            return null;
        }
        // 判断用户导出的表是 一维数据还是二维数据
        if(count($data)==count($data,1)) //----------------一维数组
        {
            $con = implode(PHP_EOL,$data);
            $tit=$data_tit;
        }else                          //----------------二维数组
        {
            $data2=array();
            foreach($data[0] as $k=>$v)
            {
                $data2["tit"][]=$k;
            }
            $data2["con"]=array_values($data);
            $data2["con"]=$this->add_slashes($data2["con"]);

            $tit=implode("\t",$data2["tit"]);

            foreach($data2["con"] as $k=>$v)
            {
                $data2["con"][$k] = implode("\t",$v);
            }

            $con = implode(PHP_EOL,$data2["con"]);

        }
        $conts=$tit.PHP_EOL.$con;
        $conts=$this->utf_gbk($conts);
        if(isset($excel_name))
        {
            $f_name=$excel_dir.$excel_name.self::$conf_data["EXCEL_EXT"];
        }else
        {
            $f_name=$excel_dir.$f_name.self::$conf_data["EXCEL_EXT"];
        }
        $f_len=file_put_contents($f_name,$conts);
        return $f_name;
    }


    public function export_edits() //-----------------批量还原
    {
        $files=$this->is_ids($_POST["id"]); //判断id 是否存在并且不为空
        $back_dir=$_SERVER['DOCUMENT_ROOT'].DATA_BACK;
        $data_dir=$_SERVER['DOCUMENT_ROOT'].DATA_TXT;
        $remove_file_arr=array();
       foreach($files as $g=>$f)
       {
           foreach($f as $f_n)
           {
               $this->remove_file($back_dir.$f_n,$data_dir.$f_n);
           }
       }
        $this->skip(SUCCESS,null,"还原");
    }

    /* 多个TXT 压缩 下载导出
     * */
    public function export_txt()
    {
        $back_dir=$_SERVER['DOCUMENT_ROOT'].DATA_BACK;
        $down_dir=$_SERVER['DOCUMENT_ROOT'].DATA_EXPORT_A;
        $files=$this->is_ids($_POST["id"]); //判断id 是否存在并且不为空
        if(!isset(self::$zip_class))
        {
            $this->skip("error",null,"当前php 环境不支持 zip类 压缩");
        }
        $zip = self::$zip_class;
        $zip_name=$down_dir.date("Y-m-d-H-i-s",time()).self::$conf_data["COMPRESS_EXT"];
        $zip->open($zip_name,ZipArchive::CREATE);   //打开压缩包

        foreach($files as $g=>$f)  // 循环分组
        {
            foreach($f as $f_n)
            {
            $zip->addFile($back_dir.$f_n,$f_n);   //向压缩包中添加文件//第二个参数是放在压缩包中的文件名称
            }
        }
        $zip->close();  //关闭压缩包

        if(is_file($zip_name))
        {
            $down=Com::getInstance("Down");
            $down->down_f($zip_name);
        }else
        {
            $this->skip("error",null,"文件压缩");
        }
    }


    /*分组 txt 文件转 excel 文件 压缩下载导出
     * */
    public function export_excel()
    {
        $down_dir=$_SERVER['DOCUMENT_ROOT'].DATA_EXPORT_A;
        $files=$this->is_ids($_POST["id"]); //判断id 是否存在并且不为空
        if(!isset(self::$zip_class))
        {
            $this->skip("error",null,"当前php 环境不支持 zip类 压缩");
        }
        $zip = self::$zip_class;
        $zip_name=$down_dir.date("Y-m-d-H-i-s",time()).self::$conf_data["COMPRESS_EXT"];
        $zip->open($zip_name,ZipArchive::CREATE);   //打开压缩包

        $excel=Com::getInstance("Excel");

        foreach($files as $g=>$f)  // 循环分组
        {
            $g_data_con=array();
            $excel_data=array();
            foreach($f as $k=>$f_n)  // 组中的文件
            {
                $data=$this->read_data(pathinfo($f_n,PATHINFO_FILENAME)); // 每个文件中的数据
                if(!empty($data))
                {
                    if(count($data)==count($data,1))
                    {
                        $excel_data["tit"]=$g;
                    }else
                    {
                        $excel_data["tit"]=array_keys($data[0]);
                    }
                    foreach($data as $d_v)
                    {
                      array_push($g_data_con,$d_v);
                    }
                }
            }
            $excel_data["con"]=$g_data_con;

            $excel_name=$excel->writer_excel($excel_data,DATA_EXPORT_A,$g);
            $g_name=$g.self::$conf_data["EXCEL_EXT"];
            $zip->addFile($excel_name,$g_name);
        }
        $zip->close();  //关闭压缩包
        if(is_file($zip_name))
        {
            $down=Com::getInstance("Down");
            $down->down_f($zip_name);
        }else
        {
            $this->skip("error",null,"文件压缩");
        }
    }


} 