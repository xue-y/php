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
use Think\Mobile;

class TeController extends Controller {

    public function index()
    {
        header("Content-Type:text/html;charset=utf-8");
        $history=CONTROLLER_NAME.FEN_FU.ACTION_NAME;


        try{
            $user=D('ser'); // 语法错误无法捕获

            echo 444;
        }catch(\Exception $e){die($e->getMessage());}

    }
} 