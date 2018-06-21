<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-23
 * Time: 下午4:18
 */

namespace Back\Model;
use Think\Model;
class ProblemModel extends Model{
    public $_validate=array(
        array('tit','require','标题必填',0,'',1),
        array('tit','/^[\s\S]{2,60}$/i','标题长度2--20位',2,'regex',3),
        array('tit','','问题标题已经存在！',0,'unique',1),
   //     array('descr','/^[\s\S]{5,300}$/i','问题描述5--100',2,'regex',1),
    );
    public $_auto=array(
        array('descr','',2,'ignore'),
        array('tit','',2,'ignore'),
    );


    /** 根据问题ID 判断是否存在此任务
     * @parem $pro_id 问题ID
     * @parem $u_id
     * @return 问题信息 2维 array type
     * */
    public function is_task($pro_id,$u_id)
    {
        $w["isdel"]=array("eq",0);  // 未删除的任务
        $w["id"]=array("in",$pro_id);
       if(isset($u_id))  //当前用户自己添加的任务
       {
           $w["u_id"]=array("in",$u_id);
           $pro=$this->where($w)->field("id,tit,descr,state,u_id")->select();
       }else
       { // 如果是超级管理员
           $pro=$this->where($w)->field("id,tit,descr,times,state,u_id")->select();
       }
        return $pro;
    }

    /** 判断任务是否存在并是未完成
     * @parem $pro_id 问题ID
     * @return 提交问题人员的ID
     * */
    public function is_task_state($pro_id)
    {
        $w["id"]=array("eq",$pro_id);
        $w["state"]=array("eq",0);
        $pro=$this->where($w)->field("u_id")->find();
        return $pro;
    }


    /**根据问题id 取得问题标题
     * @parem $pro_id 问题ID
     * @parem $filed 需要取得字段
     * */
    public function pro_info($pro_id,$filed)
    {
        $w["id"]=array("in",$pro_id);
        $pro=$this->where($w)->field($filed)->select();
        return $pro;
    }

    /**统计新任务数据
     * @parem $u_id  当前用户ID
     * @return string type 问题ID
     * */
    public function new_task($u_id)
    {
        $w["isdel"]=array("eq",0);
        $w["state"]=array("eq",0);
        $w["u_id"]=array("neq",$u_id);
        $p_id=$this->where($w)->field("id")->select();
        if(!empty($p_id))
        {
            foreach($p_id as $v)
            {
                $id[]=$v["id"];
            }
            $id=implode(",",$id);
            return $id;
        }
    }

    /** 当前用户修改 问题表状态
     * @parem $id 问题ID
     * @parem $u_id 当前用户ID--限制只有提交用户者可以验证
     */
    public function save_state($id,$u_id)
    {
        $w["id"]=array("eq",$id);
        $w["u_id"]=array("eq",$u_id);
        $data["state"]=1;
        $af=$this->where($w)->save($data);
        return $af;
    }


    /** 回收站删除/还原任务 判断是否存在此任务
     * @parem $pro_id 问题ID
     * @parem $u_id 用户ID
     * @return普通用户返回数组  超级管理员返回条数int
     * */
    public function is_task_revo($pro_id,$u_id)
    {
        $w["isdel"]=array("eq",1);  // 未删除的任务
        $w["id"]=array("in",$pro_id);
        if(isset($u_id))  //当前用户自己添加的任务
        {
            $w["u_id"]=array("in",$u_id);
            $pro=$this->where($w)->field("id")->select();
            return $pro;
        }else
        { // 如果是超级管理员
            $c=$this->where($w)->count();
            return $c;
        }
    }

    /**指定用户下的问题ID
     * @parem $u_ids string type
     * @return  返回问题ID
     * */
    public function u_pro_id($u_ids)
    {
        $w["u_id"]=array("in",$u_ids);
        $p_id=$this->where($w)->field("id")->select();
        if(count($p_id)>0)
        {
           foreach($p_id as $k=>$v)
           {
               $p_id[$k]=$v["id"];
           }
            $p_id=implode(",",$p_id);
        }
        return $p_id;
    }

    /** 删除指定问题
     * @parem $p_ids 问题ID  string type
     * */
    public function u_p_del($p_ids)
    {
        $w["id"]=array("in",$p_ids);
        $d_c=$this->where($w)->delete();
        if(!isset($d_c))
            return  $this->_sql();
    }

    /**根据问题ID 查询用户ID
     * @prem $p_ids 问题ID string type
     * @return arr type 用户id 用户信息个数
     * */
    public function u_id_group($p_ids)
    {
        $w["id"]=array("in",$p_ids);
        $u=$this->where($w)->field("u_id,count(u_id) as c")->group("u_id")->select();
        return $u;
    }

    /** 查询问题表最小的时间
     * */
    public function min_t()
    {
       return $this->min('times');
    }
} 