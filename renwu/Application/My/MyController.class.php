<?php
namespace My; // 自定义控制器类
use Think\Controller;

class MyController extends Controller {

    public $s_pix;
    public $u_id;
    public  function  __construct()
    {
        header("Content-Type:text/html;charset=utf-8");
        parent:: __construct();

        if(!isset($_SESSION))
        {session_start();}
        $this->s_pix=C('SESSION_PREFIX');

        $this->u_id=isset($_SESSION[$this->s_pix.'id'])?$_SESSION[$this->s_pix.'id']:'';
        $this->is_sgin();//--------------验证用户是否登录【需要登录页面】

        $class_refuse=$this->arr_data("class_refuse");
        if(!in_array(CONTROLLER_NAME,$class_refuse) && !$this->u_limit_is())
        {
               $this->error('您没有权限访问');
        } //------ // 判断当前用户是否拥有当前操作的权限
    }

    /**
     * 执行mysql_query 语句
     * @param $sql sql语句
     * @param $erro 错误信息
     * @return empty
     */
    protected function mysql_query($sql,$error)
    {
        $re=mysql_query($sql); //添加--数据
        if(!$re)
        {
            echo $error;  // 添加数据失败
            exit;
        }
        mysql_free_result($re);
    }

    /*
     验证服务器数据库信息,返回数据库状态
     error_reporting(0);
     ini_set('display_errors',0);
    */
    protected function is_mysql($arr)
    {
        $conn=@mysql_connect($arr['DB_HOST'].':'.$arr['DB_PORT'],$arr['DB_USER'],$arr['DB_PWD']);
        if(!isset($conn) || empty($conn))
        {
            /* $info=array(
                 "code"=>"server_error",
                 "info"=>"连接服务器失败"
             );*/
            $info="server_error";
            return $info;
        }
        else
        {
            $link=@mysql_select_db($arr['DB_NAME'],$conn);
            if(!isset($link) || empty($link))
            {
                /* $info=array(
                     "code"=>"db_ok",
                     "info"=>"信息正确，创建新的数据库"
                 );*/
                $info="db_ok";
                return $info;
            }
            else
            {
                $sql="SHOW TABLES from {$arr['DB_NAME']}";
                $re=mysql_query($sql);
                $table_n=mysql_num_rows($re);
                if($table_n>=1)
                {
                    echo "table_data";exit; // 数据库中有数据
                }
                /* $info=array(
                     "code"=>"db_exis",
                     "info"=>"信息正确，已存在此数据库，直接使用---连接成功"
                 );*/
                $info="db_exis";
                return $info;
            }
        }
    }

    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
     protected  function add_slashes($string)
    {
        if(!is_array($string)) return addslashes(trim($string));
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
        if(!is_array($string)) return stripslashes($string);
        foreach($string as $key => $val) $string[$key] =$this->str_slashes($val);
        return $string;
    }

    /*
     * 类名 方法名 部门名称的定义
     * */
    protected function arr_data($key)
    {
        $class_limit=array("sysset","recovery","user","role","limit","info","problem","task");
        $action_name=array("index","add","execAdd","update","execUate","del","restore","count");

        $bu_men=array("不受限","企划","导医","院长","行政院长","经营院长","对外联络部","查体主任","现场咨询",
           "皮肤科","形体","护理","网电咨询" ,"客服","导医","手术室","办公室","人力资源","财务室","药房","保洁" );

        $rw_state=array("未完成"=>"0","已完成"=>"1");
        $task_state=array("未验证","通过验证","未通过");

        $class_allow="Login,Install,Pass";// 不用登录即可访问
        $class_allow=explode(',',$class_allow);
        $class_refuse="Login,Install,Index,Main,Personal,Pass"; // 不用权限--只需登录或不登录都可访问
        $class_refuse=explode(',',$class_refuse);

        $personal_action="index,meil,megList,megShow";

        $allow_task="Task-add,Task-index,Task-count";//任务的快捷操作--允许列出的栏目
        $allow_task=explode(',',$allow_task);

        $limit_ico=array(
            "cog","user","pencil-square-o"
        ); // -----------------------------菜单图标

        $action_name=array(
            "add"=>"添加",
            "execAdd"=>"添加2",
            "update"=>"修改",
            "execUate"=>"修改2",
            "del"=>"删除",
            "restore"=>"还原",
            "count"=>"统计",
            "execute"=>"做任务"
        );
        $data=array(
            "class_refuse"=>$class_refuse,
            "class_allow"=>$class_allow,
            "action_name"=>$action_name,
            "allow_task"=>$allow_task,
            "bu_men"=>$bu_men,
            "rw_state"=>$rw_state,
            "task_state"=>$task_state,
            "ico"=>$limit_ico
        );
        return $data[$key];
    }

