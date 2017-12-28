<?php
/**
 * Created by PhpStorm.
 * User: CWPC001
 * Date: 2017/12/27
 * Time: 上午 11:02
 */

namespace app\index\validate;


use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'title'     => 'require|token',
        'status'    => 'require|in:0,1'
    ];

    protected $scene = [
        'allot' => ['title'],
    ];
}