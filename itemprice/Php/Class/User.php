<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-9
 * Time: 下午5:17
 */
if(!defined('IN_SYS')) {
    header("Location:/404.html");
    exit();  // 跳转404
}
class User extends Pro{
    private $c_name="用户"; // 当前操作对象
    private $c_name_g="用户组"; // 当前操作组元素
    public $g_id_field="g_id";// 当前操作组字段名称
    public $id_field="u_id"; // 当前操作元素字段名称
    public $n_field="u_n"; // 当前操作元素名称字段
    private $table=DATA_USER; // 当前操作的表
    private $table_parent=DATA_GROUP;//当前操作的父表
    private $g_url=null;


    public function __construct()
    {
        parent::__construct();
        $this->g_url=PHP_CON.ucwords($this->table_parent).self::$conf_data["LIB_EXT"];  // 组url ;
    }
    public function index()
    {
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        if(filter_has_var(INPUT_GET,$this->g_id_field)) //-------------------------------------查看指定分组
        {
            $gid=intval($_GET[$this->g_id_field]);
            $gid_data=$this->read_data($this->table_parent); // 判断组是否存在
            if(!isset($gid_data[$gid]) || empty($gid_data[$gid]))
            {
                $this->skip("error",__CLASS__.self::$conf_data["TEM_EXT"],"所选类型/组不存在 操作");
            }
            $user_file=strtolower(__CLASS__).DE_LIMITER.$gid;
            $list2=$this->read_data($user_file);//---------如果没有组成员文件返回空数组
            if(!isset($list2)  || empty($list2))
            {
                $list=array();
            }else
            {
                $all_data["g"]=$gid_data[$gid];
            }
        }else                           //-------------------------------------------查看首页分组
        {
            $user_f=$this->table.DE_LIMITER;
            $f_ext=self::$conf_data["DATA_EXT"];

           if(is_dir($_SERVER['DOCUMENT_ROOT'].DATA_TXT))
           {
               if ($dh = opendir($_SERVER['DOCUMENT_ROOT'].DATA_TXT))
               {
                   $list=array();
                   while (($file = readdir($dh)) !== false)
                   {
                      $re="/($user_f\d+)$f_ext/";
                      if(preg_match($re,$file,$f_arr))
                      {
                          $user_data=$this->read_data($f_arr[1]);
                          if(!empty($user_data))
                                array_push($list,$user_data);
                      };
                   }
                   closedir($dh);
               }
           }
           else
           {
               throw new Exception("数据目录出错");
           }
            $all_data["g"]=$this->read_data(DATA_GROUP);
            $list2=array(); //------------------二维数组转一维数组
            if(!empty($list))
            {
                foreach($list as $k=>$v)
                {
                    foreach($v as $kk=>$vv)
                        array_push($list2,$vv);
                }
            }
        }
        if(!empty($list2))
        {
            foreach($list2 as $k=>$v)
            {
                $list2[$k][$this->g_id_field]=strtok($v["id"],DE_LIMITER);
            }
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
           /* if(isset($_GET['g_id'])) //Page.php  这个类中使用 需要手动添加参数
            {
                $url = "?p={page}&g_id=".max(intval($_GET['g_id']),0);
            }else
            {
                $url = "?p={page}";
            }*/

            $total=count($list2);                // 数据开始位置    数组截取的结束位置
            rsort($list2);
            $list=array_slice($list2,($curpage-1)*$showrow,$showrow);
            rsort($list); // 文件排序
        }
        $requer_file=strtolower(__CLASS__);
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    public function add($g_id=null)  //------进入添加用户页面
    {
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        $all_data["group"]=$this->read_data(DATA_GROUP);
        $all_data['is_g']=$g_id-1;
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__;
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    public function add_exct() // 执行添加页面
    {
        $post=$this->add_slashes($_POST);
        $this->user_gid($post["group"],DATA_GROUP); // 此数据与数据文件里的数据一致

        $Vail=self::getInstance("Vaildata");
        $Vail->name($post["name"]);
        $user_data["n"]=$post["name"];

        if(!empty($post["newpass"]))  // 如果添加用户时没有填写密码有一个默认密码
        {
            $Vail->pass($post["newpass"]);
            $Vail->repass($post["newpass"],$post["renewpass"]);
            $user_data["p"]=$post["newpass"];
        }else
        {
            $user_data["p"]=$this->pass_encrypt(self::$conf_data["DEFAULT_PASS"]);
        }
        $u_data_file=DATA_USER.DE_LIMITER.$post["group"];
        $gid=$post["group"].DE_LIMITER; // 此id 与数据文件对应

        $f_len=$this->write_date_one($user_data,$u_data_file,false,"id",$gid);
        if($f_len>1)
        {
            $this->skip(SUCCESS,PHP_CON.__CLASS__.".php","添加".$this->c_name);
        }else
        {
            $this->skip("error",null,"添加".$this->c_name);
        }
    }

    public function edit($u_id) //------进入修改页面
    {
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        // 分割 u_id
        $id_arr=explode(DE_LIMITER,$u_id);
        // 判断组文件中是否存在此组
        $all_data["group"]=$g_data=$this->read_data(DATA_GROUP);
        if(!isset($g_data[$id_arr[0]]) || empty($g_data[$id_arr[0]]))
        {
            $this->skip("error",null,"参数错误 操作");
        }
        $u_data=$this->read_data(DATA_USER.DE_LIMITER.$id_arr[0]);
        $all_data[$this->g_id_field]=$id_arr[0];
        $all_data[$this->id_field]=$id_arr[1];
        foreach($u_data as $k=>$v)
        {
            if($v["id"]==$u_id)
            {
                $all_data[$this->n_field]=$v["n"];
                break;
            }
        }
        if(!isset($all_data[$this->n_field]))
        {
            $this->skip("error",null,"没有您要更改的数据 操作");
        }
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__;
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    /** 修改用户操作
     * */
    public function edit_exct()
    {
       $post=$this->add_slashes($_POST);
       $Vail=Com::getInstance("Vaildata");

        // 判断要修改的数据是否存在
        $u_id=$post[$this->g_id_field].DE_LIMITER.$post[$this->id_field];
        // 判断用户身份
        $g_u=explode(DE_LIMITER,$_SESSION["id"]); // 取得组id 用户数据文件编号   用户id
        $gid_token=$this->price_she_biaoshi($g_u[0],$_SESSION["token"],PHP_CON."Login.php");
        $price_data_key=array_keys($this->price_data_field());

        if(in_array($gid_token,$price_data_key)) // 如果是普通用户
        {
                $skip_file=PHP_CON."Price".self::$conf_data["TEM_EXT"];
        }else if($gid_token===TOKEN)
        {
            $skip_file=PHP_CON.__CLASS__.self::$conf_data["TEM_EXT"];
        }else
        {
            session_unset();
            $this->skip("error",PHP_CON."Login.php","用户编号或密码错误 登录");
        }
        //判断是否存在此用户组
        $g_data=$this->read_data($this->table_parent);
        if(!isset($g_data[$post[$this->g_id_field]]) || !isset($g_data[$post[$this->table_parent]]))
        {
            $this->skip("error",null,"不存在此用户组 修改");
        }
        //判断用户是否篡改数据
        if(($u_id!=$_SESSION["id"] || $g_u[0]!=$post[$this->table_parent] ||  $g_u[0]!=$post[$this->g_id_field]) && ($gid_token!==TOKEN))
        {
            $info="当前用户".$_SESSION["id"]."用户名".$_SESSION["n"]."篡改id 为".$u_id;
            $this->write_log($info);
            $this->skip("error",null,"修改信息错误 操作");
        }
        // 判断数据表中是否存在此用户
        $u_data2=$new_data=$this->read_data(DATA_USER.DE_LIMITER.$post[$this->g_id_field]);
        if(!isset($u_data2[$post[$this->id_field]-1]) || ($u_data2[$post[$this->id_field]-1]["id"]!==$u_id))
        {
            $this->skip("error",null,"您要修改的数据不存在");
        }

        if(!empty($post["name"]))
        {
         //   $u_data["n"]=$Vail->name($post["name"]);
            $n=$Vail->name($post["name"]);
            $u_data["n"]= $n[0];
        }
        $this->user_gid($post[$this->table_parent],$this->table_parent);// 判断是否存在此组

        if(!empty($post["oldpass"]) && !empty($post["newpass"]) && !empty($post["renewpass"]))
        {
            $Vail->repass($post["newpass"],$post["renewpass"]);// 验证二次密码是否一致
            if($post["newpass"]!==$post["oldpass"]) // 判断新密码是否与原密码一致，如果一致不修改密码
            {
                $u_data["p"]=$this->pass_encrypt($post["newpass"]);
            }
        }

        // 如果移动了分组 并且 当前用户不可移动自己所在组
        if(($post[$this->table_parent]!=$post["g_id"]) && ($u_id!=$_SESSION["id"]) && ($gid_token===TOKEN))
        {
            $u_data["g"]=$post[$this->table_parent];
        }
        if(!empty($u_data)) // 如果用户输入值有值
        {
            $is_update=false; // 判断是否更新数据
            $old_user_data=array();//--------------原数据

            if(!isset($u_data["g"])) // ---------------------如果没有更改分组
            {
                foreach($u_data2 as $k=>$v)
                {
                    if($v["id"]==$u_id)
                    {
                        if($v["n"]!= $u_data["n"])
                        {
                            $new_data[$k]["n"]=$u_data["n"];
                            $is_update=true;
                        }else if(isset($u_data["p"]))
                        {
                            $new_data[$k]["p"]=$u_data["p"];
                            $is_update=true;
                        }
                    }
                }//-------------------------------------------更新数据--拼装数组 不包括 所属组
                if($is_update==true)
                {
                    $f_len=$this->write_date_one($new_data,DATA_USER.DE_LIMITER.$post["g_id"],true);

                }else
                {
                    $this->skip(SUCCESS,PHP_CON.__CLASS__.".php","您没有更改数据 操作");
                }
            }  // ---------------------------如果更改了分组,不是修改的自己的
            else
            {
                foreach($u_data2 as $k=>$v)
                {
                    if($v["id"]==$u_id)
                    {
                        $old_user_data_k=$k;
                        if($v["n"]!= $u_data["n"])
                        {
                            $old_user_data["n"]=$u_data["n"];
                        }else
                        {
                            $old_user_data["n"]=$v["n"];
                        }
                        if(isset($u_data["p"]))
                        {
                            $old_user_data["p"]=$u_data["p"];
                        }else
                        {
                            $old_user_data["p"]=$v["p"];
                        }
                    }
                }

                $id_pix=$post["group"].DE_LIMITER;
                $new_data2=array();
                array_push($new_data2,$old_user_data);
                $f_len=$this->write_date_one($old_user_data,DATA_USER.DE_LIMITER.$post["group"],false,"id",$id_pix);
                unset($u_data2[$old_user_data_k]);
                $f_len2=$this->write_date_one($u_data2,DATA_USER.DE_LIMITER.$post["g_id"],true);
            }
            //------------------修改完成判断是否成功
            if($f_len>1 ||  (isset($f_len2) && $f_len2>1))
            {
                if(isset($u_data["n"])) // 如果修改了名称
                {
                    $_SESSION["n"]=$u_data["n"];
                }
                $this->skip(SUCCESS,$skip_file,"修改".$this->c_name);
            }else
            {
                $this->skip("error",null,"修改".$this->c_name);
            }
        }else
        {
            $this->index();
        }
    }//--------------------------------------------------------------修改用户结束

    /** 删除用户
     * */
    public function del($u_id)
    {
        if(is_array($u_id))//------------------------批量删除
        {
            $num=count($u_id);

            if($num==1)
            {
                $this->del_one($u_id[0]);
                 return false;
            }
            $u_arr=array();
            $u_arr2=array();
            $group=$this->read_data($this->table_parent);
            foreach($u_id as $k=>$v)
            {
                $id_arr=explode(DE_LIMITER,$v);
                $u_arr[$id_arr[0]][$k]=$id_arr[1]; // 要删除的用户分组
            }

            $g_arr=array_keys($u_arr); // 用户组 数组
            foreach($g_arr as $k_g=>$g_v) //----------------------判断组是否存在
            {
                $bool=array_key_exists($g_v,$g_arr);
                if(!isset($bool))
                {
                    $str=implode(" | ",$u_arr[$g_v]);
                    $this->skip("error",null,$this->c_name.$str."所在". $this->c_name_g." [ $g_v ] 不存在 操作");
                    break;
                }
                $u_data=$this->read_data(DATA_USER.DE_LIMITER.$g_v); //---用户组id_用户id 用户组id 与数据表对应
                $old_u_data_c=count($u_data);
                foreach($u_data as $k_u=>$u_v)
                {
                    foreach($u_arr[$g_v] as $k_uu=>$u_vv)
                    {
                        if($u_v["id"]==$g_v.DE_LIMITER.$u_vv)
                        {
                            unset($u_data[$k_u]);
                        }
                    }
                }
                $f_len=$this->write_date_one($u_data,DATA_USER.DE_LIMITER.$g_v,true);
                // 判断删除个数
                if(count($u_data)==($old_u_data_c-count($u_arr[$g_v])) && $f_len>1)
                {
                    $this->skip(SUCCESS,null,"删除".$this->c_name);
                }else
                {
                    $this->skip("error",null,"删除".$this->c_name);
                }
            }
        }
       else //------------------------删除单次
        {
            $this->del_one($u_id);
        }
    }

    /**删除单个用户
     * */
    protected function del_one($u_id)
    {
        $id_arr=explode(DE_LIMITER,$u_id);
        $this->user_gid($id_arr[0],$this->table_parent);// 判断是否存在此组
        $u_data=$this->read_data(DATA_USER.DE_LIMITER.$id_arr[0]);
        foreach($u_data as $k=>$v)
        {
            if($v["id"]==$u_id)
            {
                $unset_k=$k;
            }
        }

        if(!isset($unset_k))
        {
            $this->skip("error",null,"删除数据参数错误，删除".$this->c_name);
        }
        if(isset($u_data[$unset_k]))
        {
            unset($u_data[$unset_k]);
        }else
        {
            $this->skip("error",null,"删除 $this->c_name 不存在，删除".$this->c_name);
        }

        $f_len=$this->write_date_one($u_data,DATA_USER.DE_LIMITER.$id_arr[0],true);
        if($f_len>1)
        {
            $this->skip(SUCCESS,null,"删除".$this->c_name);
        }else
        {
            $this->skip("error",null,"删除".$this->c_name);
        }
    }

} 