    /*
     * 验证是否登录
     * */
    protected function is_sgin()
    {
         $class_allow=$this->arr_data("class_allow"); //允许任何人可访问的类
         if(!in_array(CONTROLLER_NAME,$class_allow))
         {
             if(!isset($_SESSION[$this->s_pix.'id']) || !isset($_SESSION[$this->s_pix.'n']) || !isset($_COOKIE[$this->s_pix.'token']))
             {
              //   $this->error('请先登录','/Back/Login/sign',3);
              echo "<script>window.location.href='".__ROOT__."/Back/Login/sign'</script>";
             }
             $user=D('User');
             $info=$user->login_select($_SESSION[$this->s_pix.'id']);
             $token=sha1($info['times'].$_SESSION[$this->s_pix.'id']);
             if(($info['u_name']!=$_SESSION[$this->s_pix.'n']) || ($_COOKIE[$this->s_pix.'token']!=$token))
             {

               //  $this->error('请先登录','/Back/Login/sign',3);
                echo "<script>window.location.href='".__ROOT__."/Back/Login/sign'</script>";
             }
         }
        if(CONTROLLER_NAME=="Login")
        {
            is_login($this->s_pix);//验证是否登录
        }//------------------验证登录页面是否已经登录了
    }

    /**
     * @parem $f 权限的文件名称
     * @parem $limit_id 权限id 类型是字符串
     * */
     protected function read_limit($f,$limit_id) //读取权限时，如果不存在权限文件创建
    {
        if(!file_exists($f))
        {
            $user=D('User');
            $role_id=$user->role_id($this->u_id);//取得当前用户角色ＩＤ
            $limit=D('Limit');
            $bool=$limit->limit_all($limit_id,$role_id);
            if(!isset($bool))
            {
                echo "读取权限文件失败";
                $this->write_log("读取权限时，不存在权限文件创建权限文件失败---提示信息：读取权限文件失败");
            }
        }
    }

    /**
     * @parem $type 输出权限数组类型
     * @parem $result  返回权限数组类型
     * */
    protected function user_limit($type,$result=false) //判断当前用户身份读取相应权限
    {
        $role=D('Role');
        $user=D("User");
        $role_id=$user->role_id($this->u_id);
        $limit_id=$role->limit_id($role_id);

        $limit_all=array();
        if($limit_id=='-1')
        { //超级管理员
            $this->read_limit(L_A_D_F,$limit_id);
            require L_A_D_F;
        }
        else
        {// 普通人员
            $this->read_limit(L_O_D_F.$role_id.'.php',$limit_id);
            require L_O_D_F.$role_id.'.php';
        }

        if($type==L_MENU)
            $this->assign("limit_".L_MENU,$limit_all[L_MENU]);
        else if($type==L_ACTION)
            $this->assign("limit_".L_ACTION,$limit_all[L_ACTION]);
        else if($type==L_ALL)
            $this->assign(L_ALL,$limit_all);
        //----------------------------------------------
        if(isset($result))
        {
            return  $type==L_ALL?$limit_all:$limit_all[$type];
        }
    }



    /**
     * 根据用户的角色ID取得权限
     * @parem $r_id 需要读取用户的角色id
     * @parem $limit_id 权限ID string type
     * @is_admin 是不是超级管理员 默认不是
     * */
    protected function rid_lid($r_id,$limit_id,$is_admin=NULL)
    {
        // 先判断如果不是超级管理员的角色ID
        $limit=D('Limit');
        $f=$limit->limit_all($limit_id,$r_id);
        $limit_all=array();
        require $f;
        return $limit_all;
    }

    protected function  u_limit_id()//-----------------通过当前用户id 取得权限ID
    {
      //  $Model=new \Think\Model;
        $Model=D();
        $role_id=$Model->table("__USER__")->field('role_id')->find($this->u_id);
        $limit_id=$Model->table("__ROLE__")->field('limit_id')->find($role_id["role_id"]);
        return  $limit_id["limit_id"];
    }

    // 判断当前用户是否拥有当前操作的权限
    protected function u_limit_is(){
        $limit_action=$this->user_limit(L_ACTION,1);
        $limit_had=array();
        foreach($limit_action as $v)
        {
            array_push($limit_had,$v['execs']);
        }//取得用户已经拥有的所有类名方法名
       $active=CONTROLLER_NAME.'-'.ACTION_NAME; //----当前访问的类名与方法
        return in_array($active,$limit_had);
    }

    //打印错误信息
    /**
     * @parem $info 错误信息
     * @parem $url 默认为NULL,不终止进程
     * */
    public function write_log($info,$url=NULL)
    {
        $con= __FILE__."|".$info."|".date('Y-m-d H:i:s',time())."\r\n";
        file_put_contents(LOG_F,$con,FILE_APPEND);
        if(isset($url))
        {  exit; }
    }

