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
function myJson($code =2000,$msg='success',$data=[])
{
    $data = [
        'code'=> $code,
        'msg'=> $msg,
        'data'=> $data,
    ];
    return json($data);
}




function setProduct($product)
{
    $data = [];
    foreach ($product as $key=>$value)
    {
        if($value['pid']!=0)
            continue;

        $data[$key] = $value;
        $data[$key]['son'] = [];
        unset($product[$key]);
        foreach($product as $k=>$v)
        {
            if($value['id']== $v['pid'])
            {
                $data[$key]['son'][] = $v;
                unset($product[$k]);
            }
        }
    }
    return $data;
}