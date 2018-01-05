<?php
namespace app\index\controller;

use app\index\model\AuthRule;

class Index extends Common
{
    public function index()
    {
       // $menu = AuthRule::where('menu','=',1)->select();
        $map['menu']= 1;
        $map['status'] =1;
        $menu = AuthRule::where($map)->field('name,title,image')->select();
        return  view('',[
            'menus' => $menu
        ]);

    }
}
