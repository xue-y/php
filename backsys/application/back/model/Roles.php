<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-16
 * Time: 下午12:29
 */

namespace app\back\model;
use think\Model;

class Roles extends Model {
    protected $pk = 'rid';

    /**根据角色id 查询权限
     * @parem  $rid 角色id
     * @return 权限值
     * */
    public function select_power($rid)
    {
        $power=Roles::field("power")->find($rid);
        return $power["power"];
    }

    /** 查询角色表中的所有角色
     * @paerm $rid 根据角色id  查询角色数组 ，默认查询首页
     * @return  角色数组
     * */
    public function select_all($rid=false)
    {
        if($rid)
        {
            $r_w=Roles::field("r_w")->find();
            $role_all=Roles::field("r_n,r_de,rid")->where("r_w","<=",$r_w[0])->order('rid')->select();
        }else
        {
            $role_all=Roles::field("r_n,r_de,rid")->order('rid')->select();
        }
        return json_decode(json_encode($role_all),true);;
    }

    /**查询指定角色
     * @paerm $rid 角色id
     * @return  查询的角色信息
     * */
    public function select_one($rid)
    {
        $role_one=Roles::field("rid,r_n,r_de,power")->find($rid);
        return json_decode(json_encode($role_one),true);
    }

    /** 根据rid 取得角色名称
     * @parem $rid 角色id
     * @return value
     * */
    public function select_n($rid)
    {
        $r_n=Roles::where("rid","=",$rid)->field("r_n")->find();
        return $r_n["r_n"];
    }

    /**根据角色id 取得权重
     * @parem $rid 角色id
     * @return value
     * */
    public function get_rw($rid)
    {
      $r_w=Roles::where("rid","=",$rid)->field("r_w")->find();
      return $r_w["r_w"];
    }

    /**根据角色id 查询小于等于自己的角色
     * @parem $rid 角色id
     * @return arr [rid, r_n]
     * */
    public function get_role($rid)
    {
        $get_role=Roles::alias('a')->where("a.rid","=",$rid)->field("b.rid,b.r_n")
            ->join('roles b','b.r_w >= a.r_w')->order(['rid'])->select();
        return json_decode(json_encode($get_role),true);;
    }

} 