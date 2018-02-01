<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-1
 * Time: 下午5:26
 */

/*模板类 調用實例
$class_down=new Tem();
$class_down->down_f($_GET["f"],"../Data/Export/");
 * */
/*require "Com.php";*/
class Tem extends Pro{

    /** 执行生成静态访问页面
    * @parem $tem_dir 模板文件名
     * @parem $view_dir 访问页面文件名
     * @parem $assin 传送数据
     * @parem ? ob_get_clear 一次只可执行一次
     * */
    public function exct_write_html($tem_dir,$view_dir,$assin=null)
    {
        if(isset($assin))
        {
            $all_data=$assin;
        }
        ob_start(); // --------------------控制输出量
        require $tem_dir;
        $c=ob_get_contents();
           ob_clean();
        $f=file_put_contents($view_dir,$c);
        if($f<1)
            exit("生成文件失败");
    }

    /** 判断检验 生成静态页面
     * @parem @$tem 模板文件名 不需要有后缀
     * @parem $assin 模板数据
     * @parem $is_update 是否强制更新 默认不更新为false
     * @parem $p 分页参数
     * @parem $view_dir 生成的静态模板路径
     * */
    public  function write_html($tem,$assin=null,$is_update=false,$p=null,$view_dir=null,$view_n=null)
    {
        if(isset($view_n))  // 判定用户是否从新定义视图文件名
        {
            $new_tem=$view_n;
        }else
        {
            $new_tem=strtolower($tem);
        }

        if(isset($p) && (intval($p)>=1)) // 判断用户生成的 视图是否传参
        {
            $view=$new_tem.'_'.$p.self::$conf_data["HTML_EXT"];
        }else
        {
            $view=$new_tem.self::$conf_data["HTML_EXT"];
        }
        $tem_dir=self::$conf_data["TEM_DIR"].$tem.self::$conf_data["TEM_EXT"];
        if(is_file($tem_dir))
        {
            if(isset($view_dir))   // 判定用户是否重新定义 视图路径
            {
                $view_dir=$view_dir.$view;
            }else
            {
                $view_dir=self::$conf_data["VIEW_DIR"].$view;
            }

             if($is_update==true || !is_file($view_dir))
             {
                $this->exct_write_html($tem_dir,$view_dir,$assin);
            }else
            {
                $t_time=filemtime($tem_dir);
                $v_time=filemtime($view_dir);
                if($t_time>$v_time)
                {
                    $this->exct_write_html($tem_dir,$view_dir,$assin);
                }
            }
        }else
        {
            exit($tem_dir."模板文件不存在或模板文件错误");
        }
    }



} 