<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-16
 * Time: 下午6:00
 */

namespace app\back\controller\admin;
use app\back\controller\Auth;
use app\back\model\Roles;
use think\facade\Request;

class Power extends Auth{

      // 列出当前用户所有的权限
      public function  index()
      {
          $power=power_get();
          $menu_data=power_list($power,true);
          $this->assign("menu_data",$menu_data);
          return $this->fetch();
      }
      // 根据角色id 查询权限
      public  function read($rid)
      {
          if(!Request::isGet())
          {
              $this->error(lang("power_role_nois"));
          }
          $rid=Request::param();

          if(empty($rid["rid"]) || intval($rid["rid"])<1)
          {
              $this->error(lang("power_role_nois"));
          }
          $role=new Roles();
          $power=$role->select_power($rid["rid"]);
          $role=null;
          if(empty($power))
          {
              $this->error(lang("power_role_nois"));
          }

          $menu_data=power_list($power,true);
          $this->assign("menu_data",$menu_data);
          return $this->fetch();
      }
}