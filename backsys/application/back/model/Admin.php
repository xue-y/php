<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-15
 * Time: 上午10:09
 */

namespace app\back\model;
use think\Model;

class Admin  extends  Model{

    protected $pk = null;
    /** 通过用户名 查询用户信息
     * @paremn $n 用户名 type string
     * @return arr 用户信息
     * */
    public  function  login($n)
    {
       // $admin_user=Admin::where('n',"=",$n)->field('pw,role,t,ip')->select();
        $admin_user=Admin::alias('a')->where('n',"=",$n)->field('a.pw,a.role,a.t,a.ip')->join('roles b','a.role=b.rid')->select();
        return json_decode(json_encode($admin_user),true);;
    }

    /**更新登录时间
     * @paren  $n  用户名 type string
     * @return bool
     * */
    public function update_login($n)
    {
        $bool=Admin::where('n',"=",$n)->update(['ip'=>request()->ip(),'t'=>null]);
        return $bool;
    }

    //查询特别超级管理员信息
    public function selct_admin()
    {
        $admin_user=Admin::alias('a')->field('a.n,a.pw')->join('roles b','a.role=b.rid')->find();
        return json_decode(json_encode($admin_user),true);;
    }


} 