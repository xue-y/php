<?php
/**
 *安装类文件
 */

namespace Back\Controller;
use My\MyController;
use Think\Model;
use PDO;

class InstallController extends MyController{ 
	
	private $pdo;  // pdo 连接对象
	
	 public  function  __construct()
	 {
		parent:: __construct();
		
		// 判断锁定文件是否存在
		$lock_file='./Install/lock.txt';
		if(file_exists($lock_file))
		{
			echo "<script>alert('请删除锁定文件在执行安装');</script>";
			exit;
		}
	 }
	
	// 进入安装页面
	public function index()
	{
		$this->display();
	}

    // 验证安装信息
    public function verifyDb(){
		// 限制只得 ajax
		if (!IS_AJAX){
			exit("dir error");
		}
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
		// 限制只得 ajax
		if (!IS_AJAX){
			exit("dir error");
		}
        set_time_limit(0);
        // 删除 缓存文件
        if(file_exists(L_A_D_F))
        {
            $f_d=unlink(L_A_D_F);
            if(!$f_d)
            {
                echo "删除创建管理员缓存文件失败\r\n请手动删除 L_A_D_F"; exit;
            }
        }
        if(is_dir(L_O_D_F))
        {
            $cache_f=opendir(L_O_D_F);
			if(!isset($cache_f))
			{
				$this->write_log(L_O_D_F.'缓存文件目录打开失败');
			}else
			{
				while($c_f=readdir($cache_f))
				{
					if($c_f!="." && $c_f!="..")
					{
						$c_d=unlink(L_O_D_F.$c_f);
						if(!isset($c_d))
						{
							echo "删除缓存文件失败".PHP_EOL."请手动删除 L_O_D_F 文件夹下文件"; exit;
						}
					}
				}
			}
           
            closedir($cache_f);
        }else
        {
            echo L_O_D_F."缓存文件不可写或缓存文件不存在";exit;
        }

        //------------------------------------删除系统运行目录缓存开始
        $r_dir_arr=array();
        $run_n=RUNTIME_PATH;
        $run_file=$run_n."common~runtime.php";
        if(file_exists($run_file))   // 如果存在运行文件删除
            @unlink($run_file);

        if(is_dir($run_n))
        {
            $run=opendir($run_n);
			if(!isset($run))
			{
				$this->write_log($run_n.'运行文件目录打开失败');
			}else
			{
				 while($run_f=readdir($run))
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
        }
		
        if(!empty($r_dir_arr))
        {
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

        $filename=CONF_PATH."config.php";

        $root_dir=$_SERVER['HTTP_HOST'].__ROOT__;

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
            'URL_CASE_INSENSITIVE' =>FALSE,  //url 区分大小写
		/*	'TMPL_EXCEPTION_FILE' => 'http://'.$root_dir.'/404.html',//空模块--空控制器---空方法 调试阶段起作用
			'URL_404_REDIRECT' => 'http://'.$root_dir.'/404.html', // 404 跳转页面 部署模式--不起作用
			'ERROR_PAGE' =>'http://'.$root_dir.'/404.html', // 错误定向页面  部署模式---- 不起作用
            'DB_FIELDS_CACHE'=>TRUE,  //启用字段缓存 默认false 关闭缓存
            'SHOW_ERROR_MSG'=> FALSE, //  关闭显示错误信息 默认true 开启
            */
        );";
       $field_num=file_put_contents($filename,$content);//----------------生成配置文件

        if($field_num!=strlen($content))
        {
            echo strlen($content).'conf_error'.$field_num;  // 配置文件错误
            exit;
        }
		
		$this->conn_db($arr);// 连接数据库

		$this->pdo=NULL; // 销毁对象
		
		$mysql=new Model();
        if($arr["table_data"]==1) // 如果数据库有表删除掉
        {
            $sql="SHOW TABLES from `{$arr['DB_NAME']}`";
            //$mysql=D();
            
            $table_info=$mysql->query($sql);
            $table_c=count($table_info);

            if($table_c>=1)
            {
				// 判断是不是加外键的主表----加外键的主表要先删除从的 在删除主的，先删除主的 【references 表】失败
				// 先解除外键约束
				
				$return_re=$mysql->execute("SET FOREIGN_KEY_CHECKS =0");
				if(!isset($return_re))
				{
					exit("解除外键约束失败");
				}
				
                $table_str="";
                $t_name='tables_in_'.$arr['DB_NAME'];
                foreach($table_info as $k=>$v)
                {
                    $table_n[]=$v[$t_name];
                }
                $table_n='`'.implode("`,`",$table_n).'`';
                $drop_table=" DROP TABLE $table_n";
				$return_drop=$mysql->execute($drop_table); // 执行成功返回 int 0
					
                if(!isset($return_drop)) // 操作成功返回1 mysql_query()
                {
                    exit("删除数据库中的表失败");
                };
				
				//$return_re=$mysql->execute($references_set); //Query was empty
				$return_re=$mysql->execute("SET FOREIGN_KEY_CHECKS =1");
				if(!isset($return_re))
				{
					exit("创建外键约束失败");
				}
              // 数据库中有数据
            }
            //----------------------------------------------------------删除数据库中的所有表
        }
	   
	   $prefix=$arr['DB_PREFIX'];// 数据表前缀
	   $u_name=$this->add_slashes($_POST['u_name']);
       $u_pass=pass_md5($this->add_slashes($_POST['u_pass']));
	   
	   $bu_men=$this->arr_data("bu_men");
	   $bu_men=$bu_men[0];
	   
	   require  MODULE_PATH."Conf/sql.php"; // 引入sql
        // 捕获执行sql 语句的错误
        try{
            foreach($create_table as $k=>$v)
            {
                $mysql->execute($v);
            }//-------------------------------防止程序卡，选择循环


            $return_role=$mysql->execute($add_role);
            if(!isset($return_role) || ($return_role!=1))//添加角色
            {
                exit("role_error");
            }

            $return_user=$mysql->execute($add_user);
            if(!isset($return_user) || ($return_user!=1))//添加--超级管理员
            {
                exit("user_error");
            }

            $return_user_fj=$mysql->execute($add_user_fj);
            if(!isset($return_user_fj) || ($return_user_fj!=1))//添加--超级管理员附加表
            {
                exit("user_fj_error");
            }

            $return_limit=$mysql->execute($add_limit);
            if(!isset($return_limit) || ($return_limit<1))//添加权限
            {
                exit("limit_error");
            }
        }catch (\Exception $e){
            die($e->getMessage());
        }
      //-----------------------------------------------------------------------------------------------基本数据库完成;
        echo "ok:".__MODULE__;
    }

    /**
     * 执行mysql_query 语句
     * @param $sql sql语句
     * @param $erro 错误信息
     * @return empty
     */
    /*private function mysql_query($sql,$error)
    {
        $re=mysql_query($sql); //添加--数据
        if(!$re)
        {
            echo $error;  // 添加数据失败
            exit;
        }
        mysql_free_result($re);
    }*/

    /*
     **验证服务器数据库信息,返回数据库状态
     *@parem $arr 数据库数组信息
    */
    private function is_mysql($arr)
    {
        $this->conn_db($arr);// 连接数据库
		  
		$sql="SHOW TABLES from {$arr['DB_NAME']}";
		
		/*$re=mysql_query($sql);
		$table_n=mysql_num_rows($re);*/ // mysql 
		
		$is_t=$this->pdo->query($sql);
		$row=$is_t->fetchColumn();  // 如果有表返回 第一行结果集的数据 stying 类型；没有数据返回booler 值
		$is_t->closeCursor(); // 关闭游走光标
		
		//if($table_n>1)   //mysql 
		if(gettype($row)=="string")
		{
			exit("table_data"); // 数据库中有数据
			$this->pdo=NULL;
		}
	
		$info="db_exis";
		return $info;
    }
	
	// 连接数据库
	private function conn_db($arr)
	{
		/*$conn=@mysql_connect($arr['DB_HOST'].':'.$arr['DB_PORT'],$arr['DB_USER'],$arr['DB_PWD']) or die("server_error");
        $link=@mysql_select_db($arr['DB_NAME'],$conn) or die("db_nois"); // 数据库不存在
		mysql_query( 'SET NAMES utf8', $conn );
		*/
		
		 try{
            $dsn='mysql:host='.$arr['DB_HOST'].';port='.$arr['DB_PORT'].';dbname='.$arr['DB_NAME'];
            $username=$arr['DB_USER'];
            $passwd=$arr['DB_PWD'];
		
            $this->pdo=new PDO($dsn,$username,$passwd);
			$this->pdo->query("set names utf8");

        }catch (\Exception $e)
        {
			$this->pdo=NULL;
            die("Error: " . $e->getMessage());
        }
	}

}