<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-2
 * Time: 下午5:35
 扩展类的父类
 */


class Com {
    public static $conf_data=null; // 配置数据文件
    private  static $new_self_val=null; // 实例化自身类
    public static $zip_class=null;
    public function __construct()
    {
        define('IN_SYS', TRUE);
        header("Content-type:text/html;charset=utf-8");
        $this->re_file(); // 配置文件只加载一次

        ini_set('display_errors',0);            //是否显示错误信息
        ini_set('display_startup_errors',1);    //php启动错误信息
        // error_reporting(-1);                    //打印出所有的 错误信息
        error_reporting(E_ALL);  //所有错误
        $error_file= DATA_LOG.'error'.self::$conf_data["DATA_EXT"];
        touch($error_file);
        ini_set('error_log',$error_file);

        session_set_cookie_params(self::$conf_data["SESSION_TIME"]);// 保存10个小时
        ini_set('session.gc_maxlifetime',self::$conf_data["SESSION_TIME"]);
        if(!isset($_SESSION))
            session_start(); //开启session

        spl_autoload_register(array($this,'auto_class'));
        if(class_exists("ZipArchive")) // 判断是否支持压缩类
        {
            self::$zip_class=Com::getInstance("ZipArchive");
        }
    }

    /** 实例化类
     * @parem $class_name 实例化的类名称
     * @parem  parameter 参数  type array
     * */
   static public function getInstance($class_name,$parameter=null) {
       static $instance=array();
        if (!(isset ( $instance[$class_name] ))) {
            if(isset($parameter))
            {/*
                if(is_array($parameter))
                    $parameter=implode(",",$parameter);*/
                $instance[$class_name] = new $class_name($parameter);
            }
            else
            {
                $instance[$class_name] = new $class_name ();
            }
        }
        return $instance[$class_name];
    }
    // 加载配置文件
    static public function re_file()
    {
        if (is_null ( self::$conf_data ) || !(isset ( self::$conf_data )))
        {
            self::$conf_data=require $_SERVER['DOCUMENT_ROOT']."/Common/Conf/data.php";
            require $_SERVER['DOCUMENT_ROOT']."/Common/Conf/define.php";
            date_default_timezone_set("PRC");
        }
        return self::$conf_data;
    }
    // 自动加载类文件
    public function auto_class($class_name)
    {
        $class_arr["Tem"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Tem.php';
        $class_arr["Down"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Down.php';
        $class_arr["Excel"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Excel.php';
        $class_arr["Pro"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Pro.php';
        $class_arr["Upload"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Upload.php';
        $class_arr["Page"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Page.php';
        $class_arr["Tpage"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Tpage.php';
        $class_arr["Spage"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Spage.php';
        $class_arr["Vaildata"]=$_SERVER['DOCUMENT_ROOT'].'/Library/Vaildata.php';

        $class_arr["Group"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Group.php';
        $class_arr["User"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/User.php';
        $class_arr["Type"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Type.php';
        $class_arr["Product"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Product.php';
        $class_arr["Dpro"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Dpro.php';
        $class_arr["Data"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Data.php';
        $class_arr["Price"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Price.php';
        $class_arr["Log"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Log.php';
        $class_arr["Login"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Login.php';
        $class_arr["Clear"]=$_SERVER['DOCUMENT_ROOT'].'/Php/Class/Clear.php';

        if(is_file($class_arr[$class_name]))
        {
            require $class_arr[$class_name];
        }else
        {
            exit($class_arr[$class_name]."文件不存在");
        }
    }

    /**生成日期时间的文件名，根据绝对路径根目录
     * @parem dir 文件存放路径相对根路径
     * @parem ext  文件后缀名
     * @return返回文件名
     * */
    protected function file_name($dir,$ext)
    {
        return $_SERVER['DOCUMENT_ROOT'].$dir.date("Y-m-d-H-i-s",time()).$ext;
    }

    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    public  function add_slashes($string)
    {
        if(!is_array($string))
        {
           return addslashes(trim($string));
        }
        foreach($string as $key => $val)
        {$string[$key] =$this->add_slashes($val);}
        return $string;
    }

    /**
     * 返回经stripslashes处理过的字符串或数组 函数删除由 addslashes() 函数添加的反斜杠。
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    protected function str_slashes($string){
        if(!isset($string)) return;
      if(!is_array($string)) return stripslashes($string);
        foreach($string as $key => $val) $string[$key] =$this->str_slashes($val);
        return $string;
    }

    /** 跳转页面
     * */
    public  function skip($status,$url=null,$info=null,$t=3)
    {
        if(!isset($status))
        {
            header("Location:".$_SERVER['HTTP_REFERER']); //默认为失败
            exit;
        }
        if(isset($info))
        {
          //  $info=filter_var($info, FILTER_SANITIZE_STRIPPED);
            $info=$this->add_slashes($info);
            $data["info"]=$info;
        }
        $data["status"]=$status;
        $data["t"]=$t;
        $data["url"]=isset($url)?$url:$_SERVER['HTTP_REFERER'];
        require $_SERVER["DOCUMENT_ROOT"].'/Tem/skip.php';
        exit;

    }

    /** 错误日志记录
     * @parem $info  错误信息
     * @parem $is_exit 如果为true 直接终止程序输出错误 默认为false
     * */
    public function write_log($info,$is_exit=false)
    {
        if(!is_dir(DATA_LOG))
        {
            @mkdir(DATA_LOG,0777);
        }
        $log_name=DATA_LOG.date('Y-m-d',time()).self::$conf_data["DATA_EXT"];
        $con="文件". __FILE__."|info:".$info."|time:".date('Y-m-d H:i:s',time()).PHP_EOL;
        file_put_contents($log_name,$con,FILE_APPEND);
        if($is_exit==true)
        {   exit($info); }
    }

    /**utf8 转 gbk
     * @parem $str //输入的字符集  输出的字符集 要转换的字符串
     * @return 转换后的字符串
     * */
    public function utf_gbk($str) {
       /* $mb_si=function_exists("mb_strlen");
        if(isset($mb_si) && mb_strlen($str)!=strlen($str))
        {
            return iconv('UTF-8', 'GB2312', $str);
        }else
        {
            return $str;
        }*/ // -------------------------判断是不是中文编码转字符 有时判断条件不够准确
        $str=iconv('UTF-8', 'GBK', $str);
        if(!isset($str) || empty($str))
        {
            $this->skip("error",null," 编码转换");
        }
        return $str;
    }
    /**gbk 转 utf8
     * @parem $str 输入的字符集  输出的字符集 要转换的字符串
     * @return 转换后的字符串
     * */
    public function gbk_utf($str) {
       /* $mb_si=function_exists("mb_strlen");
        if(isset($mb_si) && mb_strlen($str)!=strlen($str))
        {
            return iconv('GBK','UTF-8', $str);
        }else
        {
            return $str;
        }*/// -------------------------判断是不是中文编码转字符 有时判断条件不够准确
        $str=iconv('GBK','UTF-8', $str);
        if(!isset($str) || empty($str))
        {
            $this->skip("error",null," 编码转换");
        }
        return $str;
    }

    /** 加密函数
     * @parem $pass 原始密码
     * @parem return 加密的字符串
     * @return 加密后的密码
     * */
    public  function  pass_encrypt($pass)
    {
        return sha1(md5($pass));
    }

    /* 生成 token */
    public function  en_token($g_id)
    {
        $str=substr($this->pass_encrypt($g_id),5,30); //15
        $start=uniqid();//13
        $token=$start.$str.uniqid(mt_rand(10,99999));
        return $token;
    }


    /** 读取数据 json.txt 读取转数组输出
     * @parem $data_f 要读取的文件名，不需要后缀、路径
     * @parem $dir 默认正在使用的数据目录
     * @return 读取的数据
     * */
    public  function read_data($data_f,$dir=null)
    {
        if(!isset($dir))
        {
            $dir=DATA_TXT;
        }
        $f=$_SERVER["DOCUMENT_ROOT"].$dir.$data_f.self::$conf_data["DATA_EXT"];
        if(!is_file($f))
        {
            // exit($f."文件不存在");
            return null;
        }
        $data=file_get_contents($f);
        if(!empty($data))
        {
            $data=json_decode($data,true);
            $data=$this->str_slashes($data);//-------------------转义字符
            return $data;
        }
    }

    /**写入数组数据到原文件---return true  -次可以添加多条数据
     * @parem new_data 写入的数据 type array
     * @parem data_f 要写入数据的文件名 不需要后缀名、路径    导入文件前提示 数据 格式一致，否则数据出错或失败
     * @parem $is_update 是否更新数据 默认false 不更新
     * @return 返回写入的字符长度
     * */
    public function write_date($new_data,$data_f,$is_update=false,$data_file_dir=null)
    {
        $data=$this->add_slashes($new_data); //-------------------转义字符
        if(!isset($data_file_dir))
        {
            $data_file_dir=DATA_TXT;
        }
        $data_f=$_SERVER['DOCUMENT_ROOT'].$data_file_dir.$data_f.self::$conf_data["DATA_EXT"];

        if(is_file($data_f) && $is_update!=true) // ---------------z追加数据
        {
            $data=file_get_contents($data_f);

            if(empty($data) || $data=="null")
            {
                $data=array();
            }
            else
            {
                $data=json_decode($data,true);  // json 转数组
            }
            foreach($new_data as $k=>$v)
            {
                array_push($data,$v);// 数组没有进行去重，防止用户组id 对应不上用户所属组【错位】
            }
        }       //------------------------------------------------------第一次添加数据 或更新数据
        $data=json_encode($data);  // json 格式数据写入文件
        $f_len=file_put_contents($data_f,$data);
        return $f_len;
    }

    /**价格 数据 字段
     * @return 返回定义字段名称
     * */
    public function price_data_field()
    {
        // 此字段的顺序 按照组顺序 排列
        // 组 id 1-->$price_data[0], id 2-->$price_data[1] 依次类推
        $price_data["s_price"]="市场价格";
        $price_data["b_price"]="标准价格";
        //     $price_data["x_price"]="销售价格";
        return  $price_data;
    }

    /** 身份标识 ---根据不同的身份显示不同的价格
     * @parem  $g_id 组id ---数据表中的id
     * @parem $token session 中的token
     * @parem $login_file 验证失败的跳转页面
     * @return $gid_token  身份标识
     * */
    public function price_she_biaoshi($g_id,$token,$login_file)
    {
        $gid_token=null;
        $gid_token=$this->price_she_veri($g_id);

        $s_token=$this->en_token($gid_token);
        $s_token=substr($s_token,13,30);
        $token=substr($token,13,30);

        if($s_token!==$token)
        {
            session_unset();//$_SESSION=array();
            $this->skip("error",$login_file,"登录信息token失效");
            exit;
        }
        return $gid_token;
    }

    /* 身份标识---判断用户是否有权限访问*/
    public function  price_she_veri($g_id)
    {
      //  $this->price_data_field(); ---字段与 gid_token 要对应
        if($g_id==="0")
        {
            $gid_token=TOKEN;
        }else if($g_id==="1")
        {
            $gid_token="s_price";
        }else if($g_id==="2")
        {
            $gid_token="b_price";
        }
        if(!isset($gid_token) || empty($gid_token))
        {
            $this->skip("error","/404.html","您没有权限 操作");
        }
        return $gid_token;
    }


}
return Com::getInstance("Com"); // 实例化自己