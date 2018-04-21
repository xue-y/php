<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-9
 * Time: 下午2:56
 * 安装控制器
 */

namespace app\install\controller;
use think\Controller;
use think\Exception;
use think\facade\Env;
use think\facade\Request;


class Index extends Controller{
    //进入安装页面
    public function index()
    {
        return $this->fetch('/index');
    }
    // 安装验证检查
    public function validb()
    {
        // 安装前数据信息验证
        $post=trim_str(input("post."));
        $this->vaildbinfo($post);
    }

    // 执行安装
    public function install()
    {
        if(!Request::isAjax()) // 非法请求
        {
            echo "error";exit;
        }
        $post=trim_str(input("post."));
        $status=$this->vaildbinfo($post,true);
        // 数据库信息连接失败
        if( $status=="server_error")
        {
            echo $status;
            exit;
        }
        //---------------------------------------------------检查环境
        try{
            if(version_compare(PHP_VERSION,'5.4.0','<'))
            {
                echo "Current version of PHP".PHP_VERSION."less than5.4.0";exit;
            }
            if(!extension_loaded("pdo"))
            {
                echo "Please open the PHP PDO extension";exit;
            }
            if(!extension_loaded("pdo_mysql"))
            {
                echo "Please open the PHP pdo_mysql extension";exit;
            }
            if(!extension_loaded("pdo_mysql"))
            {
                echo "Please open the PHP pdo_mysql extension";exit;
            }
            if(!extension_loaded("gd"))
            {
                echo "Please open the PHP gd extension";exit;
            }
        }catch (\Exception $e)
        {
            dump($e->getMessage());
        }
        //------------------------------------删除系统运行目录缓存开始
        try{

            dirfile(Env::get("RUNTIME_PATH"));

        }catch (\Exception $e)
        {
            dump($e->getMessage());
        }
        //------------------------------------删除系统运行目录缓存结束

        if(empty($post["prefix"]))
        {
            $post["prefix"]=$post["database"]."_";
        }else
        {
            $preg="/[\w\.\@\!\-\_]{2,10}/";
            if(!preg_match($preg,$post["prefix"]))
            {
                echo "prefix_error";
                exit;
            };
        }
        $preg="/[\w\.\@\!\-\_]{6,10}/";
        if(!preg_match($preg,$post["u_name"]))
        {
            echo "name_error";
            exit;
        };
        if(!preg_match($preg,$post["u_pass"]))
        {
            echo "pass_error";
            exit;
        };
        // 写入配置文件
        $c_f=config("root_dir")."back/config/database.php";
        if(is_file($c_f))// 如果存在原配置文件先删除
        {
            unlink($c_f);
        }
        $config="<?php
          return [
              // 主机
              'hostname'       => '{$post["hostname"]}',
              // 数据库名称
              'database'        => '{$post["database"]}',
              // 数据库用户名
              'username'        => '{$post["username"]}',
              // 数据库密码
              'password'        => '{$post["password"]}',
              //数据库端口
              'hostport'        => '{$post["hostport"]}',
              //数据表前缀
              'prefix'          => '{$post["prefix"]}',
          ];";
        // 写入配置文件
        if(file_put_contents($c_f, $config) === false)
        {
            echo "config file write error";// 写入配置文件失败
            exit;
        }

        $ip=Request::ip();
//-------------------------------------------------------------------------------------构建数据表开始
        //表的结构 `back_admin`
        $sql="CREATE TABLE IF NOT EXISTS `".$post["prefix"]."admin` (
  `n` varchar(20) NOT NULL COMMENT '".lang('s_a_n')."',
  `pw` varchar(40) NOT NULL COMMENT '".lang('s_a_pw')."',
  `t` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '".lang('s_a_t')."',
  `ip` varchar(20) NOT NULL COMMENT '".lang('s_a_ip')."',
  `role` varchar(1) NOT NULL COMMENT '".lang('s_a_role')."'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='".lang('s_system_users')."'; ";

        //表的结构 `back_roles`
        $sql.="CREATE TABLE IF NOT EXISTS `".$post["prefix"]."roles` (
  `rid` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '".lang('s_r_id')."',
  `r_n` varchar(30) NOT NULL COMMENT '".lang('s_r_n')."',
  `r_de` varchar(100) NOT NULL COMMENT '".lang('s_r_de')."',
  `r_w` tinyint(3) unsigned NOT NULL COMMENT '".lang('s_r_w')."',
  `power` text NOT NULL COMMENT '".lang('s_r_pid')."',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='".lang('s_r_table')."' AUTO_INCREMENT=1 ;";

       //表的结构 `back_users`
        $sql.="CREATE TABLE IF NOT EXISTS `".$post["prefix"]."users` (
  `uid` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '".lang('s_u_id')."',
  `n` varchar(30) NOT NULL COMMENT '".lang('s_u_n')."',
  `pw` varchar(40) NOT NULL COMMENT '".lang('s_u_pw')."',
  `t` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '".lang('s_sign_t')."',
  `ip` varchar(20) NOT NULL COMMENT '".lang('s_sign_ip')."',
  `role` tinyint(3) unsigned NOT NULL COMMENT '".lang('s_role_id')."',
  `founder` smallint(5) unsigned NOT NUL LDEFAULT '1' COMMENT '".lang('s_f_id')."',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='".lang('s_a_table')."' AUTO_INCREMENT=2 ;";

        //转存表中的数据 `back_admin`
        $pass=encry($post['u_pass']);
        $sqldata="INSERT INTO `".$post["prefix"]."admin` (`n`, `pw`, `t`, `ip`, `role`) VALUES
('".$post['u_name']."', '".$pass."', null, '".$ip."', '1'); ";
        //
        $sqldata.="INSERT INTO `".$post["prefix"]."roles` (`rid`, `r_n`, `r_de`, `r_w`,`power`) VALUES ('', '".lang('s_administrator')."', '".lang('s_all_powers')."', 1,'-1');";

//-------------------------------------------------------------------------------------构建数据表结束
        try{
            $dsn = "mysql:host=".$post["hostname"].";port=".$post["hostport"].";dbname=".$post["database"];
            $pdo = new \PDO($dsn,$post["username"],$post["password"]);
          if($post["table"]==2) // 如果数据中存在表---清空
            {
                $sql_table="show tables from {$post["database"]}";
                $res=$pdo->prepare($sql_table);//准备查询语句
                $res->execute(); //函数是用于执行已经预处理过的语句，只是返回执行结果成功或失败需要配合prepare函数使用
                $drop_table=$res->fetchAll(\PDO::FETCH_NUM);
                foreach($drop_table as $k=>$v)
                {
                    $drop_table_str[$k]=$v[0];
                }
                $drop_table=implode(",",$drop_table_str);
                $drop_sql="drop table $drop_table";
                $void=$pdo->exec($drop_sql); // 无返回值
            }
            //--------------清空运行目录
            //--------------写入数据表 写入数据
            $write_table=$pdo->prepare($sql);//准备查询语句
            $write_bool=$write_table->execute();
            if(!$write_bool)
            {
                echo $sql." | create table fail";
                exit;
            }
            $res=$void=$write_table=$write_bool=null; // 释放原来的资源

            $write_bool2=$pdo->exec($sqldata);
            if(!$write_bool2)
            {
                echo $sqldata." | write table data fail";
                exit;
            }
            echo "ok";
            $pdo=$dsn=$write_bool2=null; // 释放资源

        }catch (\Exception $e){
            dump($e->getMessage());
        }
    }

