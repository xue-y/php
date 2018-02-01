<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-12
 * Time: 下午2:15
 */

class Vaildata extends Com {
      public function name($val)
      {
          $preg= "/^[\s\S]{2,10}$/ui";
          return $this->vail($preg,$val,"字符长度不得小于2大于10<br/>操作");
      }
      public function pass($val)
      {
          $preg= "/^[\w\.\@\!\-\_]{6,10}$/";
          return $this->vail($preg,$val,"密码长度6到10位之间字符数组英文 .\@\!\-\_ <br/>添加用户");
      }

      //
    /**验证密码是否一次
     * @parem $pass 第一次的密码
     * @parem $repass 第二次密码
     * @return 验证正确返回 true 错误直接跳转
     * */
      public function repass($pass,$repass)
      {
            if($pass === $repass)
            {
                return true;
            }else
            {
                $this->skip("error",null,"二次密码不一致添加用户");
            }
      }

    /** 验证数组是否为空并去重
     * @parem $arr 需要验证的数组
     * @parem $info 提示信息
     * @return 返回验证后的数组
     * */
    public  function veri_arr($arr,$info)
    {
        if(!isset($arr) || empty($arr))
        {
            $this->skip("error",null,$info."数据不得为空 操作");
        }
        $arr=array_filter(array_unique($arr));
        if(empty($arr))
        {
            $this->skip("error",null,$info."数据为空或重复 操作");
        }
        else
        {
            return $arr;
        }
    }

    /** 验证字符长度
     * @parem $str 需要验证的字符串
     * @parem $len_arr 字符长度限制例如 2,3
     * @parem return  字符串
     * */
     public  function veri_str_len($str,$len_arr)
    {
        preg_match_all("/./us", $str, $matches);
        $str_len=count(current($matches));
        $len_arr=explode(",",$len_arr);
        if($str_len<$len_arr[0] || $str_len>$len_arr[1])
        {
           $this->skip("error",null," 字符长度不得小于".$len_arr[0]."大于".$len_arr[1]);
        }
        return $str;
    }


    /** 正则验证表单数值
     * @parem $regular 正则
     * @parem $val 需要验证的值
     * @parem $info 错误提示信息
     * @parem $is_url  是否跳转返回上一页 默认为true  false直接结束
     * @return 验证正确返回 验证后的值
     * */
     private   function vail($regular,$val,$info,$is_url=true)
     {
         preg_match($regular,$val,$new_val);
         if(empty($new_val))
         {
             if(isset($is_url))
             {
                $this->skip("error",null,$info);
             }else
             {
                 exit($info);
             }
         }else
         {
             return $new_val;
         }
     }

    /** 验证时间有效性
     * @parem $timer  格式 2001-03-04
     * */
    public function check_time($timer)
    {
        $n_t=explode("-",$timer);
        return checkdate($n_t[1],$n_t[2],$n_t[0]);
    }
} 