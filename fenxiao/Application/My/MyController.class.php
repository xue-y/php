<?php
/** 
 * 自定义控制器类
 */

namespace My; 
use Think\Controller;

class MyController extends Controller {

    public $s_pix;
    public $u_id;
    public  function  __construct()
    {
        header("Content-Type:text/html;charset=utf-8");
        parent:: __construct();
		
		if(CONTROLLER_NAME!="Install")
		{
			$this->is_install(); // 验证是否安装过系统
		}

        if(!isset($_SESSION))
        {session_start();}
        $this->s_pix=C('SESSION_PREFIX');

        $this->u_id=isset($_SESSION[$this->s_pix.'id'])?$_SESSION[$this->s_pix.'id']:'';

        $this->is_sgin();//--------------验证用户是否登录【需要登录页面】

        $class_refuse=$this->arr_data("class_refuse");
        if(!in_array(CONTROLLER_NAME,$class_refuse)  && !$this->u_limit_is())
        { 
			 $this->error('您没有权限访问');
        } //------ // 判断当前用户是否拥有当前操作的权限
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
        // 系统设置  回收站 管理员用户  角色 权限 客户管理 -----类
        $class_limit=array("sysset","recovery","user","role","limit","customer","line","info","money");
        $action_name=array("index","add","execAdd","update","execUate","del","restore");// 方法名

        $bu_men=array("不受限","主管","咨询" ); // 公司部门

        $class_allow="Login,Install,Pass";// 不用登录即可访问的类
        $class_allow=explode(',',$class_allow);
        $class_refuse="Login,Install,Index,Main,Personal,Pass"; // 不用权限--只需登录或不登录都可访问
        $class_refuse=explode(',',$class_refuse);

        $personal_action="index,meil";

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
            "show"=>"查看",
			"moneyUate"=>"佣金",
            "money"=>"佣金"
        );
        $data=array(
            "class_refuse"=>$class_refuse,
            "class_allow"=>$class_allow,
            "action_name"=>$action_name,
            "bu_men"=>$bu_men,
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
              echo "<script>window.location.href='".__ROOT__."/Back/Login/sign'</script>";exit;
             }
             $user=D('User');
             $info=$user->login_select($_SESSION[$this->s_pix.'id']);
             $token=sha1($info['times'].$_SESSION[$this->s_pix.'id']);
             if(($info['u_name']!=$_SESSION[$this->s_pix.'n']) || ($_COOKIE[$this->s_pix.'token']!=$token))
             {

               //  $this->error('请先登录','/Back/Login/sign',3);
                echo "<script>window.location.href='".__ROOT__."/Back/Login/sign'</script>";exit;
             }
         }
        if(CONTROLLER_NAME=="Login" && ACTION_NAME!="login_out")
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


    /** 搜索 按照时间 同时把部门显示出来，
     * @parem $get get 的值
     * @parem $bu_men  数组数据
     * @parem $thak_index  任务数组数据索引下标
     * @return $w 条件数组
     * */
    public function time_search($get,$bu_men)
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


        if(isset($get["t_start"]) && !empty($get["t_start"]) && !isset($get["t_end"]))
        {
            $t_start=strtotime($get["t_start"]);
            $w["times"]=array("egt",$t_start);
        }// 时间搜索

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

    /** 删除文件 循环删除文件夹 文件
     * @parem $run_n 文件跟路径
     * @parem $v 要删除文件夹名称
     * */
    protected function unlink_f($run_n,$v)
    {
        $f_v=opendir($run_n.$v);
        while(($f=readdir($f_v))!==FALSE)
        {
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
	
	 /**判断客户是否存在
     * @parem $id 客户id
     * @parem $dmodel 是否传入实例化模型
     * @return void  不存在用户调回列表页
     * */
    protected function cus_is($id,$dmodel=null)
    {
        if(!isset($dmodel))
        {
            $dmodel=D("Customer");
        }
        $is_cus=$dmodel->is_cus($id);
        if($is_cus!=1)
        {
            $this->error("客户不存在或客户信息错误");exit;
        }
    }
	
    //咨询修改客户信息是存储的临时session ---- 删除
    protected function temp_session($field)
    {
        if(isset($_SESSION[$this->s_pix.$field]))
        {
            $_SESSION[$this->s_pix.$field]=null;
            unset($_SESSION[$this->s_pix.$field]);
        }
    }
	
	/*验证是否安装系统*/
	protected function  is_install()
	{
		// 判断安装类文件是否存在
		$class_file=APP_PATH."/Back/Controller/InstallController.class.php";
		if(file_exists($class_file))
		{
			echo "<script>alert('您没有安装系统 或 已安装没有更改安装类文件名称');</script>";
			exit;
		}
		
		// 判断锁定文件是否存在
		$lock_file='./Install/lock.txt';
		if(!file_exists($lock_file))
		{
			echo "<script>alert('您没有安装系统 或 锁定文件创建失败');</script>";
			exit;
		}
	}

}