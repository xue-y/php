<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-25
 * Time: 上午11:28
 */
namespace Back\Model;
use Think\Model;
class TaskModel extends Model{

    protected $updateFields =array("state");

    public  $_validate=array(
        array('plan','require','解决方案必填'),
      //  array('plan',' /^[\s\S]{5,300}$/i','字符长度5--100个',0,'regex'),
        //解决方案
    );

    /** 查询是否可以修改/删除 此任务 ---任务是否有人执行
     * @parem $pro_id 问题ID
     * @parem $re_all 返回全部数据 array type
     * @return 默认返回问题id,总数c
     * */
    public function is_pro($pro_id,$re_all)
    {
         $w["p_id"]=array("in",$pro_id);
         $pro=$this->where($w)->field("p_id,u_id,times")->select();
         $n_prp_id=array();
         $task_u_id=array();
         foreach($pro as $k=>$v)
         {
             $n_prp_id[]=$v["p_id"];
         }
         $c=count($pro);
         if(isset($re_all))
         {
             return $pro;
         }
        else
         {
             $n_prp_id=array_unique($n_prp_id);
            return array("pro_id"=>$n_prp_id,"c"=>$c);
         }

    }


    /** 用户执行任务 等待用户验证
     * @parem $u_id 执行任务人员id
     * @parem $pro_id 问题ID
     * @parem $pro_plan 解决问题的方案
     * */
    public function u_add_task($u_id,$pro_id,$pro_plan)
    {
        $data["p_id"]=$pro_id;
        $data["u_id"]=$u_id;
        $data["times"]=time();
        if(isset($pro_plan))
        {
            $data["plan"]=$pro_plan;
        }
        $last=$this->add($data);
        return $last;
    }

    /**用户验证时更改任务表状态
     * @parem $id 任务ID
     * @parem $state 用户验证是否通过
     * @parem $u_id  限制添加人员自己验证自己
     * */
    public function save_state($id,$state,$u_id)
    {
        $w["id"]=array("eq",$id);
        $w["u_id"]=array("neq",$u_id);
        $data["state"]=$state;
        $af=$this->where($w)->save($data);
        return $af;
    }

    /**验证任务表是否存在 以及用户是否验证过
     * */
    public function task_is($t_id)
    {
        $w["id"]=array("eq",$t_id);
        $w["state"]=array("eq",0);
        $c=$this->where($w)->count();
        return $c;
    }


    /** 其他用户提交的新任务，并且当前用没有执行过
     * @parem $p_id 问题的 string type
     * @parem $u_id 当前用户ID
     * @return int type   当前用户待做任务的个数
     * */
    public function u_task_is($p_id,$u_id)
    {
        $w["p_id"]=array("in",$p_id);
        $w["u_id"]=array("eq",$u_id);
        $is_p_id=$this->where($w)->field("p_id")->select();
        if(empty($is_p_id))
        {  //如果此任务当前用户没有执行过
            $p_id=explode(",",$p_id);
            return $p_id;
        }else
        {   //如果此任务当前用户执行过
            foreach($is_p_id as $v)
            {
                $is_p_id2[]=$v["p_id"];
            }
            $p_id=explode(",",$p_id);
            $is_p_id2=array_diff($p_id,$is_p_id2);
            return $is_p_id2; //代做任务的ID；
        }

    }

    /** 删除用户提交的问题ID 查询任务
     * @parem $p_ids 根据问题id 删除任务
     * */
    public function u_t_del($p_ids)
    {
        $w["p_id"]=array("in",$p_ids);
        $d_c=$this->where($w)->delete();
        if(!isset($d_c))
            return  $this->_sql();
    }

    /** 查询用户执行的任务【其他用户提交的任务未验证的】
     * @parem $u_ids 问题提交用户ID  string type
     * @return 问题ID
     * */
    public function u_task_id($u_ids)
    {
        $w["u_id"]=array("in",$u_ids);
        $w["state"]=array("eq",0); // 未验证的任务 ----对应减少提交问题用户的信息
        $p_id=$this->where($w)->field("p_id")->select();
        if(count($p_id)>0)
        {
            foreach($p_id as $k=>$v)
            {
                $p_id[$k]=$v["p_id"];
            }
            $p_id=implode(',',array_unique($p_id));
            return $p_id;
        }
    }

    /** 删除用户执行的任务【其他用户提交的任务已验证的】
     * @parem $u_ids  执行任务用户ID--要删除用户的id
     * */
    public function u_task_del($u_ids)
    {
        $w["u_id"]=array("in",$u_ids);
        $d_c=$this->where($w)->delete();
        if(!isset($d_c))
            return  $this->_sql();
    }

    /** 查询任务表最小的时间
     * */
    public function min_t()
    {
        return $this->min('times');
    }
}
