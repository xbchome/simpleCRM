<?php

namespace app\index\controller;

use app\index\model\AuthGroup;
use app\index\model\AuthRule;
use my\RecursionType;
use think\Controller;
use think\Exception;
use think\Log;
use think\Request;

class UserGroup extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        //$this->fetch('user_group/create');
        return view('user_group/create');
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
            'title' =>  $request->post('title','','htmlspecialchars'),
            'status'    => $request->post('status'),
            'describes' => $request->post('describes'),
            '__token__' => $request->post('__token__'),
        ];

        $validate = validate('AuthGroup');
        if(!$validate->check($data))
            return myJson(-1,$validate->getError());
        unset($data['__token__']);
        try {
            AuthGroup::create($data);
            return myJson();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
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
        $userGroup = AuthGroup::get($id);
        return view('',[
            'userGroup' => $userGroup
        ]);
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
        if(!is_numeric($id))
            return myJson(-1,'数据不规范');
        $data = [
            'title' =>  $request->post('title','','htmlspecialchars'),
            'status'    => $request->post('status'),
            'describes' => $request->post('describes'),
            '__token__' => $request->post('__token__'),
        ];
        $validate = validate('AuthGroup');
        if(!$validate->check($data))
            return myJson(-1,$validate->getError());
        unset($data['__token__']);
        try {
            AuthGroup::where('id','=',$id)->update($data);
            return myJson();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return myJson(-1,$exception->getMessage());
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(!is_numeric($id))
            return myJson(-1,'数据有误');
        if($id==1)
            return myJson(-1,'请不要删除管理员！');
        try {
            AuthGroup::destroy($id);
            return myJson();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return myJson(-1,'删除失败');
        }

    }

    /**
     * 权限分配
     */
    public function allotAuthShow($id)
    {
        $dataGroup = AuthGroup::get($id);
        $rule = AuthRule::all();
        $rele = (new RecursionType)->getArray($rule);
        return view('',[
            'groupInfo' => $dataGroup,
            'reules'    => $rele,
            'userRules'    => explode(',',$dataGroup['rules']),
        ]);
    }

    /**
     * 权限分配提交
     */

    public function allotAuthDo(Request $request,$id)
    {
        if(!is_numeric($id))
            return myJson(-1,'数据不完整请刷新');
        if($id===1)
            return myJson(-1,'管理员拥有所有权限');
        $rules = $request->post();

        $rules = empty($rules['reules'])?'' : implode(',',$rules['reules']);
       // dump($rules);die;
        $data = [
            'title' =>  $request->post('title'),
            '__token__' => $request->post('__token__'),
        ];
        $validate = validate('AuthGroup');
        if(!$validate->scene('allot')->check($data))
            return myJson(-1,$validate->getError());
        try {
            AuthGroup::where('id','=',$id)->update(['rules'=>$rules]);
            return myJson();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return myJson(-1,'修改出错');
        }
    }
}
