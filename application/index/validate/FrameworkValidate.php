<?php
/**
 * Created by PhpStorm.
 * User: CWPC001
 * Date: 2017/12/28
 * Time: 上午 11:51
 */

namespace app\index\validate;


use think\Validate;

class FrameworkValidate extends Validate
{
    protected $rule = [
        'title'  => 'require|token'
    ];

    protected $scene = [
        'add'   => ['title'],
    ];
}