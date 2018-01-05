<?php

namespace app\index\controller;

use my\RecursionType;
use think\Controller;
use think\Exception;
use think\Log;
use think\Request;
use app\index\model\Framework as FrameworkModel;

class Framework extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = FrameworkModel::all();
        $data = RecursionType::getFrameworks($data);
        return view('',[
            'fram_data'  => $data
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data = FrameworkModel::all();
        $data = RecursionType::getFrameworks($data);
        return view('',[
            'data' => $data
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
            'title' => $request->post('title',null,'htmlspecialchars'),
            'pid'   => $request->post('pid',0),
            'describes' => $request->post('describes','','htmlspecialchars'),
            '__token__' => $request->post('__token__')
        ];

        $validate = validate('FrameworkValidate');
        if(!$validate->scene('add')->check($data))
            return myJson(-1,$validate->getError());
        $data['create_time'] = time();
        if($data['pid']==0)
        {
            $data['paht'] = ',0,';
        }else
        {
            $pf = FrameworkModel::get($data['pid']);
            if(empty($pf))
                return myJson(-1,'上级可能被删除');
            $data['paht'] = $pf['paht'].$data['pid'].',';
        }

        unset($data['__token__']);
        try {
            FrameworkModel::create($data);
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
        if(!is_numeric($id))
            die('请返回刷新');
        $dataS = FrameworkModel::get($id);
        $data = FrameworkModel::all();

        $data = RecursionType::getFrameworks($data);
        foreach ($data as $key=>$value)
        {
            if($dataS['id']===$value['id'])
                unset($data[$key]);
        }
        return view('',[
            'data' => $data,
            'datas'  =>$dataS
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
            return myJson(-1,'请刷新后重试！');
        $data = [
            'title' => $request->post('title',null,'htmlspecialchars'),
            'pid'   => $request->post('pid',0),
            'describes' => $request->post('describes','','htmlspecialchars'),
            '__token__' => $request->post('__token__')
        ];

        $validate = validate('FrameworkValidate');
        if(!$validate->scene('add')->check($data))
            return myJson(-1,$validate->getError());
        if($data['pid']==0)
        {
            $data['paht'] = ',0,';
        }else
        {
            $pf = FrameworkModel::get($data['pid']);
            if(empty($pf))
                return myJson(-1,'上级可能被删除');
            $data['paht'] = $pf['paht'].$data['pid'].',';
        }
        unset($data['__token__']);
        try {
            FrameworkModel::where('id','=',$id)->update($data);
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
            return myJson(-1,'请刷新后重试！');
        $info = FrameworkModel::where('pid','=',$id)->count('id');
        if($info>=1)
            return myJson(-1,'此架构下还有子架构存在无法删除！');
        try {
            FrameworkModel::destroy($id);
            return myJson();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return myJson(-1,'请刷新后在试！');
        }
    }
}
