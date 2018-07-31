<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-20
 * Time: 下午1:43
 * 自定义 curl 函数
 */

namespace Think;


class Curl {

    // post 请求
    public function https_post($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function https_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /** curl 请求url 返回数据
     * @parem  $url 请求url
     * @parem  $data 请求参数数据 post 用到
     * @parem  $cookie 设置cookie
     * @return 返回请求的数据
     * */
    public function curl_url($url,$data=null,$cookie=null)
    {
        // 初始化 curl
        $curl = curl_init();

        // 设置URL和相应的选项 curl_setopt — 设置一个cURL传输选项。
        //1.由 curl_init() 返回的 cURL 句柄; 2.需要设置的CURLOPT_XXX选项;3.将设置在option选项上的值
        curl_setopt($curl, CURLOPT_URL, $url); // url
        curl_setopt($curl, CURLOPT_HEADER, 0); // 将头文件的信息作为数据流输出； 1 为输出 ；0 不输出

        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出：
        //1 或者 true为不输出，0 或false  直接输出到页面上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

        // https请求 不验证证书和hosts 请求数据时使用，跳过验证； 例如参数 返回数据
        //CURLOPT_SSL_VERIFYPEER 禁用后cURL将终止从服务端进行验证  默认为true
        //如果CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        // 如果传送数据---- 使用post 方式
        if(isset($data) && !empty($data))
        {
            //设置post方式提交 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
            //表示是否启用第二个option，这里为CURLOPT_POST，设置为1，表示启用时会发送一个常规的POST请求
            curl_setopt($curl, CURLOPT_POST, 1);

            /*这个参数可以通过urlencoded后的字符串类似'para1=val1&para2=val2&...'或使用一个以字段名为键值，
            字段数据为值的数组。如果value是一个数组，Content-Type头将会被设置成multipart/form-data。
             * */
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        // 如果传递 HTTP请求中 的 Cookie
        if(isset($cookie) && !empty($cookie))
        {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }

        // 抓取URL并把它传递给浏览器 执行curl 返回页面数据
        $outdata = curl_exec($curl);
        // 关闭curl 连接
        curl_close($curl);
        return $outdata;
    }

    /** 下载远程图片到指定路径
     * @parem $url 请求的图片地址
     * @parem $filename 保存本地的文件名
     * @parem $save_dir 保存本地路径
     * @parem $size 文件最大大小 2MB  暂时用不到这个参数,$size=2097152
     * @parem $timeout 请求超时限制
     * @return array
     * */
    public function get_img($url,$filename=null,$save_dir=null,$timeout=2)
    {
        $error=array(); //微信不支持 $error=[] 这种方式定义数组
        if(empty($url))
        {
            $error["code"]=1;
            $error["info"]="img url open fail";
            return $error;
        }
        if(!isset($save_dir) || empty($save_dir))
        {
            $save_dir=$_SERVER["DOCUMENT_ROOT"]."/Public/headimg/";
        }
        //创建保存目录
        if(!is_dir($save_dir) && !mkdir($save_dir,0777))
        {
            $error["code"]=2;
            $error["info"]="mkidr $save_dir fail";
            return $error;
        }
        //获取远程文件所采用的方法
        /* CURLOPT_CONNECTTIMEOUT用来告诉PHP脚本在成功连接服务器前等待多久（连接成功之后就会开始缓冲输出），这个参数是为了应对目标服务器的过载，下线，或者崩溃等可能状况；
 CURLOPT_TIMEOUT 用来告诉成功PHP脚本，从服务器接收缓冲完成前需要等待多长时间。如果目标是个巨大的文件，生成内容速度过慢或者链路速度过慢，这个参数就会很有用。
 使用cURL下载MP3文件是一个对开发人员来说不错的例子。CURLOPT_CONNECTTIMEOUT 可以设置为10秒，标识如果服务器10秒内没有响应，脚本就会断开连接；CURLOPT_TIMEOUT可以设置为100，如果MP3文件100秒内没有下载完成，脚本将会断开连接。*/
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //1 为不输出在页面上
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
        $img_resource=curl_exec($ch);
        curl_close($ch);

        if(!isset($filename) || empty($filename))
        {
            $filename=time().".jpg";    // 默认文件后缀jpg
        }
        $filename=$save_dir.$filename;// 必须绝对目录写入，否则失败

        file_put_contents($filename,$img_resource);
        if(!is_file($filename))
        {
            $error["code"]=3;
            $error["info"]="write headimg fail";
            return $error;
        }
        $error["code"]=0;
        return $error;
    }



} 