<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-15
 * Time: 下午4:01
 * back 模块公共函数文件 只可应用于back 模块下
 */

// 密码加密处理
function encry($pass)
{
    $before=substr(sha1(""),5,4);
    $after=substr(sha1("*./?(&%(*!~"),25,4);
    return $before.md5(sha1($pass)).$after;
}
// 左边菜单数据--数据表权限存的数组下标
function menu_data($data_name=null)
{
    $data=[];
    // 模块
    $modul[]="admin"; // 管理员
    $modul[]="sys";     //系统管理
    $modul[]="setup";   // 设置管理

    $modul_ico[]="icon-user";
    $modul_ico[]="icon-pencil-square-o";
    $modul_ico[]="icon-cog";

    // 类名与模块数组下标对应
    $class[0][]="User";
    $class[0][]="Role";
    $class[0][]="Power";

    $class[1][]="Db";
    $class[1][]="Log";

    $class[2][]="Setup";

    // 方法名称 与 类名、模块数组下标对应
    $action[]="index";
    $action[]="create"; // 添加
    $action[]="edit";   // 修改
    $action[]="delete"; // 删除
    $action[]="read";
    $action[]="update"; // 执行修改
    $action[]="save";   // 执行添加



    $modul_de[]=lang("meun_m_admin");
    $modul_de[]=lang("meun_m_sys");
    $modul_de[]=lang("meun_m_setup");

    $class_de[0][]=lang("meun_c_User");
    $class_de[0][]=lang("meun_c_Role");
    $class_de[0][]=lang("meun_c_Power");
    $class_de[1][]=lang("meun_c_Db");
    $class_de[1][]=lang("meun_c_Log");
    $class_de[2][]=lang("meun_c_Setup");

    $action_de[]=lang("meun_a_index");
    $action_de[]=lang("meun_a_create"); // 添加
    $action_de[]=lang("meun_a_edit"); // 修改
    $action_de[]=lang("meun_a_delete");// 删除
    $action_de[]=lang("meun_a_read");// 查看


    $data["m"]=$modul;
    $data["ico"]=$modul_ico;
    $data["c"]=$class;
    $data["a"]=$action;
    $data["m_de"]=$modul_de;
    $data["c_de"]=$class_de;
    $data["a_de"]=$action_de;

    switch($data_name)
    {
        case  "modul":
            return $modul;
            break;
        case "class":
            return $class;
            break;
        case "action":
            return $action;
            break;
        default:
            return $data;
            break;
    }
}

// 创建登录token 并存储
function create_token()
{
    //取得数据库中当前用户的登录时间和ip---更改用户信息时更新 token
    $cookie=cookie("login_t").cookie("login_ip");
    $token=substr(md5($cookie),5,10);
    return $token;
}

/**验证是否登录
 * @return   false 是已经登录，ture 未登录
 * */
function check_login()
{
    if(!cookie("?login_t") ||  !cookie("?login_ip") || !cookie("?login_token") || !cookie("?login_uid") || !cookie("?u_name") || !cookie("?login_role"))
    {
        return true;
    }
    $uid=cookie("login_uid");

    if($uid<1)
        return true;

    $where[]=["n","=",cookie("u_name")];
    $where[]=["ip","=",cookie("login_ip")];
    $where[]=["role","=",cookie("login_role")];
    if($uid==1 && cookie("login_role")==1) // 超级管理员
    {
        $is_user=db('admin')->where($where)->count();
    }
    else // 普通管理员
    {
        $where["uid"]=["uid","=",$uid];
        $is_user=db('users')->where($where)->count();
    }

    if(($is_user===1) && (create_token()===cookie("login_token")))
    {
        return false;// 用户已经登录过
    }
    return true; // 用户没有登录
}

// 判断当前用户是否有访问权限 false有权限访问 true 没有权限访问
function is_power()
{
    // 取得控制器 方法是 当前访问、调用页面的控制器与方法
    // 当前的访问的 模块 控制器 方法
    $operation=request()->controller();
    $operation=explode(".",$operation);
    $m=strtolower($operation[0]);
    if(!isset($operation[1])) // 解决单层控制器问题 back/Test/index
    {
        $c=ucfirst($m);
    }else
    {
        $c=ucfirst($operation[1]);
    }

    $a=request()->action();

    // 如果是超级管理员
    if(cookie("login_role")==1)
    {
        // 可以访问  返回当前位置
        $pos['m']=lang("meun_m_".$m);
        $pos['c']=lang("meun_c_".$c);
        $pos['c_url']=$m.'/'.$c;
        $pos['a']=lang("meun_a_".$a);
        return $pos;
    }
    // 普通其他用户
    if($a=="save")
    {
        $a="create";
    }
    if($a=="update")
    {
        $a="edit";
    }
    $menu_data=menu_data();
    if(!in_array($m,$menu_data["m"]))
    {
        return true;
    }
    $menu_m=array_flip($menu_data["m"]);
    $num_m=$menu_m[$m];

    if(!in_array($c,$menu_data["c"][$num_m]))
    {
        return true;
    }
    $menu_c=array_flip($menu_data["c"][$num_m]);
    $num_c=$menu_c[$c];

    if(!in_array($a,$menu_data["a"]))
    {
        return true;
    }
    $menu_a=array_flip($menu_data["a"]);
    $num_a=$menu_a[$a];

    // 数据库的 权限字段是 0-0-0|0-0-1 的形式存储
    $power_id=$num_m.'-'.$num_c."-".$num_a;
    $roles=new \app\back\model\Roles();
    $power_arr=explode("|",$roles->select_power(cookie("login_role")));
    $roles=null;
    if(!in_array($power_id,$power_arr))
    {
       return false; // 没有权限
    }else
    {
       // 可以访问  返回当前位置
        $pos['m']=lang("meun_m_".$m);
        $pos['c']=lang("meun_c_".$c);
        $pos['c_url']=$m.'/'.$c;
        $pos['a']=lang("meun_a_".$a);
        return $pos;
    }
}

