<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-14
 * Time: 下午4:00
 * 验证用户登录
 */

namespace app\back\validate;

use think\Validate;

class Vbackuser extends Validate {

    protected $rule = [
        'n' => 'require|regex:[\s\S]{2,10}',
        'pw' => 'require|regex:([\w\.\@\!\-\_]){6,10}',
    ];
    protected $message = [
        'n.require' => 'n_require',
        'n.regex' => 'n_regex',
        'pw.require' => 'pw_require',
        'pw.regex' => 'pw_regex',
    ];
    protected $scene = [
       'login' => ['n','pw'],
    ];



} 