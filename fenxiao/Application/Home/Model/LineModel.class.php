<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-12
 * Time: 上午10:12
 */

namespace Home\Model;
use Think\Model;

class LineModel extends Model {
 //   protected $autoCkeckFields = false; //关闭检测字段
    protected  $tableName = 'cus_downline';
    protected $updateFields = array('wx','phone','n','remarks',"sex","age"); // 允许修改的字段
   // 推荐好友审核状态 1 未审核  2 通过审核  3 未通过审核

    public  $_validate=array(
        array('n','require','姓名必填！'),
        array('phone','/^1(\d){10}$/','请输入正确的手机号',2,'regex'),
        array('phone','','手机号已被推荐过了',2,'unique',1),
        array('age','/^\d{1,3}$/','用户年龄必须是数字',2,'regex'),
        array('n',' /^[\s\S]{2,15}$/i','用户名2--5位',2,'regex'),
    );
    // 如果值为空 忽略
    public $_auto=array(
        array('wx','',2,'ignore'),
        array('age','',2,'ignore'),
        array('n','',2,'ignore'),
        array('phone','',2,'ignore'),
    );


    /** 验证手机号是否存在---修改推荐人事
     * @parem  $phone 手机号
     * */
    public function phone_unique($phone)
    {
        return $this->where("phone=$phone")->count();
    }

    /** 客户查看自己推荐好友信息
     * @parem $tid 客户id
     * @parem $id 推荐的好友id
     * @return type arr
     * */
    public function line_info($tid,$id)
    {
        $w["tid"]=$tid;
        $w["id"]=$id;
        $info=$this->where($w)->field("phone,wx,n,age,sex,remarks,state")->find();
        return $info;
    }


    /** 根据推荐好友的id 判断用户是否存在 并且状态未审核
     * @parem $tid 客户id
     * @parem $id 推荐的好友id
     * @return type int
     * */
    public function line_is($tid,$id)
    {
        $w["tid"]=$tid;
        $w["id"]=$id;
        $w["state"]=array("eq",1);
        return $this->where($w)->count();
    }

    /** 根据推荐好友的id 判断用户是否存在 并且状态未审核或 未通过审核的
     * @parem $tid 客户id
     * @parem $id 推荐的好友id
     * @return type int
     * */
    public function line_iss($tid,$id)
    {
        $w["tid"]=$tid;
        $w["id"]=$id;
        $w["state"]=array("neq",2);
        return $this->where($w)->count();
    }

    /** 根据推荐好友的id 取得与好友相同的手机号--人员个数
     * @parem $id 推荐的好友id
     * @parem $phone 推荐好友的手机号
     * @return type int
     * */
    public function line_phone($id,$phone)
    {
        $w["id"]=array("neq",$id);
        $w["phone"]=$phone;
       return  $this->where($w)->count();
    }

    /**取得客户的下线个数
     * @parem $id 客户id
     * @return type  int
     * */
    public function cus_sub_num($id)
    {
      return  $this->where("tid=$id")->count();
    }



} 