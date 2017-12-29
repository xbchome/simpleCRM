<?php
/**
 * Created by PhpStorm.
 * User: CWPC001
 * Date: 2017/12/29
 * Time: 上午 11:52
 */

namespace app\index\validate;


use think\Validate;

class UsersValidate extends Validate
{
    protected $rule = [
        'log_name|用户名'      => 'require|unique:users',
        'user_name|用户姓名'   => 'require',
        'log_password|密码'    => 'require|min:6',
        'email'                => 'require|email|unique:users',
        'phone|手机号'         => 'require|regex:0?(13|14|15|17|18|19)[0-9]{9}|unique:users',
        'position'             => 'require'
    ];

    protected $scene = [
        'add'   => ['log_name','user_name','log_password','email','phone','position']
    ];
}