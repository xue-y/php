<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-18
 * Time: 下午8:04
 * 验证添加 修改角色
 */

namespace app\back\validate;
use think\Validate;

class Vbackrole extends  Validate {

    protected $rule =   [
        'r_n'  => 'require|regex:[\s\S]{2,30}', // 字符长度是中文1个==3个字符长度
        'r_de'   => 'length:5,60',
    ];

    protected $message  =   [
        'r_n.require' => 'r_n_require',
        'r_n.regex'     => 'r_n_regex',
        'r_de.length'   => 'r_de_length',
    ];
    protected $scene = [
        'create' => ['r_n','r_de'],
    ];
} 