<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-17
 * Time: 上午11:53
 */

namespace Back\Model;
use Think\Model;

class UserModel extends Model{

    protected $readonlyField = array('id');

    public  $_validate=array(
       array('u_name','require','用户名必填'),
        array('u_name',' /^[\s\S]{2,15}$/i','用户名2--5位',0,'regex'),
        array('u_pass','/^([\w\.\@\!\-\_]){6,12}$/i','6--12位数字、英文 . ! @ - _',2,'regex'),
        array('id','require','用户编号必填'),
        array('id','/^\d{5}$/','请填写正确的编号',0,'regex'),
        array('u_pass2','u_pass','确认密码不正确',2,'confirm')
        //用户登录  添加 修改
    );
    public $_auto=array(
        array('u_pass',md5,3,'function'),
        array('u_pass','',2,'ignore'),
    );

    //登录时间
    public function login_time($id,$time)
    {
        $bool=$this->where("id=$id")->setField('times',$time);
        if(!$bool)
        {
            return "系统繁忙，稍后再试";
            write_log("登录是时间字段写入失败--提示信息：系统繁忙，稍后再试");
        }
        else
        {return 1;}
    }

    //验证是否登录
    public  function  login_select($id)
    {
        $info=$this->field('u_name,times')->find($id);
        return $info;
    }

    //根据用户ID 取得用户的角色ID
    public  function role_id($u_id)
    {
         if(!isset($u_id) || empty($u_id)) // 如果不存在session ID
         {
           //  $this->error('session 已过期，请先登录','/Back/Login/sign',3);
            echo "<script>window.location.href='/Back/Login/sign'</script>";exit;
         }else
         {
             $role_id=$this->field('role_id')->find($u_id);
             return $role_id["role_id"];
         }
    }

    //查询所有创建用户人员
    public function  found_all()
    {
        //查询创建人
	   $pix= C("DB_PREFIX");
	   $sql="select `id`,`u_name` from `{$pix}user` where `id` in ( SELECT `found` FROM __USER__ GROUP BY `found`)";
       $found=$this->query($sql);
       return $found;
    }

    //通过用户ID 判断是否存在此用户
    public function u_id_is($u_id)
    {
       $info=$this->field("u_name,bumen,role_id")->find($u_id);
       return $info;
    }

    //取得超级管理员的ID；
    public  function selec_admin()
    {
        $pix= C("DB_PREFIX");
        $sql="select ".$pix."user.id from ".$pix."user,".$pix."role where ".$pix."user.id=".$pix."user.found and ".$pix."role.id=".$pix."user.role_id and ".$pix."role.limit_id=-1";
        $a_id=$this->query($sql);
        return $a_id[0]["id"];
    }

    //判断角色id 是否有用户在使用
    public function rid_user($r_id)
    {
        $w["role_id"]=array("in",$r_id);
        $c=$this->where($w)->count();
        return $c;
    }

    //
    /**根据用户ID 取得用户信息 用户所在部门 用户姓名
     * @parem $u_id string type 用户ID
     * @return 用户信息 bumen u_name
     * */
    public function uid_info($u_id)
    {
        $w["id"]=array("in",$u_id);
        $info=$this->where($w)->field("id,bumen,u_name")->select();
        return $info;
    }

    /** 根据部门名称取得部门下的所有用户id
     * @parem $bm_n 部门名称
     * @retun 部门下的所有用户ID array type
     * */
    public function bm_u($bm_n)
    {
        $w["bumen"]=array("eq",$bm_n);
        $bm_u=$this->where($w)->field("id")->select();
        foreach($bm_u as $v)
        {
            $bm_u2[]=$v["id"];
        }
        return $bm_u2;
    }

    /** 根据用户名取得用户下所有的任务
     * @parem $u_name 模糊用户名
     * @return 多个用户ID string type
     * */
    public  function u_name($u_name)
    {
        $w["u_name"]=array("like","%$u_name%");
        $u_ids=$this->where($w)->field("id")->select();
        $u_ids2=array();
       foreach($u_ids as $v)
       {
            $u_ids2[]=$v["id"];
       }
       $u_ids2=implode(",",$u_ids2);
       return $u_ids2;
    }

    /** 更新用户表信息字段+
     * @parem $u_id 用户ID
     * @parem $p_index_id　任务表索引
     * */
    public function user_meg($u_id)
    {
        //user_meg($u_id,$p_index_id)
        /*$sql="UPDATE  __USER__  SET `meg`=CONCAT (`meg`,'$p_index_id,') WHERE id=$u_id";
        $af=$this->execute($sql);
        return $af;*/
        $w["id"]=array("eq",$u_id);
        $af=$this->where($w)->setInc('meg',1);
        return $af;
    }

    /**用户验证反馈信息--更新用户meg字段
     * */
    public function meg_ver($u_id)
    {
        $meg = $this->getFieldById($u_id,'meg');
        if($meg<0) return 0;

        $w["id"]=array("eq",$u_id);
        $af=$this->where($w)->setDec('meg',1);
        return $af;
    }


    /**用户个人资料 数据
     * @parem $u_id 用户
     * @return 个人基本信息
     * */
    public function user_data($u_id)
    {
       return $this->field("id,u_name,bumen,role_id,mail,is_jihuo,meg")->find($u_id);
    }

