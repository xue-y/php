<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-11
 * Time: 上午10:54
 * wx 客户访问数据
 */

namespace Home\Model;
use Think\Model;

class CustomerModel extends Model {
    protected $autoCkeckFields = false; //关闭检测字段
    protected $readonlyField = array('id');
    protected  $tableName = 'cus_detailed';
    protected $updateFields = array('wx','is_wx','sub_num','n','age','sex'); // 允许修改的字段

    public  $_validate=array(
        /* array('phone','/^1(\d){10}$/','请输入正确的手机号','regex'), // 客户端设置--客户修改手机号时不起作用
       */
        array('age','/^\d{1,3}$/','用户年龄必须是数字',2,'regex'),
    //    array('money','/^\d+$/','请输入数字',2,'regex'),
        array('n','/^[\s\S]{2,15}$/i','用户名2--5位',2,'regex'),
        array('wx','/^[\s\S]{3,60}$/','微信号字符长度3--20个字符',2,'regex'),
        array('old_pass','/^([\w\.\@\!\-\_]){6,12}$/i','原密码6--12位数字、英文 . ! @ - _',2,'regex'),
        array('pass','/^([\w\.\@\!\-\_]){6,12}$/i','新密码6--12位数字、英文 . ! @ - _',2,'regex'),
        array('pass2','pass','确认密码不正确',2,'confirm')
        //客户  修改个人信息
    );
    public $_auto=array(
        /*array('pass','',2,'ignore'),*/   // 客户端设置--- 修改密码不起作用
        array('wx','',2,'ignore'),
        array('age','',2,'ignore'),
        array('n','',2,'ignore'),
        /*array('phone','',3,'ignore'),*/  // 客户端设置--客户修改手机号是忽略空值不起作用
    );

    /** 登录验证---验证客户
     * @parem $phone 客户手机号
     * @return type arr 存在客户信息数组
     * */
    public function yan_login($phone)
    {
        $w["phone"]=array("eq",$phone);
        $w["is_del"]=array("eq",0);
        $cus=$this->table("__CUS_BASE__")->where($w)->field("id,pass")->find();
        return $cus;
    }

    /**根据客户id 取得客户佣金金额、姓名/消息个数
     * @parem $id 客户id
     * @return type arr
     * */
    public function money_n($id)
    {
        return $this->field("money,n,headimg")->find($id);
    }

    /***根据客户id 取得客户 基本信息
     * @parem $id 客户id
     * @return type arr
     * */
    public function base($id)
    {
        return $this->field("age,n,sex,wx,id,is_wx")->find($id);
    }

    /** 根据客户id 取得自己咨询的id
     * @parem $id 客户id
     * @return type char 咨询id
     * */
    public function zx_id($id)
    {
        return $this->getFieldById($id,"cid");
    }

    /** 当前登录用户的消息个数  cus_info"客户消息通知表"
     * @parem $id 客户id
     * @return 返回消息条数
     * */
    public function meg_c($id)
    {
        $w["cid"]=$id;
        $w["is_read"]=array("eq",1);
       return  $this->table("__CUS_INFO__")->where($w)->count();
    }

    /** 修改用户信息是查询手机号是否唯一
     * @parem $id 当前客户的id
     * @parem $phone  当前客户的手机号
     * @return type int 存在返回1
     * */
    public function unique_phone($id,$phone)
    {
        $w["id"]=array('neq',$id);
        $w["phone"]=array('eq',$phone);
        return $this->table("__CUS_BASE__")->where($w)->count();
    }

    /** 取得客户咨询师的信息
     * @parem $id 当前客户的id
     * */
    public function zx($id)
    {
        $w["a.id"]=array("eq",$id);
        return $this->alias("a")->where($w)->field("b.phone,b.wx,b.info,c.u_name")
            ->join("__USER__ as c ON c.id=a.cid")
            ->join("__USER_FJ__ as b ON b.id=a.cid")->find();
    }

    /** 判断数据库中是否有用户的openid
     * @parem 用户的openid
     * @return type arr
     * */
    public function openid_is($openid)
    {
        $w["a.openid"]=array("eq",$openid);
        $w["b.is_del"]=array("neq",1);
        return $this->alias("a")->where($w)->field("a.id,a.n,a.headimg,b.phone")
            ->join("__CUS_BASE__ as b ON a.id=b.id")->find();
    }

    /** 根据用户 id 取得用户信息
     * @parem $id 用户的id
     * @return type arr
     * */
    public function id_is($id)
    {
        $w["a.id"]=array("eq",$id);
        $w["b.is_del"]=array("neq",1);
        return $this->alias("a")->where($w)->field("a.n,a.headimg,b.phone")
            ->join("__CUS_BASE__ as b ON a.id=b.id")->find();
    }

    /** 取消微信绑定
     * @parem $uid取消微信绑定
     * */
    public function quBingwx($uid)
    {
        $w["id"]=array("eq",$uid);
        $data["is_wx"]=0;
        $data["openid"]=NULL;
        $bool=$this->where($w)->save($data);
        return $bool;
    }

} 