    //当前位置标签
    protected function pos_tag()
    {
        $limit=D('Limit');
        $pos=$limit->pos();
        $action_name=$this->arr_data("action_name");
        $this->assign(array(
            'pos'=>$pos,"action_name"=>$action_name,
        ));
    }

    /**
     * @param $user 实例换用户表
     * @return 1/没有返回值
     */
    // 判断当前用户是不是超级管理员
    protected function is_admin($user)
    {
        $limit_id=$this->u_limit_id(); //当前用户所有的权限id;
        $found=$user->field('found')->find($this->u_id);
        if($limit_id=='-1' && $found["found"]==$this->u_id)
        {  return 1;    }
    }


    /**修改/删除问题表中的任务---数据是否存在 只可修改未完成 未删除的
     * @parem $pid 任务ID
     * @parem $problem 问题表
     * @parem $re_type 如果type有值反回ID
     * @retrun 数据信息 默认返回数据信息
     * */
    protected function update_vei($pid,$problem,$re_type=NULL)
    {
        if(!isset($pid))
        {
            $this->error("您没有选择任务");
        }
        $pro_id=$this->add_slashes($pid);
        if(is_array($pro_id))  // git post 获取的所有问题ID
        {
            $pro_id_c=count($pro_id);
            $pro_id_arr=$pro_id;
            $pro_id=implode(',',$pro_id);
        }

        $user=D("User");
        $is_admin=$this->is_admin($user);

        if(!isset($is_admin)) //普通用户
        {
            $pro=$problem->is_task($pro_id,$this->u_id);
            if(!isset($pro) || empty($pro))
            {
                $this->error("您没有权限操作此任务");
            }

            foreach($pro as $k=>$v)
            {
                $pro_id_arr2[]=$v["id"];
            }
            $pro_id_arr3=array_diff($pro_id_arr,$pro_id_arr2);
            if(!empty($pro_id_arr3))// 如果差集不为空---不是用户自己添加的任务
            {
                $pro_id_arr3=implode(" , ",$pro_id_arr3);
                $this->error("您没有权限操作编号为 $pro_id_arr3 的任务");
            }

        }else  //超级管理员
        {
            $pro=$problem->is_task($pro_id);

            if(!isset($pro) || empty($pro))
            {
                $this->error("不存在此任务");
            }
        }
        $task=D("Task"); //任务是否有人执行过
        $task_already=$task->is_pro($pro_id);
        $task_already_id["pro_id"]=array_unique($task_already["pro_id"]);
        $task_already_id=implode(" , ",$task_already["pro_id"]);
        if($task_already["c"]>0)
        {
            $this->error("编号为 $task_already_id 的任务已有 {$task_already['c']} 人执行，不可操作");
        }

        if(!isset($re_type))
        {
            return $pro[0]; //--------------进入修改页面
        }else
        {
            return $pro_id;
        }
    }

    /*
     * 任务的快捷操作
     * */
    protected function task_nimble()
    {
        $this->pos_tag();  //当前位置标签
        $action=$this->user_limit(L_ACTION,1);  // 快捷操作
        $task=array();
        $allow_task=$this->arr_data("allow_task");

        foreach($action as $k=>$v)
        {
            if(($v['pid']==QUICK) && (in_array($v['execs'],$allow_task)))
            {
                $v['execs']=str_replace('-','/',$v['execs']);
                array_push($task,$v);
            }
        }  //--------------------------当前用户所有的任务【已拥有的权限】
        $this->assign("task",$task);
    }

    /** 当前用户操作任务身份
     * */
    protected function rw_identity($user,$role,$r_id)
    {
        $task_warn=0;
        $pro_warn=0;
        if(!$this->is_admin($user))
        {
            $u_limit_id=$role->limit_id($r_id);
            $u_limit_id=explode(",",$u_limit_id);
            $limit=D("Limit");
            //执行任务
            $task_l_id=$limit->task_identity();
            foreach($task_l_id as $k=>$v)
            {
                if(in_array($v["id"],$u_limit_id))
                    $task_warn=1+$k;
            }
            //信息反馈
            $pro_l_id=$limit->problem_identity();
            foreach($task_l_id as $k=>$v)
            {
                if(in_array($v["id"],$u_limit_id))
                  $pro_warn=1+$k;
            }
        }
        if($this->is_admin($user) || $pro_warn==2)
        {
            $_SESSION[PRO_W]=PRO_W;
            $meg=$user->getFieldById($this->u_id,"meg");
            if($meg>0)
            {
                S("pro_w_n",$meg,T_INT);
            }
        }//---如果有添加任务的权限 取得消息个数

        if($this->is_admin($user) || $task_warn==2)
        {
            $_SESSION[TAST_W]=TAST_W;
            $c=$this->u_task_c("c");
            if(isset($c) && $c>0)
            {
                S("tast_w_n",$c,T_INT);  // 执行任务权限 待执行任务个数
            }
        }
    }

