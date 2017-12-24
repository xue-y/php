<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-18
 * Time: 上午10:50
 */
namespace Back\Model;
use Think\Model;
class LimitModel extends Model{
    /* *
    *读取用户的权限 根据角色id 写入权限文件
     * @parem $limit_id  string type
     * @parem $role_id int type
     * @return 1 (ok);
     * */
    public function limit_all($limit_id,$role_id)
    {
        if($limit_id=='-1')
        {//超级管理员
            $file_name=L_A_D_F;
            if(file_exists($file_name))
            { return $file_name;}
            else{
                $limit_info=$this->order("pid asc,id asc")->select();
            }
        }else{//其他用户
            $file_name=L_O_D_F.$role_id.".php";

            if(file_exists($file_name))
            { return $file_name;}
            else
            {
                $w['id']  = array('in',$limit_id);
                $limit_info=$this->where($w)->order("pid asc,id asc")->select();
            }
        }

        $data=array();
        foreach($limit_info as $v)
        {
            $v["execs"]=ucfirst($v["execs"]);
            if(strpos($v["execs"],"-")===false)
            {
                $data[L_MENU][]=$v;
            }else
            {
                $data[L_ACTION][]=$v;
            }
        }
        $con="<?php header('Content-type: text/html; charset=utf-8'); \r\n \$".L_ALL."=".var_export($data, true).";";

        file_put_contents($file_name,$con);
        if(!file_exists($file_name))
        {
            return "系统繁忙2，请稍后再试";
            $this->write_log("登录时写入权限文件失败---提示信息：系统繁忙，请稍后再试");
        }
        else
        { return $file_name;}
    }

    //后台首页读取快捷操作 ----任务
    public function  task_all()
    {
        $w["execs"]=array("neq","");
        $w["pid"]=array("eq",QUICK);
        $task=$this->where($w)->select();
    }

    //当前位置
    public  function pos(){
        $pos=array();
        $class=lcfirst(CONTROLLER_NAME);
        $w["execs"]=array("eq",$class);
        $pos['c']=$this->where($w)->getField('n');
        if(!isset($pos['c']) || empty($pos['c']))
            $pos['c']="管理系统首页";

        $action=$class.'-'.ACTION_NAME;
        $w2["execs"]=array("eq",$action);
        $pos['a']=$this->where($w2)->getField('n');
        return $pos;
    }

    /**
     * 取得当前角色所有的权限
     * @parem $limit_id($string) 要查询的权限id 类型为字符串
     * @parem $is_all 是否取出全部 默认false 不取
     * */
    public function select_limit_all($limit_id,$is_all=FALSE)
    {
        if(!isset($limit_id) || empty($limit_id))
        {
            echo "<script>alert('当前用户权限出错');window.location.href='/Back/Role/index'</script>";
            exit;
        }

        if(($limit_id=="-1" && $is_all==TRUE) ||  ($limit_id=="-1" && A_S_A==TRUE) )
        {
           $l_all=$this->order("pid asc,id asc")->select();  // 取得所有权限
        }else if($limit_id=="-1")
        {
            $w["id"]=array("not in",DENY_L_ID); //禁止取出的权限
            $l_all=$this->where($w)->order("pid asc,id asc")->select();
        }
        else
        {  //------------------------根据权限ID取得权限
            $w["id"]=array("in",$limit_id);
            $l_all=$this->where($w)->order("pid asc,id asc")->select();
        }

        foreach($l_all as $k=>$v)
        {
            if(empty($v["execs"]))
            {
                $l_one[]=$v;
            }
            else if((stripos($v["execs"],"-")===FALSE))
            {
                $l_two[]=$v;
            }
            else //if(stripos($v["execs"],"exec")==FALSE)
            {
                if($v['n'])
                $l_thr[]=$v;
            }
        }
        $l_all3=array();
        array_push($l_all3,$l_one,$l_two,$l_thr);
        return $l_all3;
    }

    /**
     * 根据权限id 判断权限表中是否存在
     * @parem $limit_id 字符串类型
     * @return c查询条数  l_id 权限ID字符串
     * */
    public function  limit_id_is($limit_id)
    {
        $old_len=count($limit_id);
        $limit_id=implode(',',$limit_id);
        $w['id']=array("in",$limit_id);
        $c=$this->where($w)->count();
        if($c==$old_len)
        {
            return array(
                "c"=>$c,
                "l_id"=>$limit_id
            );
        }
    }

    /**用户 执行任务身份
     * 判断用户是否有执行任务的权限
     * 取得 执行任务权限ID
     * */
    public function task_identity()
    {
       $sql="SELECT `id` FROM `__LIMIT__` WHERE `execs` = 'task-execUte' or `execs` = 'task-ute'";
       return $this->query($sql);
    }

    /**用户 提交任务身份
     * 判断用户是否有提交任务的权限
     * 取得 执行任务权限ID
     * */
    public function problem_identity()
    {
        $sql="SELECT `id` FROM `__LIMIT__` WHERE `execs` = 'task-execAdd' or `execs` = 'task-add'";
        return $this->query($sql);
    }

    /**系统其它操作 --当前控制器的其它操作
     * */
    public function  sysset_operate($class)
    {
        $sql="select execs,n from __LIMIT__ where execs!='$class-index' and execs like '$class-%'";
        $operate=$this->query($sql);
        return $operate;
    }
}