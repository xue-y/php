<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-18
 * Time: 上午9:04
 */

namespace Back\Model;
use Think\Model;
class RoleModel extends Model{

    protected $readonlyField = array('id');

    public  $_validate=array(
        array('n','require','角色名必填',0,'',1),
        array('n',' /^[\s\S]{2,15}$/i','名称2--5位',0,'regex'),
        array('n','','角色名称已经存在！',0,'unique',1),
        array('descr','/^[\s\S]{5,150}$/i','问题描述5--50',2,'regex',1),
    );
    public $_auto=array(
        array('n','',2,'ignore'),
        array('descr','',2,'ignore')
    );

    //根据角色ID查询权限ID
    public  function limit_id($r_id)
    {
        $limit_id=$this->field("limit_id")->find($r_id);
        return $limit_id["limit_id"];
    }

    //根据角色id 取得角色名称
    public  function  role_n($role_id)
    {
        if(is_array($role_id))
        {
            $role_id=implode(',',$role_id);
            $w['id']=array('in',$role_id);
        }else
        {
            $w['id']=array('eq',$role_id);
        }

        $role_n=$this->where($w)->field('n,id')->select();
        return $role_n;
    }

    /**根据角色ID 判断是否存在此角色
     * @parem $r_id 角色ID
     * @return array type (c:总条数，n:角色名称）
     * */
    public function is_role_id($r_id)
    {
        $w["id"]=array("in",$r_id);
        $r_info=$this->where($w)->field("n")->select();
        foreach($r_info as $v)
        {
            $n[]=$v["n"];
        }
      $c=count($r_info);
        return array(
            "c"=>$c,
            "n"=>$n
        );
    }

    /** 查询所有角色
     * @parem $is_admin  是否除去超级管理员--存在取得的所有，不存在去除超级管理员
     * @return 二维数组 角色名称 角色ID
     * */
    public function role_all($a_r_id=NULL)
    {
        if(!isset($a_r_id))
        {
            $role=$this->field("n,id")->select();
        }else
        {
            $w["id"]=array("neq",$a_r_id);
            $role=$this->where($w)->field("n,id")->select();
        }
        return $role;
    }




} 