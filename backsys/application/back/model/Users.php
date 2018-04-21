<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-14
 * Time: 下午5:42
 */

namespace app\back\model;
use think\Model;


class Users extends Model{

    protected $pk = 'uid';

    //查询用户是否存在
    public  function  login($n)
    {
      //  $pu_user=Users::where('n',"=",$n)->field('uid,pw,role,t,ip')->select();
        $pu_user=Users::alias('a')->where('n',"=",$n)->field('a.uid,a.pw,a.role,a.t,a.ip')->join('roles b','a.role=b.rid')->select();
        return json_decode(json_encode($pu_user),true);
    }

    //更新登录时间
    public function update_login($id)
    {
        $bool=Users::where('uid',"=",$id)->update(['t' =>null,'ip'=>request()->ip()]);
        return $bool;
    }

    // 查询单个角色下正在使用的用户
    public function select_user($rid)
    {
        $r_user=Users::where("role",'=',$rid)->count();
        return $r_user;
    }

    // 查询多个角色正在使用的用户
    public function select_users($rid)
    {
        $rid=implode(",",$rid);
        // 查询出每个 role 下面几个用户使用
     //   $r_user=Users::where("role","in",$rid)->field("role,count('role') as c")->group("role")->having(count("role"))->select();
        $r_user=Users::where("role","in",$rid)->field("role")->group("role")->select();
        return json_decode(json_encode($r_user),true);
    }

    // 根据创建人id 取得创建人姓名
    public function select_f($fid)
    {
       $n=Users::where("uid","=",$fid)->field("n")->find();
        return $n["n"];
    }

    // 取得所有用户
    public function select_all()
    {
        $u_all=Users::field("uid,n")->select();
        return json_decode(json_encode($u_all),true);

    }
    //通过用户id 查询用户是否存在
    public  function  is_user($uid)
    {
        //  $pu_user=Users::where('n',"=",$n)->field('uid,pw,role,t,ip')->select();
        $is_user=Users::alias('a')->where('uid',"=",$uid)->field('a.n,a.pw,a.role,a.founder')->join('roles b','a.role=b.rid')->find();
        return json_decode(json_encode($is_user),true);
    }
    //通过当前用户的所有信息
    public function cur_user()
    {
        $is_user=Users::alias('a')->where('uid',"=",cookie("login_uid"))->field('a.n,a.pw,a.role,a.founder')->join('roles b','a.role=b.rid')->find();
        return json_decode(json_encode($is_user),true);
    }

    /** 比较2个用户的角色权重
     * @parem $uid 级别较大的uid 角色权重
     * @parem $uid_min 级别较小的uid 角色权重
     * @return  $uid>$uid_min ture 否则返回false
     * */
    public function compare_rw($uid,$uid_min)
    {
        $u_w=Users::alias('a')->where('uid',"=",$uid)->field('b.r_w')->join('roles b','a.role=b.rid')->find();
        $umin_w=Users::alias('a')->where('uid',"=",$uid_min)->field('b.r_w')->join('roles b','a.role=b.rid')->find();
        if(intval($u_w['r_w']) < intval($umin_w['r_w']))
        {
            return true;
        }else
        {
            return false;
        }
    }

    /** 判断一个用户是否是另一用户创建的
     * @parem $fid 创建人
     * @parem $uid 创建的用户
     * @return  是的返回 true ，是返回false
     * */
    public function is_founder($fid,$uid)
    {
        $is_user=Users::alias('a')->where('uid',"=",$uid)->field('founder')->find();
        if($is_user["founder"]==intval($fid))
        {
            return true;
        }else
        {
            return false;
        }
    }

    // 根据用户id取得创建人id 姓名
    public  function  get_foun($uid)
    {
         $founder=[];
      //  $f=Users::alias('a')->where("a.uid","=",$uid)->field("a.founder,b.uid")->join("users b",'a.founder=b.uid')->find();
        $f=Users::where("uid","=",$uid)->field("founder")->find();
        if(!$f["founder"] || empty($f["founder"]))
        {
            return false;
        }
        if($f["founder"]==1)
        {
            $founder["f_n"]=select_fa();
        }else
        {
            $founder["f_n"]=select_f($f["founder"]);
        }
        $founder["fid"]=$f["founder"];
        return $founder;
    }

    /**根据用户uid取得用户角色权重 ---- 多个用户
     * @parem $uid 用户id type string
     * @return arr [uid,r_w]
     * */
    public function get_rw($uid,$rw)
    {
        $w["a.uid"]=["a.uid","in",$uid];
        $w["b.r_w"]=["b.r_w",">",$rw];
        $r_w=Users::alias('a')->where($w)->join("roles b",'a.role=b.rid')->column("a.uid");
        return $r_w;
    }

    /** 根据用户 uid 取得 用户的创建人
     * @parem $uid 用户id type string
     * @return arr [uid,founder]
     * */
    public function get_f($uid,$fid)
    {
        $w["uid"]=["uid","in",$uid];
        $w["founder"]=["founder","=",$fid];
        $f=Users::where($w)->column("uid");
        return $f;
    }

}