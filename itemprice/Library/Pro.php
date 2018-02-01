<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-1
 * Time: 下午5:36
 */


/** 业务逻辑的公共父类
 * */
class Pro extends Com{

    protected  $cur_pos_v; // 当前位置变量
    public  $index_url;// 模板导航 首页

    public  function __construct()
    {
       $this->veri_login(); // 验证是否登录
        // 模板首页链接地址 --文字
       $this->index_url='<li><a href="'.PHP_CON.'Index.php" target="right" class="icon-home"> 首页</a></li>';

    }

    /** 菜单数据
     * @parem @return_data 要返回数据的数组
     * @return  返回你需要要的数据
     * */
    public function menu_data($return_data)
    {
        $ext=self::$conf_data["TEM_EXT"];
        $menu[0]["n"]="用户组管理";
        $menu[0]["url"]="Group".$ext;
        $menu[0]["icon"]="icon-group";

        $menu[1]["n"]="用户管理";
        $menu[1]["url"]="User".$ext;
        $menu[1]["icon"]="icon-user";

        $menu[2]["n"]="项目分类";
        $menu[2]["url"]="Type".$ext;
        $menu[2]["icon"]="icon-bar-chart";

        $menu[3]["n"]="项目管理";
        $menu[3]["url"]="Product".$ext;
        $menu[3]["icon"]="icon-bar-chart";

        $menu[4]["n"]="数据管理";
        $menu[4]["url"]="Data".$ext;
        $menu[4]["icon"]="icon-book";

        $menu[5]["n"]="项目回收站";
        $menu[5]["url"]="Dpro".$ext;
        $menu[5]["icon"]="icon-trash";

        $menu[6]["n"]="日志管理";
        $menu[6]["url"]="Log".$ext;
        $menu[6]["icon"]="icon-cog";

        $nav["Group"]="用户组";
        $nav["User"]="用户";
        $nav["Type"]="项目类型";
        $nav["Product"]="项目";
        $nav["Dpro"]="项目回收站";
        $nav["Data"]="数据管理";
        $nav["Log"]="日志管理";// 类名

        $action["add"]="添加";
        $action["edit"]="修改";
        $action["index"]="列表"; // 方法名

        $data_url["Data_cache"]="更新缓存";
        $data_url["Data_import"]="导入数据";
        $data_url["Data_back"]="备份数据";
        $data_url["Data_export"]="导出/还原";

        //用户组数据 人员数据 项目数据 项目类型数据 删除项目数据
        if(isset($return_data))
        {
            switch($return_data)
            {
                case "nav":
                    return $nav;
                    break;
                case "menu":
                    return $menu;
                    break;
                case "action":
                    return $action;
                    break;
                case "data_url":
                    return $data_url;
                    break;
                default:
                    return "不存在您要的数据";
                    break;
            }
        }

    }


    /** 当前位置小导航
     * @parem $c l类名称
     * @parem $fn 函数名称
     * @return 导航位置变量
     * */
    public  function cur_pos($c,$fn)
    {
       /* $data["nav"]=$this->menu_data("nav")[$c];
        $data["action"]=$this->menu_data("action")[$fn];
        $data["p_url"]=$c.self::$conf_data["TEM_EXT"];
        return $data;*/
        $data=array();
        $nav=$this->menu_data("nav");
        $action=$this->menu_data("action");
        $p_url=$c.self::$conf_data["TEM_EXT"];
        $data=array("nav"=>$nav[$c],"action"=>$action[$fn],"p_url"=>$p_url);
        return $data;
    }

    /** 拼接视图名称View 访问静态页面
     * */
    public function view_name($c,$fn)
    {
        $v_n=self::$conf_data["VIEW_DIR"].strtolower($c).'_'.$fn.self::$conf_data["HTML_EXT"];
        return $v_n;
    }

    /** 判断是不是存在 requer 提交的 值
     * @parem $type 表单提交方式 INPUT_GET、 INPUT_POST、 INPUT_COOKIE、 INPUT_SERVER、 INPUT_ENV 里的其中一个。
     * @parem $name 字段名称
     * @return true false
     * */
    public function is_requer($type,$name)
    {
          if(is_array($name))
          {
            foreach($name as $v)
            {
                // 如果值为0  empty 判断为ture
                $bool=filter_has_var($type,$v);
                if(gettype($name)=="int")
                {
                    settype($v,"string");
                }
                if(!isset($bool) && !empty($v))
                {
                  return false;break;
                }
            }
            return true;
          }else
          {
              if(gettype($name)=="int")
              {
                 settype($name,"string");
              }
              $bool=filter_has_var($type,$name);
              if(isset($bool) && !empty($name))
              {
                  return true;
              }
          }
    }