/** 根据用户 角色cookie 取得权限
 * @return  权限
 * */
function power_get()
{
    $roles=new \app\back\model\Roles();
    $power=$roles->select_power(cookie("login_role"));
    $roles=null;
    return $power;
}

/**显示当前用户所拥有的所有的权限
 * @parem $power power val  type string
 * @parem $is_action 是否输出方法 默认不输出
 * @return 返回当前用户所拥有的所有的权限
 * */
function power_list($power,$is_action=false)
{
    // 验证权限----根据权限 显示左边菜单
    $menu_data=menu_data();
    $menu_data2=array();
    if($power=="-1")// 超级管理员
    {
        // 一般控制器 有 列表 增删改查  加上执行6 个方法 ，需要显示4 个 ；
        //Power Setup 有点特殊 特别提出来但写
        if($is_action)
        {
            $old_a_de=$menu_data["a_de"];
            $menu_data["a"]=$menu_data["a_de"]=null;
            foreach($menu_data["m"] as $k=>$v)
            {
                foreach($menu_data["c"][$k] as $kk=>$vv)
                {
                    if($vv=="Power")
                    {
                        $menu_data["a"][$k][$kk][0]="index";
                        $menu_data["a_de"][$k][$kk][0]=lang("meun_a_index");
                        $menu_data["a"][$k][$kk][1]="read";
                        $menu_data["a_de"][$k][$kk][1]=lang("meun_a_read");
                    }else if($vv=="Setup")
                    {
                        $menu_data["a"][$k][$kk][0]="read";
                        $menu_data["a_de"][$k][$kk][0]=lang("meun_a_read");
                        $menu_data["a"][$k][$kk][1]="set";
                        $menu_data["a_de"][$k][$kk][1]=lang("meun_a_set");
                    }else
                    {
                        foreach($old_a_de as $kkk=>$vvv)
                        {
                            // $menu_data2["a"][$k][$kk][$kkk]=$vvv;
                            $menu_data["a_de"][$k][$kk][$kkk]=lang($vvv);
                        }
                    }
                }
            }
        } // 如果需要输出方法
        return $menu_data;
    }else
    {//普通管理员
        $power_arr=array();
        $power=explode("|",$power);
        foreach($power as $k=>$v)
        {
            $p_child=explode("-",$v);
            $modul["m"][$p_child[0]]=$menu_data["m"][$p_child[0]];
            $modul["ico"][$p_child[0]]=$menu_data["ico"][$p_child[0]];
            $modul["m_de"][$p_child[0]]=$menu_data["m_de"][$p_child[0]];
            $modul["c"][$p_child[0]][$p_child[1]]=$menu_data["c"][$p_child[0]][$p_child[1]];
            $modul["c_de"][$p_child[0]][$p_child[1]]=$menu_data["c_de"][$p_child[0]][$p_child[1]];
            if($is_action)
            {
                $modul["a_de"][$menu_data["c"][$p_child[0]][$p_child[1]]][]=$menu_data["a_de"][$p_child[2]];
            }
        }

        $menu_data2["m"]=array_unique($modul["m"]);
        $menu_data2["ico"]=array_unique($modul["ico"]);
        $menu_data2["m_de"]=array_unique($modul["m_de"]);

        foreach($modul["c"] as $k=>$v)
        {
            $m_c=array_values(array_unique($v));
            if($is_action)
            {
                foreach($m_c as $kk=>$vv)
                {
                    $menu_data2["a_de"][$k][$kk]=$modul["a_de"][$vv];
                }
            }
            $menu_data2["c"][$k]=$m_c;
            $menu_data2["c_de"][$k]=array_values(array_unique($modul["c_de"][$k]));
        }
        return $menu_data2;
    }
}

// 根据创建人id 取得创建人姓名
function select_f($fid)
{
    $n=\app\back\model\Users::where("uid","=",$fid)->field("n")->find();
    return $n["n"];
}

//  取得特别超级管理员姓名
function select_fa()
{
    $n=\app\back\model\Admin::field("n")->find();
    return $n["n"];
}



