<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-1
 * Time: 下午6:32
 * 主要用于 使用用户导出excel  管理员导入数据 excel -->txt
 */

class Excel extends Com {
    /** 数组写入 excel 表格 支持2007 版本的 xls，xlsx 不支持，其他版本未测试
     * @parem $data type 二维数据 tit/con 2个数组
     * */
    //调用实例
     /* require "../Library/Excel/writer.php";
        $class_write=new writer();
        $data["tit"]=array("id", "时间", "名称");
        $data["con"] = array(
            0 => array(
                "id" => "1",
                "date" => date("Y-m-d"),
                "name" => "素材火"
            ),
            1 => array(
                "id" => "2",
                "date" => date("Y-m-d"),
                "name" => "分享微博送30积分"
            )
        );
        $f_status=$class_write->writer_excel($data);*/

    /** 写入 excel  tit con  为指定字段
     * @parem  $data写入 的数据
     * @parem $f_dir 文件写入存放的目录
     * @parem $is_name false默认自动命名  使用原名称传入文件名
     * @return 返回 全文件名
     * */
    public function writer_excel($data,$f_dir,$is_name=null)
    {
        if(!isset($f_dir) || !isset($data))
        {
            exit("数据或 写入目录不存在或错误");
        }
        if(isset($is_name))
        {
            $f_name=$_SERVER['DOCUMENT_ROOT'].$f_dir.$is_name.".xls";
        }else
        {
            $f_name=$this->file_name($f_dir,".xls");
        }
        if(count($data["con"])==count($data["con"],1)) //----------------一维数组 字段属性个数
        {
            $con = implode(PHP_EOL,$data["con"]);
            $tit=$data["tit"];
        }else                          //----------------二维数组
        {
            $tit=implode("\t",$data["tit"]);
            foreach($data["con"] as $k=>$v)
            {
                $data["con"][$k] = implode("\t", $v);
            }
            $con = implode(PHP_EOL,$data["con"]);
        }
        $conts=$tit.PHP_EOL.$con;
        $conts=$this->utf_gbk($conts);
        $f_len=file_put_contents($f_name,$conts);
        if(!is_file($f_name))
        {
            $this->skip("error",null,"写入Excel文件失败");
            //   exit("写入Excel文件失败");
        }
        else
            return $f_name;
    }

    /** excel 数据转 数组形式 的 txt
     * @pram $f_name  excel全文件名
     * @return 成功返回 txt 文件名称 失败返回 null
     * */
    public function read_excel($f_name)
    {
        if(!is_file($f_name))
           exit($f_name."文件不存在或文件不正确");
        $file = fopen($f_name, 'r');
        $str_rep=array(PHP_EOL, "\r", "\n","\t"); // 需要替换的字符
        $file_re = fgets($file);
        $tit = explode("\t", $file_re); // title
        $data = array();
        $count = 0;
        if(count($tit,1)==1)  //----- 一维数组  判断是不是只有一个属性 标题只有一个字段
        {
            while(!feof($file)) {
                $row = fgets($file);
                $count ++;
                if($count>=1)
                {
                    array_push($data,$this->gbk_utf(str_replace($str_rep, '', $row)));
                }
            }
        }
        else                 //------------ 二维数组
        {
            $count = 0;
            while(!feof($file)) {
                $count ++;
                $row = fgets($file);
                $row = explode("\t", $row);

                if($count>=1)   //内容读取
                {
                    foreach($tit as $k => $v)
                    {
                        $str = str_replace($str_rep, "", $v);
                        $tit_str=$this->gbk_utf($str);
                        $row_v=str_replace($str_rep,'',$row[$k]);

                        if(!empty($row_v))
                        {
                           $row_v=$this->gbk_utf($row_v);
                        }else
                        {
                            $row_v= $row_v;
                        }
                        $data[$count-1][$tit_str] =$row_v;
                    }
                }
            }
        }
        fclose($file);
        $f_ext=self::$conf_data["EXCEL_EXT"];
        $f_name2=str_replace($f_ext,self::$conf_data['DATA_EXT'],$f_name);
        $data=json_encode($data);
        $len=file_put_contents($f_name2,$data);
        if(!is_file($f_name2))
        {
            $bool=unlink($f_name);
            if(!isset($bool))// 删除原文件
            {
                $this->skip("error",null,"原文件删除失败请手动删除<br/> 操作");
            }else
            {
                $this->skip("error",null,"写入数据");
            }
        }else
        {
            return $f_name2;
        }
    }
} 