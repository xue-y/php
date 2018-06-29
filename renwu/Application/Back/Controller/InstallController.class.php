<?php
namespace Back\Controller;
use My\MyController;
class InstallController extends MyController{ //安装类文件
    function  __construct()
    {
        parent:: __construct();
        $url=parse_url($_SERVER['HTTP_REFERER']);
        $f_n=substr($url["path"],1);

        if($f_n!=="install.html")
        {  echo "page_error";die;}
        if(!isset($_POST) || empty($_POST))
        {
            echo "data_error";
            exit;
        }
    }


    // 验证安装信息
    public function index(){
        $arr['DB_HOST']=trim($_POST['host']);
        $arr['DB_USER']=trim($_POST['root']);
        $arr['DB_PWD']=trim($_POST['pass']);
        $arr['DB_NAME']=trim($_POST['dbname']);
        $arr['DB_PORT']=trim($_POST['port']);
        $info=$this->is_mysql($arr);
        echo $info;
    }

    //执行安装
    public function install()
    {
        set_time_limit(0);
        // 删除 缓存文件
        if(file_exists(L_A_D_F))
        {
            $f_d=unlink(L_A_D_F);
            if(!f_d)
            {
                echo "删除创建管理员缓存文件失败\r\n请手动删除 L_A_D_F"; exit;
            }
        }
        if(is_dir(L_O_D_F) && is_writable(L_O_D_F))
        {
            $cache_f=opendir(L_O_D_F);
            while(($c_f=readdir($cache_f)!==FALSE))
            {
                if($c_f!="." && $c_f!="..")
                {
                    $c_d=unlink(L_O_D_F.$c_f);
                    if(!isset($c_d))
                    {
                        echo "删除缓存文件失败\r\n请手动删除 L_O_D_F 文件夹下文件"; exit;
                    }
                }
            }
            closedir($cache_f);
        }else
        {
            echo "缓存文件不可写或缓存文件不存在";exit;
        }
        file_put_contents(LOG_F,''); //创建日志文件

        //------------------------------------删除系统运行目录缓存开始
        $r_dir_arr=array();
        $run_n='./Application/Runtime/';
        if(is_dir($run_n))
        {
			$run_file=$run_n."common~runtime.php";
			if(file_exists($run_file))   // 如果存在运行文件删除
				@unlink($run_file);
			
            $run=opendir($run_n);
            while(($run_f=readdir($run))!==FALSE)
            {
                if($run_f!="." && $run_f!="..")
                {

                    if(is_dir($run_n.$run_f))
                    {
                        array_push($r_dir_arr,$run_f);
                    }
                }
            }
        }
        if(!empty($r_dir_arr))
        {
            $log_f=implode("|",$r_dir_arr);
            file_put_contents(LOG_F,$log_f);
            foreach($r_dir_arr as $v)
            {
                $this->unlink_f($run_n,$v);
            }
        } //------------------------------------删除系统运行目录缓存结束

        if(version_compare(PHP_VERSION,'5.3.0','<'))
        {
            echo "当前php版本".PHP_VERSION."小于5.3.0";exit;
        }
        if(!extension_loaded("pdo"))
        {
            echo "请开启php pdo扩展";exit;
        }
        if(!extension_loaded("pdo_mysql"))
        {
            echo "请开启php pdo_mysql扩展";exit;
        }
        $arr['DB_HOST']=trim($_POST['host']);
        $arr['DB_USER']=trim($_POST['root']);
        $arr['DB_PWD']=trim($_POST['pass']);
        $arr['DB_NAME']=trim($_POST['dbname']);
        $arr['DB_PREFIX']=trim($_POST['dbpre']);
        $arr['DB_PORT']=trim($_POST['port']);
        $arr['table_data']=isset($_POST['table_data'])?trim($_POST['table_data']):'0';

        $filename=$_SERVER["DOCUMENT_ROOT"]."/Application/Common/Conf/config.php";

       $content="<?php
       return array(
            //'配置项'=>'配置值'
            'URL_MODEL' =>2, //REWRITE模式
            'DB_TYPE'   => 'mysql', // 数据库类型
            'DB_HOST'   => '{$arr['DB_HOST']}', // 服务器地址
            'DB_USER'   => '{$arr['DB_USER']}', // 用户名
            'DB_PWD'    => '{$arr['DB_PWD']}', // 密码
            'DB_NAME'   => '{$arr['DB_NAME']}', // 数据库名
            'DB_PREFIX' => '{$arr['DB_PREFIX']}', // 数据库表前缀
            'DB_PORT'   => '{$arr['DB_PORT']}', // 端口
            'DB_CHARSET'=> 'utf8', // 字符集
            'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
            'PWD_PREFIX'=> '&^*(*&(@..!', //密码加密秘钥
            'DEFAULT_TIMEZONE'=>'PRC', //设置时区时间
            'SESSION_AUTO_START' => TRUE, //开启session缓存
            'URL_CASE_INSENSITIVE' =>FALSE,  //url 区分大小写
           /* 'DB_FIELDS_CACHE'=>TRUE,  //启用字段缓存 默认false 关闭缓存
            'SHOW_ERROR_MSG'=> FALSE, //  关闭显示错误信息 默认true 开启
            */
        );";
       $field_num=file_put_contents($filename, $content);//----------------生成配置文件
      // $field_num=file_put_contents($filename, $content,FILE_APPEND);
        if($field_num!=strlen($content))
        {
            echo 'conf_error';  // 连接数据库或用户名密码错误
            exit;
        }
        $conn=@mysql_connect($arr['DB_HOST'].':'.$arr['DB_PORT'],$arr['DB_USER'],$arr['DB_PWD']);
        $link=@mysql_select_db($arr['DB_NAME'],$conn);

        if(!isset($link) || empty($link))
        {
            echo "database_error";  // 数据库不存在
            exit;
        }
        if($arr["table_data"]==1) // 如果数据库有表删除掉
        {
            $sql="SHOW TABLES from `{$arr['DB_NAME']}`";
            $mysql=D();
            $table_info=$mysql->query($sql);
            $table_c=count($table_info);

            if($table_c>=1)
            {
                $table_str="";
                $t_name='tables_in_'.$arr['DB_NAME'];
                foreach($table_info as $k=>$v)
                {
                    $table_n[]=$v[$t_name];
                }

                $table_n=implode(",",$table_n);
                $sql2=" DROP TABLE $table_n";
                $af=$mysql->execute($sql2);
               //删除表暂无返回值
              // 数据库中有数据
            }
            //----------------------------------------------------------删除数据库中的所有表
        }
       $create_table=$this->create_table($arr['DB_PREFIX']);
       foreach($create_table as $k=>$v)
       {
           $this->mysql_query($v,"table_error");//数据表创建失败
       }//-------------------------------防止程序卡，选择循环

        $limit_one=$this->limit_data("limit_one");
        $limit_two=$this->limit_data("limit_two");

        $insert="";
       foreach($limit_one as $k=>$v)
       {
            $id=$k+1;
            $insert.="( '$v', '',$id),";
       }
        $insert=substr($insert,0,-1);
        $sql_insert= "INSERT INTO `{$arr['DB_NAME']}`.`{$arr['DB_PREFIX']}limit` ( `n`, `execs`, `pid`) VALUES $insert";
        $this->mysql_query($sql_insert,"limit_error");//顶级权限

        $limit_two=$this->limit_data("limit_two");
        $insert="";
        $limit_tree=array();
        foreach($limit_two as $k=>$v)
        {
            $id=$k+1;
            foreach($v as $kk=>$vv)
            {
                $insert.="( '$vv[0]', '$kk',$id),";
                foreach($vv[1] as $kkk=>$vvv)
                $insert.="( '$vvv', '$kkk',$id),";
            }
        }
         $insert=substr($insert,0,-1);
         $sql_insert= "INSERT INTO `{$arr['DB_PREFIX']}limit` ( `n`, `execs`, `pid`) VALUES $insert";
         $this->mysql_query($sql_insert,"limit_error2");//2级权限

      //------------------------------------------------------------------------------------------------------插入权限数据结束
        $u_name=$this->add_slashes($_POST['u_name']);
        $u_pass=md5($this->add_slashes($_POST['u_pass']).C(PWD_PREFIX));
        $sql_insert="";
        $bu_men=$this->arr_data("bu_men");
        $bu_men=$bu_men[0];
        $sql_insert="INSERT INTO `{$arr['DB_PREFIX']}role`( `n`, `descr`, `limit_id`) VALUES ('超级管理员','拥有所有权限','-1');";
        $this->mysql_query($sql_insert,"role_error");//添加角色

        $sql_insert="";
        $sql_insert=" INSERT INTO `{$arr['DB_PREFIX']}user` (`u_name`, `u_pass`, `bumen`, `role_id`,`found`) VALUES ('$u_name','$u_pass', '$bu_men', 1,'00001');";
        $this->mysql_query($sql_insert,"user_error"); //添加--超级管理员
      //-----------------------------------------------------------------------------------------------基本数据库完成;
         echo "ok";
    }
    /*
     * 创建数据表结构
     * */
    function create_table($prefix)
    {
        $sql=array();
        $sql[]="
        CREATE TABLE IF NOT EXISTS `{$prefix}limit` (
           `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
            `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
            `execs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的类与方法',
            `pid` tinyint(4) unsigned NOT NULL COMMENT '权限分组',
             PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表' AUTO_INCREMENT=1 ;
        "; //表的结构 `my_limit`

        $sql[]="
        CREATE TABLE IF NOT EXISTS `{$prefix}problem` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题ID',
              `tit` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '问题名称',
              `descr` text COLLATE utf8_unicode_ci NOT NULL COMMENT '问题描述',
              `u_id` smallint(5) unsigned zerofill NOT NULL COMMENT '提问题人员ID',
              `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '问题是否解决,0未解决,1解决',
              `isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '问题是否删除,0未删除,1删除',
              `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '时间',
               PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题表' AUTO_INCREMENT=1 ;
        ";//表的结构 `my_problem`

        $sql[]="
        CREATE TABLE IF NOT EXISTS `{$prefix}role` (
              `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
              `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
              `descr` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色描述',
              `limit_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID',
              PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表' AUTO_INCREMENT=1 ;
        ";//--表的结构 `my_role`

        $sql[]="
        CREATE TABLE IF NOT EXISTS `{$prefix}task` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务表ID',
              `p_id` int(11) unsigned NOT NULL COMMENT '问题ID',
              `u_id` smallint(5) unsigned zerofill NOT NULL COMMENT '执行任务人员编号',
              `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '时间',
              `state` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否验证,0未验证,1通过验证,2未通过',
              `plan` text COLLATE utf8_unicode_ci NOT NULL COMMENT '解决方法',
              PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='执行任务表';
        ";//---表的结构 `my_tast`

        $sql[]="
        CREATE TABLE IF NOT EXISTS `{$prefix}user` (
              `id` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT '用户编号',
              `u_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名称',
              `u_pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
              `bumen` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '人员所在部门',
              `role_id` tinyint(2) unsigned NOT NULL COMMENT '用户角色ID',
              `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录时间',
              `found` smallint(5) unsigned zerofill NOT NULL COMMENT '用户创建人,部位管理人员',
              `mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用于忘记密码时用户找回密码',
              `is_jihuo` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '邮箱是否激活',
               `meg` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户信息提醒个数',
              PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=1 ;
        ";//--------表的结构 `my_user`

        return $sql;
    }

    /*
     * 权限数据
     * */
    function limit_data($key)
    {
        $limit_one=array("系统管理","管理员管理","任务管理");
        $limit_two=array(
            array(
                "sysset"=>array("系统设置",array("sysset-index"=>"系统设置首页","sysset-oldf"=>"清理文件"))
            ),
            array(
                "user"=>array("用户管理",array("user-index"=>"用户列表","user-add"=>"添加用户","user-execAdd"=>"添加用户",
                    "user-update"=>"修改用户","user-execUate"=>"修改用户","user-del"=>"删除用户"
                )),
                "role"=>array("角色管理",array("role-index"=>"角色列表","role-add"=>"添加角色","role-execAdd"=>"添加角色",
                    "role-update"=>"修改角色","role-execUate"=>"修改角色","role-del"=>"删除角色"
                )),
                "limit"=>array("权限管理",array("limit-index"=>"权限列表"/*,"limit-add"=>"添加权限","limit-execAdd"=>"添加权限",  "limit-update"=>"修改权限","limit-execUate"=>"修改权限","limit-del"=>"删除权限"*/
                )),
                "personal"=>array("用户信息",array("personal-index"=>"个人资料"/*,"personal-emailVer"=>"邮箱验证"*/))
            ),
            array(
                "task"=>array("任务管理",array("task-index"=>"任务列表","task-add"=>"添加任务","task-execAdd"=>"添加任务",
                    "task-update"=>"修改任务","task-execUate"=>"修改任务","task-del"=>"删除任务","task-ute"=>"执行任务",
                    "task-execUte"=>"执行任务","task-count"=>"任务统计"
                  )),
                "recovery"=>array("回收站",array("recovery-index"=>"回收站列表","recovery-restore"=>"任务还原","recovery-del"=>"任务删除"))
            )
        );
        $limit=array(
            "limit_one"=>$limit_one,
            "limit_two"=>$limit_two,
        );
        return $limit[$key];
    }


}