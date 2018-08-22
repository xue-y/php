<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-23
 * Time: 下午12:56
 * 测试使用
 */

namespace Home\Controller;
use Think\Controller;
use Think\Agent;
use Think\Crypt;

class PayController extends Controller {

    private  $appid;
    private  $mch_id; //  商户id
    private  $openid; // 客户openid
    private  $sign_type; //签名类型
    private  $trade_type; //交易类型

    public  function  __construct()
    {
        header("Content-Type:text/html;charset=utf-8");
        parent:: __construct();
        visit_num();// 限制用户频繁刷新页面
        $this->s_pix=C('COOKIE_PREFIX');

        // 定义常量


    }
    public function index()
    {
        if(isset($_POST) && !empty($_POST))
        {
            // 客户确认订单
            $post=add_slashes($_POST);
            // 根据商品id取得商品相关信息 同时服务器端加密处理，客户端展现页面上，用户查看后确认订单
            if(I('post.total_fee/f')<0)
            {
                $this->error("请输入金额");
            }else
            {
                $post["total_fee"]= I('post.total_fee/f');
            }

            // 加密查询的商品信息
            $crypt=new Crypt();

            list($msec, $sec) = explode(' ', microtime());
            $msec = number_format($msec,4);
            $msec=str_replace("0.","_",$msec);

            $order_id=date("Ymdhis",time()).$msec; // 生成唯一订单号
            S("out_trade_no",$order_id,ORDER_T);

            $post_crypt=http_build_query($post);
            $post_crypt=$crypt->encrypt($post_crypt,$order_id,ORDER_T);

            $this->assign(array("shop"=>$post,"shop_info"=>$post_crypt));
            $this->display("order");
        }else
        {
            // 商品展现
            $this->display("shop");
        }

    }

    public function Pay()
    {
        $order_id=S("out_trade_no"); // 商户订单号

        if(!isset($order_id) || empty($order_id))
        {
            $this->error("订单过期,请重新确认订单",'index');
        }

        $post=add_slashes($_POST);
        $crypt=new Crypt();
        $shop_info=$post["shop_info"];
        $post_data=$crypt->decrypt($post["shop_info"],$order_id);
        parse_str(urldecode($post_data),$post_data);

        // 判断是浏览器 pc  wx  mob  根据 post[agent] 这个值 调取不同的支付方式
        if($post["agent"]=="wx")
        {
            // 用户是在公众号里访问的页面
        }else if($post["agent"]=="mob")
        {
            // 用户手机端 浏览器访问的页面
        }else
        {
            // 用户pc 端访问的页面
        }

       echo $post["agent"];

    }



} 