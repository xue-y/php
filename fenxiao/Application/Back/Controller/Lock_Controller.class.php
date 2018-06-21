<?php
namespace Back\Controller;
use Think\Controller;
class LockController extends Controller{ //安装程序完成之后 锁定文件

    function index()
    {
        header("Content-Type:text/html;charset=utf-8");
		
        $old_name = MODULE_PATH.'Controller/InstallController.class.php';
        $new_name = MODULE_PATH.'Controller/Install_Controller.class.php';
	
        if(file_exists($new_name)!=TRUE)
        {
            $bool=rename($old_name,$new_name);
            if(!file_exists($new_name) ||  file_exists($old_name) || !isset($bool))
            {
				exit('安装类文件删除失败');
			}
        }else
		{
		 //  exit('原安装锁定类文件已存在请手动删除');
		}
		
		 $install_dir='./Install';
		 if(is_dir($install_dir) != TRUE)
		 {
			 mkdir($install_dir,777);
		 }
		 if(!is_writable($install_dir))
		 {
			 exit($install_dir."目录不可写无法创建锁定文件请手动创建");
		 }
		 $lock=$install_dir.'/lock.txt';
		
		 $f_len=file_put_contents($lock,"lock");
		 
		 if(!isset($f_len) || $f_len!=4)
		 {
			echo "锁定文件失败，请手动创建";	 
		 }
		 $url=__MODULE__."/Login/sign?state=Lock";
		 echo "<script>window.location.href='".$url."'</script>";
    }

}