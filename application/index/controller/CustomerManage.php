<?php

namespace app\index\controller;

use app\index\model\Customers;
use app\index\model\Follow;
use app\index\model\Scheme;
use app\index\model\Users;
use app\index\model\Visits;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class CustomerManage extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if($request->isAjax())
        {
            $page = input('get.page',1);
            $limit = input('get.limit',10);
            $uid = input('get.uid',0);
            $map = $uid==0?[]:['c.uid'=>$uid];
            $data =  (new Customers)->getCustomersList($page,$limit,$map,$this->userInfo['id']);
            return json($data);
        }
           $users = (new Users)->getFrameworkUser($this->userInfo['position'],$this->userInfo['id']);
        return view('',[
            'users' =>$users
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return view();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //return myJson();
       $data = [
           'cm_name'        => $request->post('cm_name',null,'htmlspecialchars'),
           'cm_phone'       => $request->post('phone'),
           'cm_sex'         => $request->post('sex'),
           'cm_type'        => $request->post('type'),
           'company'        => $request->post('company',null,'htmlspecialchars'),
           'industry'       => $request->post('industry',null,'htmlspecialchars'),
           'describes'      => $request->post('describes',null,'htmlspecialchars'),
           'demand'         => $request->post('demand',null,'htmlspecialchars')
       ];
        $validate = validate('CustomersValidate');
        if(!$validate->check($data))
            return myJson(-1,$validate->getError(),['ss']);
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['uid']    = $this->userInfo['id'];
        $schemes = $_POST['scheme'];  // 方案
//        dump($data);die;
        $visits['visits_time'] = $request->post('visits'); // 上门时间
        $follow['contents'] = $request->post('follow'); // 跟进情况
        $follow['create_time'] = time();
        Db::startTrans();
        try {
            $c = Customers::create($data); // 创建客户
            $dataScheme=[];
            foreach($schemes as $v)
            {
                if(!empty($v))
                    $dataScheme[] = ['scheme'=>$v,'cid'=>$c->id];
            }
            if(empty($dataScheme))
                $dataScheme[] = ['scheme'=>'','cid'=> $c->id];
           // dump($dataScheme);
            (new Scheme)->saveAll($dataScheme);  // 添加方案
            $visits['cid']  = $c->id;
            $follow['cid'] = $c->id;
            Visits::create($visits);
            Follow::create($follow);
            Db::commit();
            return myJson();
        } catch (Exception $exception) {
            Db::rollback();
            myJson(-1,$exception->getMessage());
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
        $customer = new Customers();
        $data = $customer->relation('follows,schemes,visitss')->where('id','=',$id)->find();
        if(empty($data))
            die("你要编辑的客户不存在或是已经删除");
        return view('',[
            'data'  => $data
        ]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $customer = new Customers();
        $data = $customer->relation('follows,schemes,visitss')->where('id','=',$id)->find();
        if(empty($data))
            die("你要编辑的客户不存在或是已经删除");
        return view('',[
            'data'  => $data
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
        //
        $data = [
            'cm_name'        => $request->post('cm_name',null,'htmlspecialchars'),
            'cm_phone'       => $request->post('phone'),
            'cm_sex'         => $request->post('sex'),
            'cm_type'        => $request->post('type'),
            'company'        => $request->post('company',null,'htmlspecialchars'),
            'industry'       => $request->post('industry',null,'htmlspecialchars'),
            'describes'      => $request->post('describes',null,'htmlspecialchars'),
            'demand'         => $request->post('demand',null,'htmlspecialchars')
        ];
        $validate = validate('CustomersValidate');
        if(!$validate->check($data))
            return myJson(-1,$validate->getError(),['ss']);
        $data['update_time'] = time();


        try {
            $c = Customers::where('id','=',$id)->update($data); // 创建客户

            return myJson();
        } catch (Exception $exception) {
            myJson(-1,$exception->getMessage());
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
        try {
            Customers::destroy($id);
            Follow::where('cid','=',$id)->delete();
            Scheme::where('cid','=',$id)->delete();
            Visits::where('cid','=',$id)->delete();
            return myJson();
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());
        }
    }

    /**
     * 客户转移
     */
    public function shift(Request $request,$uid)
    {
        $data = $request->post();
        $upData = [];
        foreach ($data['data'] as $v)
        {
            $upData[] = ['id'=>$v,'uid'=>$uid];
        }
        try {
                $c = new Customers();
                $c->isUpdate()->saveAll($upData);
                return myJson();
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());

        }
    }
}