    /** 搜索 按照时间 同时把部门显示出来，
     * @parem $get get 的值
     * @parem $bu_men  数组数据
     * @parem $thak_index  任务数组数据索引下标
     * @return $w 条件数组
     * */
    public function time_search($get,$bu_men,$task_index)
    {
        if(isset($get["bumen"]) && !empty($get["bumen"]))
        {
            $bm_u=$this->bm_search($get["bumen"],$bu_men);
            if(isset($bm_u))
            {
                $w["u_id"]=array("in",$bm_u);
            }
        } // 点击部门--部门搜索

        if(isset($get["u_id"]) && !empty($get["u_id"]))
        {
            $w["u_id"]=array("eq", $get["u_id"]);
        }//点击用户名

        if(isset($get["state"]) && in_array($get["state"],$task_index))
        {
            $w["state"]=array("eq",$_GET["state"]);
        }// 按照状态搜索

        if(isset($get["t_start"]) && !empty($get["t_start"]) && !isset($get["t_end"]))
        {
            $t_start=strtotime($get["t_start"]);
            $w["times"]=array("egt",$t_start);
        }

        if(isset($get["t_end"]) && !empty($get["t_end"]) && !isset($get["t_start"]))
        {
            $t_end=strtotime($get["t_end"]);
            $w["times"]=array("elt",$t_start);
        }

        if(isset($get["t_end"]) && !empty($get["t_end"]) && isset($get["t_start"]) && !empty($get["t_start"]))
        {
            $t_start=strtotime($get["t_start"]);
            $t_end=strtotime($get["t_end"]);
            $w["times"]=array('between',"$t_start,$t_end");
        }
        return $w;
        //-------------------------按时间搜索
    }

    /**搜索部门任务
     * @parem $get_bumen 部门名称
     * @parem $bm_array 部门数组
     * @return 部门下的用户id string type
     * */
    public function bm_search($get_bumen,$bm_array)
    {
        if(in_array($get_bumen,$bm_array))
        {
            $user=D("user");
            $bm_u=$user->bm_u($get_bumen);
            $bm_u=implode(",",$bm_u);
            //   $w["u_id"]=array("in",$bm_u);
            return $bm_u;
        }
    }


    /* * 根据身份提示信息
     * */
    public function remind_meg()
    {
        if(isset($_SESSION[PRO_W]) && $_SESSION[PRO_W]=PRO_W) // 信息反馈
        {
           if(!S("pro_w_n"))
           {
               $user=D("User");
               $meg=$user->getFieldById($this->u_id,"meg");
               if($meg>0)
               {
                   S("pro_w_n",$meg,300);
                   $this->assign("meg",$meg);
               }
           }else
           {
               $this->assign("meg",S("pro_w_n"));
           }
        }
        if(isset($_SESSION[TAST_W]) && $_SESSION[TAST_W]=TAST_W)  //待执行任务
        {
            if(!S("tast_w_n"))
            {
                $c=$this->u_task_c("c");     //待执行任务个数
                if(isset($c) && $c>0)
                {
                    S('task_w_n',$c,300);
                    $this->assign("p_c",S("tast_w_n"));
                }
            }else
            {
                $this->assign("p_c",S("tast_w_n"));
            };
        }
    }

    /**
     * 当前用户待做的任务的问题ID
     * @parem $re_type 返回类型 默认返回数组 str  c
     * */
    protected function u_task_c($re_type=NULL)
    {
        $problem=D("Problem");
        $task=D("Task");
        $task_warn=$problem->new_task($this->u_id);
        if(isset($task_warn))
        {
            $p_id_arr=$task->u_task_is($task_warn,$this->u_id);
            if($re_type=="str")
                return implode(",",$p_id_arr);
            else if($re_type=="c")
                return count($p_id_arr);
            else
                return $p_id_arr;
        }
    }

    /** 删除文件 循环删除文件夹 文件
     * @parem $run_n 文件跟路径
     * @parem $v 要删除文件夹名称
     * */
    protected function  unlink_f($run_n,$v)
    {
        $f_v=opendir($run_n.$v);
        while(($f=readdir($f_v))!==FALSE)
        {
            file_put_contents(LOG_F,'--'.$f.'--',FILE_APPEND);
            if($f!="." && $f!="..")
            {
                $new_fn=$run_n.$v.'/'.$f;
                if(is_dir($new_fn))
                {
                    $this->unlink_f($run_n.$v.'/',$f);
                }else
                {
                    $f_d=unlink($new_fn);
                    if(!isset($f_d))
                       $this->write_log("删除缓存文件失败，请手动删除 $new_fn");
                }
            }
        }
        closedir($f_v);
    }

}