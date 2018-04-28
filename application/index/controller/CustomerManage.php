<?php

namespace app\index\controller;

use app\index\model\Customers;
use app\index\model\Follow;
use app\index\model\Product;
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
            $user = Users::get($this->userInfo['id']);
            $mapor =[];
            if($user['is_captain'])
                $mapor['u.position'] = ['=',$user['position']];
            $data =  (new Customers)->getCustomersList($page,$limit,$map,$this->userInfo['id'],$mapor);
            return json($data);
        }
        $user = Users::get($this->userInfo['id']);
        $mapor=[];
        if($user['is_captain'])
            $mapor['u.position'] = ['=',$user['position']];

           $users = (new Users)->getFrameworkUser($this->userInfo['position'],$this->userInfo['id'],$mapor);
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
        $product = Product::all();
        if($product) {
            $product = collection($product)->toArray();
        }
        $data = setProduct($product);

        return view('',[
            'data' =>$data
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
        //return myJson();
       $data = [
           'cm_name'        => $request->post('cm_name',null,'htmlspecialchars'),
           'cm_phone'       => $request->post('phone'),  // 手机
           'cm_phone2'       => $request->post('phone2',''),  // 手机
           'cm_phone3'       => $request->post('phone3',''),  // 手机
           'cm_sex'         => $request->post('sex'), // 性别
           'cm_type'        => $request->post('type'), // 类型
           'company'        => $request->post('company',null,'htmlspecialchars'),  // 公司
           'industry'       => $request->post('industry',null,'htmlspecialchars'),  // 行业
           'describes'      => $request->post('describes',null,'htmlspecialchars'), // 描述
           'demand'         => $request->post('demand',null,'htmlspecialchars')  // 需求
       ];

        $schemes = empty($_POST['fananser'])?[]:$_POST['fananser']; // 接收方案
        $data['schemes'] = json_encode($schemes,JSON_UNESCAPED_UNICODE);  // 将方案转成json
        $validate = validate('CustomersValidate');
        if(!$validate->check($data))
            return myJson(-1,$validate->getError(),['ss']);
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['uid']    = $this->userInfo['id'];
//        $visits['visits_time'] = $request->post('visits'); // 上门时间
        $follow['contents'] = $request->post('follow'); // 跟进情况
        $follow['create_time'] = time();
        Db::startTrans();
        try {
            $c = Customers::create($data); // 创建客户
            $follow['cid'] = $c->id;  // 添加方案
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
        $uid = $this->userInfo['id'];
        $is_admin = Users::get($uid);
        $customer = new Customers();
        $data = $customer->relation('follows,visitss')->where('id','=',$id)->find();
        if(empty($data))
            die("你要查看的客户不存在或是已经删除");
        if($data['uid'] != $uid && $is_admin->is_super!=10)
        {
            $data['cm_phone3'] ='*******';
            $data['cm_phone2'] ='*******';
            $data['cm_phone'] ='*******';
        }
        $schemes = $data['schemes'];
        $schemes = empty($schemes)?[]: json_decode($schemes);
        return view('',[
            'data'  => $data,
            'schemes'   => $schemes
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
        $uid = $this->userInfo['id'];
        $is_admin = Users::get($uid);
        $customer = new Customers();
        $data = $customer->relation('follows,visitss')->where('id','=',$id)->find();
        if(empty($data))
            die("你要编辑的客户不存在或是已经删除");
        $product = Product::all();  // 查所有的解决方案
        if($product) {
            $product = collection($product)->toArray();
        }
        $product = setProduct($product);  // 处理客户方案的数据
        $schemes = $data['schemes'];
        $is_update = 1;
        if($data['uid'] != $uid && $is_admin->is_super!=10)
        {
            $data['cm_phone3'] ='*******';
            $data['cm_phone2'] ='*******';
            $data['cm_phone'] ='*******';
            $is_update = -1;
        }
        $schemes = empty($schemes)?[]: json_decode($schemes);
        $schemeType = [];
        foreach($schemes as $key=>$v)
        {
            $schemeType[$key] = "disabled";
        }

        return view('',[
            'data'  => $data,
            'schemes'   => $schemes,
            'schemeType' =>$schemeType,
            'product'   => $product,
            'is_update' => $is_update
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
            'cm_phone2'       => $request->post('phone2',''),  // 手机
            'cm_phone3'       => $request->post('phone3',''),  // 手机
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
        $schemes = empty($_POST['fananser'])?[]:$_POST['fananser']; // 接收方案
        $data['schemes'] = json_encode($schemes,JSON_UNESCAPED_UNICODE);  // 将方案转成json

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
