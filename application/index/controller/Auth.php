<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Auth extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return view('');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $model = input('post.module',null,'htmlspecialchars,lcfirst');
        $action = input('post.ation',null,'htmlspecialchars,lcfirst');
        $controller = input('post.controller',null,'htmlspecialchars,ucfirst');
        $name = $model.'/'.$controller.''.$action;
       if($model===null || $action=== null || $controller === null)
           return myJson(-1,$name);

        $data = [
            'title'  => input('post.title',null,'htmlspecialchars'),
            'name'   => $name,
            'pid'   => input('post.pid',null),
            'status'    => 1,
            'menu'      => input('post.menu',null),
        ];
       $validate =  validate('AuthValidate');
        if(!$validate->scene('add')->check($data))
            return myJson(-1,$validate->getError());

        return json($data);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
