<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-20
 * Time: 上午11:22
 * 前台用户查看价格表
 */
if(!defined('IN_SYS')) {
    header("Location:/404.html");
    exit();  // 跳转404
}
class Price extends  Pro {

    public function  __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // 取得用户身份标识
        $g_u=explode(DE_LIMITER,$_SESSION["id"]);
        $gid_token=$this->price_she_biaoshi($g_u[0],$_SESSION["token"],PHP_CON."Login.php");
        // 取得数据
        $all_data=$this->price_data($gid_token);
        $list=$all_data["con"];

        if(isset($_GET["t_id"]) && (intval($_GET["t_id"])+1>=1))
        {
            $new_g=array();
            $g_id=intval($_GET["t_id"]);
            foreach($all_data["con"] as $k=>$v)
            {
                if($g_id==$v["t_id"])
                    array_push($new_g,$v);
            }
            $list=$new_g;
        }//------------------------------------------分组查看

        if(!empty($list))
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
            $total=count($list);                // 数据开始位置    数组截取的结束位置
            $all_data["con"]=array_slice($list,($curpage-1)*$showrow,$showrow);
            rsort($all_data["con"]); // 文件排序
        }
        $requer_file=strtolower(__CLASS__);
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    /**用户访问价格表数据
     * @parem  $biaoshi 身份标识 判断访问什么价格
     * @return  返回项目信息数据
     * */
    public  function  price_data($biaoshi)
    {
        $price_data_field=$this->price_data_field();
        $price_data_key=array_keys($price_data_field);

        $data_f=$_SERVER['DOCUMENT_ROOT'].DATA_CACHE.DATA_CACHE_USER.self::$conf_data["DATA_EXT"];
        if(!is_file($data_f))  // 判定缓存数据文件是否存在
        {
            $Data=Com::getInstance("Data");
            $all_data["con"]=$Data->wrtie_front();
        }else
        {
            $all_data["con"]=$this->read_data(DATA_CACHE_USER,DATA_CACHE);
        }
        // 移除不需要用户看到的价格
        $remove_field=array();
        $biaoshi2[0]=$biaoshi;
        $remove_field=array_diff($price_data_key,$biaoshi2);

        $all_data["tit"]=array("项目名称","项目价格","项目类型","上架时间","备注信息");

        if(count($remove_field)== count($price_data_key))  //访问的用户为 admin--显示所有价格
        {
            $all_data["web_name"]="项目价格表";
            // 按顺序插入 价格字段--价格字段值
            $i=1;
            foreach($price_data_field as $k=>$v)
            {
                $i++;
               array_splice($all_data["tit"],$i,0,$v);
            }
            //价格组 个数---管理员显示所有组的价格组
            array_splice($all_data["tit"],1,1);
            $all_data["price"]=null;
        }else                           // 其他组员
        {
            foreach($all_data["con"] as $k=>$v)
            {
                foreach($remove_field as $vv)
                {
                    unset($v[$vv]);
                }
                $all_data["con"][$k]=$v;
            }
            $all_data["web_name"]=$price_data_field[$biaoshi]."表";
            $all_data["price"]=$biaoshi;
        }

        $all_data["key"]=$price_data_key;
        return $all_data;
    }

} 