    /**验证邮箱---写入邮箱字段
     * */
    public function mail_ver($mail,$u_id)
    {
        $data["mail"]=$mail;
        $data["is_jihuo"]=time();
        $af=$this->where("id=$u_id")->save($data);
        return $af;
    }

    /** 验证邮箱
     * @parem $post 用户邮箱 用户ID
     * @return 用户绑定邮箱时间戳
     * */
    public function mail_ver2($post)
    {
        $w["id"]=array("eq",$post["id"]);
        $w["mail"]=array("eq",$post["mail"]);
        $t=$this->where($w)->field("is_jihuo")->find();
        return $t["is_jihuo"];
    }

    /**用户信息字段
     * @parem $u_id  用户ID
     * @return string type
     * */
    public function meg_fank($u_id)
    {
        $meg=$this->field("meg")->find($u_id);
        $meg=substr($meg["meg"],0,-1);
        return $meg;
    }

    /** 进入邮箱验证码之前验证信息是否存在
     * @parem $u_arr 用户信息数组 包括 u_id  is_jihuo
     * @return  返回用户邮箱
     * */
    public function mail_state($u_arr)
    {
        $w["id"]=array("eq",$u_arr["id"]);
        $w["is_jihuo"]=array("eq",$u_arr["is_jihuo"]);
        $w['_logic'] = 'AND';
        $mail=$this->where($w)->field("mail")->find();
        return $mail["mail"];
    }

    /** 更改邮箱激活状态
     * @parem $u_id 当前用户ID
     * @parem $mail  当前用户maill
     * @return  返回 影响的行数
     * */
    public function mail_save($u_id,$mail)
    {
        $w["id"]=array("eq",$u_id);
        $w["mail"]=array("eq",$mail);
        $w['_string'] = 'is_jihuo!=1 AND is_jihuo!=0 AND is_jihuo!=""';
        $af=$this->where($w)->setField('is_jihuo',1);
        return $af;
    }

    /** 解除邮箱绑定
     * @parem $u_id 当前用户ID
     * @parem $mail  当前用户maill
     * @return  返回 影响的行数
     * */
    public function mail_relieve($u_id,$mail)
    {
        $w["id"]=array("eq",$u_id);
        $w["mail"]=array("eq",$mail);
        $w["is_jihuo"]=array("eq",1);
        $data["mail"]=NULL;
        $data["is_jihuo"]=0;
        $af=$this->where($w)->save($data);
        return $af;
    }

    /**密码找回第一步
     * @parem $val ---用户ID mail u_name
     * @return 邮箱激活状态
     * */
    public function pass_one($val)
    {
        $w["u_name"]=array("eq",$val["u_name"]);
        $w["id"]=array("eq",$val["id"]);
        $w["mail"]=array("eq",$val["mail"]);
        $w["is_jihuo"]=array("neq",0);
        $jihuo=$this->where($w)->field("is_jihuo")->find();
        return $jihuo["is_jihuo"];
    }

    /**根据用户id 用户邮箱 邮箱状态 判断是否存在此数据
     * @parem $var arr type 用户ID mail jihuo
     * */
    public function pass_user_is($val)
    {
        $w["mail"]=array("eq",$val["mail"]);
        $w["id"]=array("eq",$val["id"]);
        $w["is_jihuo"]=array("eq",$val["jihuo"]);
        return $this->where($w)->count();
    }

    /** 验证用户id mail  邮箱激活状态 未激活直接激活 ，已激活返回查询条数
     * */
    public function pass_two($val)
    {
        $w["id"]=array("eq",$val["id"]);
        $w["mail"]=array("eq",$val["mail"]);
        if(isset($val["mail_state"]))  // 账号已绑定未激活
        {
            $w['_string'] = 'is_jihuo!=1 AND is_jihuo!=0 AND is_jihuo!=""';
            $af=$this->where($w)->setField("is_jihuo",1);
        }else
        {
            $w["is_jihuo"]=array("eq",1);
            $af=$this->where($w)->count();
        }
        return $af;
    }

    /**通过用户id 取得用户邮箱 匹配token,取得用户密码 邮箱
     * */
    public function pass_three($u_id)
    {
        $w["id"]=array("eq",$u_id);
        $w["is_jihuo"]=array("eq",1);
        $info=$this->where($w)->field("u_pass,mail")->find();
        return $info;
    }

    /** 执行修改密码
     * @parem $u_id 用户ID
     * @parem $n_pass 用户新密码
     * */
     public function pass_return($u_id,$n_pass)
     {
         $w["id"]=array("eq",$u_id);
         $data["u_pass"]=$n_pass;
         $af=$this->where($w)->save($data);
         return $af;
     }

    /** 取得当前用户自己的部门
     * @parem $u_id　当前用户id
     * @return 返回部门名称
     * */
    public function u_bumen($u_id)
    {
        return $this->getFieldById($u_id,"bumen");
    }

    /** 查询某个用户是不是某个管理员创建的
     * @parem $u_id 管理员ID
     * @parem $child_id  管理员创建的子用户 string type
     * @return 查询的条数
     * */
    public function u_child_u($u_id,$child_id,$u_bumen)
    {
        $w["id"]=array("in",$child_id);
        $w['_query'] = "found=$u_id&bumen=$u_bumen&_logic=or";
        $c=$this->where($w)->count();
        return $c;
    }
}