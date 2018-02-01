<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-24
 * Time: 下午5:08
 */

class Log extends Pro{

    public  function index()
    {
      $log_dir=@opendir(DATA_LOG);
      $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
      if(!isset($log_dir))
      {
          echo "打开日志目录失败";
          exit;
      }
      $list2=array();
      $i=0;
      while (($file = readdir($log_dir)) !== false)
      {
          $i++;
            if($file!="" && $file!="." && $file!="..")
            {
                $list2[$i]["t"]=date("Y-d-m H:i:s", filectime(DATA_LOG.$file));
                $list2[$i]["n"]=$this->gbk_utf($file);
            }
      }
     if(!empty($list2))
     {
         $showrow =PAGE_SIZE; //一页显示的行数
         $show_page=PAGE_SHOW; // 显示几个页码
         if(isset($_GET['p']))
         {
             $curpage=max(intval($_GET['p']),1);
         }else
         {
             $curpage=1;  // 当前页数
         }
         $total=count($list2);                // 数据开始位置    数组截取的结束位置
         $list=array_slice($list2,($curpage-1)*$showrow,$showrow);
         rsort($list); // 文件排序
     }
    $requer_file=strtolower(__CLASS__);
    require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    /** 查看文件
     * @parem $dir 文件目录
     * @parem $n  文件名
     * */
    public function log_sel($dir,$n)
    {
        $con=file_get_contents($dir.$n);
        echo "<pre style='font-size:14px;line-height:2em'>".$con."</pre>";
    }

} 