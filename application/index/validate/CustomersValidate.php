<?php
/**
 * Created by PhpStorm.
 * User: CWPC001
 * Date: 2018/01/03
 * Time: 下午 14:36
 */

namespace app\index\validate;


use think\Validate;

class CustomersValidate extends Validate
{
    protected $regex = [
        'phone' => '/0?(13|14|15|17|18|19)[0-9]{9}/',
    ];
    protected $rule = [
        'cm_name|客户姓名'   => 'require',
        'cm_phone|客户手机'  => 'require|regex:phone'
    ];
}