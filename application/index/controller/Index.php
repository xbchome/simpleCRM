<?php
namespace app\index\controller;

use app\index\model\AuthRule;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $menu = AuthRule::where('menu','=',1)->select();
        return  view('',[
            'menus' => $menu
        ]);
    }
}
