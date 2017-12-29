<?php

namespace app\index\controller;

use app\index\model\AuthGroup;
use think\Controller;
use think\Request;
use app\index\Model\Framework as FrameworkModel;
use my\RecursionType;

class UserManage extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = [
            'msg'   =>"",
            'code'  =>0,
            'data'  => [
                'id' =>1,
                'username'  => 'xbc',
                'sex'   => 'nang',
                "city"=>"城市-0",
                "sign"=>"签名-0",
                "experience"=>255,
                "logins"=>24,
                "wealth"=>82830700,
                "classify"=>"作家",
                "score"=>57
            ],
            'count' => 1
        ];
        if(request()->isAjax())
            return json($data);
        return view('',[
            'fram_data' => []
        ]);
    }

    public function lists()
    {
        $data = [
            'msg'   =>"",
            'code'  =>0,
            'data'  => [
                [
                'id' =>1,
                'username'  => 'xbc',
                'sex'   => 'nang',
                "city"=>"城市-0",
                "sign"=>"签名-0",
                "experience"=>255,
                "logins"=>24,
                "wealth"=>82830700,
                "classify"=>"作家",
                "score"=>57
            ],
                [
                    'id' =>2,
                    'username'  => 'xbc',
                    'sex'   => 'nang',
                    "city"=>"城市-0",
                    "sign"=>"签名-0",
                    "experience"=>255,
                    "logins"=>24,
                    "wealth"=>82830700,
                    "classify"=>"作家",
                    "score"=>57
                ]
    ],
            'count' => 1
        ];
        return json($data);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        // 查找组织架构
        $data = FrameworkModel::all();
        $data = RecursionType::getFrameworks($data);
        $group = AuthGroup::all();
        return view('',[
            'data'  => $data,
            'group' => $group
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
        $data = [
            'log_name'      => $request->post('log_name',null,'trim|htmlspecialchars'),
            'log_password'  => $request->post('log_password',null,'trim'),
            'state'         => 1,
            'email'         => $request->post('email',null,'trim'),
            'phone'         => $request->post('phone',null,'trim'),
            'positon'       => $request->post('positon',null),
        ];
        $validate = validate('UsersValidate');
        if(!$validate->scene('add')->check($data))
            return myJson(-1,$validate->getError());

        return myJson();
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
