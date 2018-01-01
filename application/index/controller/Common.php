<?php
/**
 * Created by PhpStorm.
 * User: XBC
 * Date: 2017/12/31
 * Time: 14:05
 */

namespace app\index\controller;


use think\Controller;
use think\Session;

class Common extends Controller
{
    protected $userInfo = [];
    protected function _initialize()
    {
        parent::_initialize();
        $user = Session::get("userInfo");
        if(empty($user))
            $this->redirect("index/Login/index");
        $this->userInfo = $user;
    }
}