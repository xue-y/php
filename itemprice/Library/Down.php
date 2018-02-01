<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-1
 * Time: 下午1:16
 */

/* 调用实例  下载类
$class_down=new Down();
$class_down->down_f($_GET["f"],"../Data/Export/");*/
class Down extends Com{

    /** 下载文件
     * @parem filename 下载文件名--全名
     * @parem file_dir 下载文件路径 下载文件相对于调用类文件位置
     * */

    public function down_f($filename)
    {
    //注意is_file 与 file_exists函数不能判断绝对路径 例如： /XXX/XXX.XXX ;如果最前面是域名可以访问到$filename;
       $fileinfo = pathinfo($filename);
        header('Content-type: application/x-'.$fileinfo['extension']);
        header('Content-Disposition: attachment; filename='.$fileinfo['basename']);
        header('Content-Length: '.filesize($filename));
        readfile($filename);
        exit();
    }

    /**下载 用户导出 网页  用于ajax
     * @parem $data  模板数据
     * @parem $price 模板文件名
     * @parem $biaoshi 视图文件名--用于标识
     * */
    public function down_html($data,$price,$biaoshi)
    {
        try{
            if(!isset(self::$zip_class))
            {
                $this->write_log("当前php 环境不支持 zip类 压缩");
                exit(ERROR_CODE);
            }
            // 判定是否存在生成好的 缓存静态模板页面 --如果没有生成
            $html_cache=DATA_EXPORT_U.$biaoshi.self::$conf_data["HTML_EXT"];
           if(!is_file($_SERVER['DOCUMENT_ROOT'].$html_cache))
            {
                 $Tem=Com::getInstance("Tem");
                 $Tem->write_html($price,$data,true,null,$_SERVER['DOCUMENT_ROOT'].DATA_CACHE,$biaoshi);
                 $html_cache2=$_SERVER['DOCUMENT_ROOT'].DATA_CACHE.$biaoshi.self::$conf_data["HTML_EXT"];
                // 判定是否生成成功
                if(!is_file($html_cache2))
                {
                    $this->write_log("生成网页静态页面失败");
                    exit(ERROR_CODE);
                }
            }
            $f_n2=date("Y-m-d-H-i-s",time()).self::$conf_data["HTML_EXT"];
            $filename =$this->file_name(DATA_EXPORT_U,".zip");

            $zip = self::$zip_class;
            $zip->open($filename,ZipArchive::CREATE);   //打开压缩包
            $zip->addFile($html_cache2,$f_n2);   //向压缩包中添加文件//第二个参数是放在压缩包中的文件名称
            $zip->close();  //关闭压缩包
            if(isset($filename))
            {
                $f_name=str_replace($_SERVER['DOCUMENT_ROOT'],"",$filename);
                echo $f_name;
            }else
            {
              $this->write_log("生成压缩文件失败");
              exit(ERROR_CODE);
            }
        }catch (Exception $e)
        {
            $info=$e->getCode().":".$e->getFile().$e->getLine()."\r\n".$e->getMessage()."\r\n".$e->getTrace();
            $this->write_log($info);
            exit(ERROR_CODE);
        }
    }

    /**下载 用户导出 excel 表格  用于ajax
    * @parem $data 要写入的数据
     * @return 成功返回 写入的文件名 失败返回错误码
    */
     public  function down_excel($data,$biaoshi)
    {
        try{
            $Excel=Com::getInstance("Excel");
            //从组数组 ---标题与 字段对应  price.class  35 $all_data["tit"]字段
            //$price_data_field=$this->price_data_field();
            $price_key=array_keys($this->price_data_field());
             if(in_array($biaoshi,$price_key))
             {
                 foreach($data["con"] as $k=>$v)
                 {
                     $new[$k][]=$v["n"];
                     $new[$k][]=$v[$data["price"]];
                     $new[$k][]=$v["t_n"];
                     $new[$k][]=$v["t"];
                     $new[$k][]=$v["bz"];
                 }
             }else
             {
                 foreach($data["con"] as $k=>$v)
                 {
                    $new[$k][]=$v["n"];
                    foreach($price_key as $p_k=>$price_v)
                    {
                        $new[$k][]=$v[$price_v];
                    }
                     $new[$k][]=$v["t_n"];
                     $new[$k][]=$v["t"];
                     $new[$k][]=$v["bz"];
                 }
             }
            $data["con"]=$new;
            $f_name=$Excel->writer_excel($data,DATA_EXPORT_U);
            $f_name=str_replace($_SERVER['DOCUMENT_ROOT'],"",$f_name);
            echo $f_name;
        }catch (Exception $e)
        {
            echo  ERROR_CODE;
            $info=$e->getCode().":".$e->getFile().$e->getLine()."\r\n".$e->getMessage()."\r\n".$e->getTrace();
            $this->write_log($info,true);
        }
    }
} 