    /**判断用户所在组是否存在
     * @parem  $gid 判断此组是否存在
     * @parem $g_type  类型、组文件名
     * @return 错误直接跳出 正确无返回值
     * */
    protected function user_gid($gid,$g_type)
    {
        $gid=intval($gid)+1; // 如果id 为第一个数组值取不到
        if(!empty($gid))
        {
            $gid-=1;
            $group=$this->read_data($g_type);
            $group=array_keys($group);
            if(!in_array($gid,$group))
            {
                $this->skip("error",null,"所选类型/组不存在 操作");
            }
        }else
        {
            $this->skip("error",null,"请选择所属类型/组  操作");
        }
    }

    /**写入数组数据到原文件---return true  一次添加一条数据
     * @parem new_data 写入的数据 type array
     * @parem data_f 要写入数据的文件名 不需要后缀名、路径  导入文件前提示 数据 格式一致，否则数据出错或失败
     * @parem $is_update 是否更新数据 默认false 不更新
     * @parem $is_id  如果传入id ，添加id 字段
     * @parem $id_pix id 前缀标识
     * @return 写入字符长度
     * */
    protected function write_date_one($new_data,$data_f,$is_update=false,$is_id=null,$id_pix=null)
    {
        $new_data=$this->add_slashes($new_data); //-------------------转义字符
        $data_f=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$data_f.self::$conf_data["DATA_EXT"];

        if(is_file($data_f) && $is_update!=true) // ---------------z追加数据
        {
            $data=file_get_contents($data_f);

            if(empty($data))
            {
                $data=array();
            }
            else
            {
                $data=json_decode($data,true);  // json 转数组
            }

            if(isset($is_id))
            {
                foreach($data as $k=>$v) // ---从新赋值id
                {
                    $id_num=$k+intval(DATA_ID_S);// ----------------------id 从1 开始
                    $data[$k][$is_id]=$id_pix.$id_num;
                }
                $id_cur_num=DATA_ID_S+count($data);
                $new_data[$is_id]=$id_pix.$id_cur_num;
            }

            array_push($data,$new_data);
     /*       $data=json_encode($data);  // json 格式数据写入文件
            $f_len=file_put_contents($data_f,$data);*/
        }
        else        //------------------------------------------------------第一次添加数据 或更新数据
        {
            if(isset($is_id))
            {
                $new_data[$is_id]=$id_pix.DATA_ID_S;// ----------------------id 从1 开始
            }
            $data=array();
            if(!is_file($data_f)) // 多个字段的数组 第一次要转为二维数组
            {
                $data[0]=$new_data;
            }else
            {
                foreach($new_data as $k=>$v)
                {
                    array_push($data,$v);
                }
            }
        }
        $data=json_encode($data);  // json 格式数据写入文件
        $f_len=file_put_contents($data_f,$data);
        return $f_len;
    }

    /** 判断id是否存在 并且是否合法
     * @pream $id  判断传入的值 int type
     * @return 返回id 值； 失败返回 null
     * */
    public  function  get_id($id)
    {
        if(isset($_GET[$id]) && (intval($_GET[$id])+1)>0)

        {
            return intval($_GET[$id]);
        }else
        {
            return null;
        }
    }

    /** 判断id 是否存在并且不为空
     * @parem id 传值方式
     * */
    protected  function is_ids($mode)
    {
        if(!isset($mode) || empty($mode))
        {
            $this->skip("error",null,"请选择要操作的对象");
        }
        return $mode;
    }

    /** 删除单个文件
     * @parem  $dir文件目录
     * @parem $n  文件名称
     * @reutn 成功失败 弹窗提示
     * */
    public function file_del($dir,$n)
    {
        $bool=unlink($dir.$n);
        if(isset($bool))
        {
            $this->skip(SUCCESS,null,"清空/删除");
        }else
        {
            $this->skip("error",null,"清空/删除");
        }
    }

