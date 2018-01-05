<?php
/**
 * Created by PhpStorm.
 * User: XBC
 * Date: 2017/12/31
 * Time: 14:05
 */

namespace app\index\controller;


use app\index\model\AuthRule;
use think\Controller;
use think\Request;
use think\Session;
use my\Auth;
class Common extends Controller
{
    protected $userInfo = [];  // 当前用户信息
    protected $userMenu = []; // 当前用户菜单
    protected function _initialize()
    {
        parent::_initialize();
        $user = Session::get("userInfo");
        if(empty($user))
            $this->redirect("index/Login/index");
        $this->userInfo = $user;
       $auth =  new Auth();
       $request = Request::instance();
       $u = $request->module().'/'.$request->controller().'/'.$request->action();

       if($auth->check($u,$user['id']) || $u=='index/Index/index')
       {
        $this->assign('glp_menu',$auth->menu);
       }else
       {
           $msg = '你没有权限访问';
           if($request->isAjax())
               $msg = json_encode(['code'=>-1,'msg'=>'你没有权限访问']);
           echo $msg;
           die();
       }
    }
}