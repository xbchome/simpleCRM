<?php
namespace app\index\controller;

use app\index\model\AuthRule;

class Index extends Common
{
    public function index()
    {
        $menu = AuthRule::where('menu','=',1)->select();
        return  view('',[
            'menus' => $menu
        ]);
    }
}
