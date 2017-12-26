<?php
/**
 * Created by PhpStorm.
 * User: CWPC001
 * Date: 2017/12/26
 * Time: 下午 17:56
 */
namespace app\index\validate;
use think\Validate;
class AuthValidate extends Validate
{
//    验证规则
    protected $rule = [
        'title' => 'require',
        'pid'   => 'require|number',
        'status'=> 'require|number',
        'menu'  => 'require|in:0,1'
    ];
    // 使用场景
    protected  $scene = [
        'add'   => ['title','pid','status','menu']
    ];

}