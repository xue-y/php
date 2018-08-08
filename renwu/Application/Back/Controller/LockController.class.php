<?php
namespace Back\Controller;
use Think\Controller;
class LockController extends Controller{ //安装程序完成之后

    function index()
    {
        // 判断是否安装过
        if(file_exists("lock.txt")  && file_exists("Install/lock.html") && file_exists(APP_PATH."Back/Controller/Lock_Controller.class.php"))
        {
            echo "<script>window.location.href='/Back/Login/sign'</script>";exit;
        }
        header("Content-Type:text/html;charset=utf-8");
       $old_name = dirname(__FILE__).'/'.'InstallController.class.php';
       $new_name=dirname(__FILE__).'/'.'Lock_Controller.class.php';

        if(!file_exists($new_name))
        {
            $bool=rename($old_name,$new_name);
            if(!file_exists($new_name) ||  file_exists($old_name) || !isset($bool))
            {echo '安装类文件删除失败';exit;}
        }else
		{
			echo "锁定类文件已存在请手动删除"; exit;
		}

        if(!file_exists('lock.txt'))
        {
            $file_len=file_put_contents('lock.txt','lock');
            if($file_len!=strlen("lock"));
            {
                echo $file_len.'创建锁文件失败'.strlen("lock");exit;
            }
        }

        $old_name2=$_SERVER["DOCUMENT_ROOT"].'/'.'install.html';
        $new_name2=$_SERVER["DOCUMENT_ROOT"].'/'.'Install/lock.html';
        if(!file_exists($new_name2))
        {
            $bool=rename($old_name2,$new_name2);
            if(file_exists($old_name2))
                  @unlink($old_name2);
            if(!file_exists($new_name2) ||  file_exists($old_name2) || !isset($bool))
            {
                echo '安装入口文件删除失败';exit;
            }
        }
        echo "<script>window.location.href='/Back/Login/sign'</script>";exit;
    }

}