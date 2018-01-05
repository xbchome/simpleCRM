<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\index\model\AuthRule;
function myJson($code =2000,$msg='success',$data=[])
{
    $data = [
        'code'=> $code,
        'msg'=> $msg,
        'data'=> $data,
    ];
    return json($data);
}


function is_menu($munes,$name)
{
    if(in_array($name,$munes))
        return true;

}