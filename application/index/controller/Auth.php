<?php

namespace app\index\controller;

use app\index\model\AuthRule;
use app\index\validate\AuthGroup;

use my\RecursionType;
use think\Controller;
use think\Exception;
use think\Request;
use app\index\model\AuthGroup as AuthGroupModel;

class Auth extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = AuthGroupModel::all();
        return view('',[
            'authGroup' => $data
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
//        $data = AuthRule::all();
       $data = db('auth_rule')->select();
       // $s = (new \my\RecursionType())->getArray($data);
        $data = RecursionType::getNbsp($data);
        //dump($s);

        return view('',[
            'authRule'  => $data
        ]);
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
        $action = input('post.action',null,'htmlspecialchars,lcfirst');
        $controller = input('post.controller',null,'htmlspecialchars,ucfirst');
        $name = $model.'/'.$controller.'/'.$action;
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
        // 开始上传图片
        $file = request()->file('image');
        if($file)
        {
           $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
           if($info)
           {
              $imageName = $info->getSaveName();
               $data['image'] = str_replace('\\','/',$imageName);
           }else{
            return myJson(-1,$file->getError());
           }
        }

        try{
            AuthRule::create($data);
            return myJson();
        }catch (Exception $exception)
        {
            return myJson(-1,$exception->getMessage());
        }
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

    // 增加用户组
    public function addUserGroup()
    {

    }
}
