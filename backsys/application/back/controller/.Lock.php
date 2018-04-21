<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-13
 * Time: 下午3:49
 * 安装后 禁止访问 安装模块
 */

namespace app\back\controller;
use think\Controller;

class Lock extends Controller{

    public function index()
    {
        $install_route=config("root_dir")."../route/install.php";
        $route="<?php
        Route::any('install', '/back/Login/index');";

        $route_file=file_put_contents($install_route,$route);
        if($route_file!=strlen($route))
        {
            echo lang("lock_file");
            exit;
        }else  // 保证写入成功后在跳转页面
        {
            session('HTTP_REFERER',true);
            return redirect('Login/index',["lock" => 1]);
        }
    }

} 