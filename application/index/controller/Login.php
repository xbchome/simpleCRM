<?php

namespace app\index\controller;

use app\index\model\Users;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $userInfo = Session::get('userInfo');
        if(!empty($userInfo))
            $this->redirect('index/Index/index');
        return view();
    }

    public function doLogin(Request $request)
    {
        $error  = Session::get('loginError');  // 先获取用户密码输入错误次数
        if(empty($error))    // 如果为空
            Session::set('loginError',0);  // 初始化为0
        $userName = $request->post("logname",null,"trim,htmlspecialchars");
        $password = $request->post("logpass",null,"trim,htmlspecialchars");
        if(empty($userName) && empty($password))
            return myJson(-1,'用户名和密码不能为空',['c'=>$error]);
        if($error>=4)    // 当密码输入错误超过4次或者以上的时候开启验证码验证
        {
            $code = $request->post('code');
            if(!captcha_check($code))
                return myJson(-2,'验证码有误');
        }
        $user = Db::name('users')->where('log_name','=',$userName)->find();
        if(empty($user))
        {
            Session::set('loginError',$error+1);
            return myJson(-1,'用户名或密码有误',[]);
        }

        if($user['log_password']!==md5($user['create_time'].$password))
        {
            Session::set('loginError',$error+1);
            return myJson(-1,'用户名或密码有误!');
        }
        unset($user['log_password']);
        Session::set("userInfo",$user);
        Session::delete('loginError');
        return myJson();


    }

    public function singOut()
    {

        Session::delete("userInfo");
        return myJson();
    }














}