    /**-----------批量删除
     * @parem  $dir 要删除的目录
     * @parem $n_arr 数组文件名
     *  @reutn 成功失败 弹窗提示
     * */
    public function file_dels($dir,$files=null)
    {
        if(!isset($files))
        {
            $files=$this->is_ids($_POST["id"]); //判断id 是否存在并且不为空
        }
        if(count($files)===count($files,1))//==== 文件名称为一维数组的
        {
            foreach($files as $f_n)
            {
               $this->unlink_file($dir.$f_n);
            }
        }else
        {
            foreach($files as $g=>$f) //==== 文件名称为二维数组的
            {
                foreach($f as $f_n)
                {
                    $this->unlink_file($dir.$f_n);
                }
            }
        }
        $this->skip(SUCCESS,null,"清空/删除");
    }

    /**移动文件
     * @parem $old 老文件名
     * @parem $new 新文件名
     * @return 正确没有返回值 错误输出错误码
     * */
    protected   function remove_file($old,$new)
    {
        $bool=rename($old,$new);
        if(!isset($bool))
            $this->skip("error",null,"移动文件 [".$old."] ");
    }

    /** 删除文件
     * @parem $file_name 要删除的文件名
     *  @return 正确没有返回值 错误输出错误码
     * */
    protected  function  unlink_file($file_name)
    {
       $file_name=$this->utf_gbk($file_name);  // 操作中文文件名
        if(!is_file($file_name))
        {
            $this->skip("error",null,"删除文件 [".$file_name."] <br/>不存在或文件错误 操作");
        }
        $bool=unlink($file_name);
        if(!isset($bool))
            $this->skip("error",null,"删除文件 [".$file_name."] 失败 请手动删除");
    }

    /** 判断get ---传值文件名 判断文件是否存在
     * @parem 文件路径
     * @return 文件名称 单独文件名--不带路径
     * */
    public function field_n($dir)
    {
        if(!isset($_GET["n"]))
        {
            return null;
        }
        $n=$this->add_slashes($_GET["n"]);
        $n=$this->utf_gbk($n);  // 操作中文文件名
        if(!is_file($dir.$n))
        {
            return null;
        }
        return $n;
    }

    // 验证用户是否登录---用户访问其他页面时
    public function veri_login()
    {
        $id="id";  //---Login.class  12    price.php 47 id
        $login_file=PHP_CON."Login.php";
        if(!isset($_SESSION["token"]) || !isset($_SESSION[$id]) || empty($_SESSION["token"]) || empty($_SESSION[$id]))
        {
            $this->skip("error",$login_file,"登录信息失效");
        }

        $g_u=explode(DE_LIMITER,$_SESSION[$id]); // 取得组id 用户数据文件编号   用户id
        // 判断 用户数据文件是否存在
        $u_file=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.DATA_USER.DE_LIMITER.$g_u[1].self::$conf_data["DATA_EXT"];
        if(!is_file($u_file))
        {
            $this->skip("error",$login_file,"登录信息失效1");
        };
        // 判断组数据是否存在
        $g_data=$this->read_data(DATA_GROUP);
        if(!isset($g_data[$g_u[0]]))
        {
            $this->skip("error",$login_file,"登录信息失效2");
        }
        // 判断用户信息
        $u_data=$this->read_data(DATA_USER.DE_LIMITER.$g_u[0]);

        if(!isset($u_data[$g_u[1]-1]) && ($u_data[$g_u[1]-1][$id]==$_SESSION[$id]))
        {
            $this->skip("error",$login_file,"用户信息不存在");
        }
        // 身份标识 验证
        $gid_token=$this->price_she_biaoshi($g_u[0],$_SESSION["token"],$login_file);

        // 权限分配
        $url[]=PHP_CON."Price.php"; // 可访问地址
        $url[]=PHP_CON."Down.php";
        $url[]=PHP_CON."User.php?h=edit&u_id=".$_SESSION[$id]; // 进入修改页面
        $url[]=PHP_CON."User.php?h=edit_exct";  // 执行修改

        $price_data=$this->price_data_field();
        $price_data_key=array_keys($price_data);

        if(in_array($gid_token,$price_data_key))
        {
            // ["PHP_SELF"]
            if(!in_array($_SERVER["REQUEST_URI"],$url))
                $this->skip("error",PHP_CON."Price.php","您没有权限访问 操作");
        }else if($gid_token!=="Superadministrator")
        {
            $this->skip("error","/404.html","您没有权限 操作");
        }
    }

} 