    /**安装前数据信息验证
     * @parem $post 需要验证的提交数据
     * @parem $is_return 是否返回，默认为false 直接输出
     * @rerun Void
     * */
    private function vaildbinfo($post,$is_return=false)
    {
        //使用Db 总读取没有写入配置文件之前的原配置文件中的配置信息 访问模块是就已经加载了配置信息
        //使用pdo 判断数据库是否连接成功
        try{
            $dsn = "mysql:host=".$post["hostname"].";port=".$post["hostport"].";dbname=".$post["database"];
            $pdo = new \PDO($dsn,$post["username"],$post["password"]);

            $sql="show tables from {$post["database"]}";

            $res=$pdo->prepare($sql);//准备查询语句
            $res->execute(); //函数是用于执行已经预处理过的语句，只是返回执行结果成功或失败需要配合prepare函数使用
            $result=$res->fetchAll(\PDO::FETCH_NUM);

            if(!empty($result))// 数据中存在表
            {
                if($is_return==true)
                {
                    return "table_data";
                }else
                {
                    echo "table_data";
                }
                exit;
            }else
            {   // 可以使用  数据库
                if($is_return==true)
                {
                    return "db_exis";
                }else
                {
                    echo "db_exis";
                }
                exit;
            }
        }catch (\Exception $e){
            // 连接数据库失败或没有数据库权限
            if($is_return==true)
            {
                return 'server_error';
            }else
            {
                echo 'server_error';
            }

            exit;
        }
        $pdo=$dsn=null;
    }

}