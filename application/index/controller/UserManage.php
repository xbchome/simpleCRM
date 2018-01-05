<?php

namespace app\index\controller;

use app\index\model\AuthGroup;
use app\index\model\AuthGroupAccess;
use app\index\model\Customers;
use app\index\model\Users;
//use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use app\index\Model\Framework as FrameworkModel;
use my\RecursionType;

class UserManage extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {


        if(request()->isAjax())
        {
            $page = input('get.page',1);
            $limit = input('get.limit',10);
            $count = Users::where('state','>=',1)->count();
            $data  = Users::where('state','>=',1)->page($page,$limit)->select();
            $sex = [1=>'男',2=>'女'];
            foreach($data as &$vo)
            {
                $vo['sex'] = $sex[$vo['sex']];
            }
            $datas = [
                'msg'   =>"",
                'code'  =>0,
                'count' => $count,
                'data'  =>$data
            ];
            return json($datas);
        }

        return view('',[
            'fram_data' => []
        ]);
    }

    public function lists()
    {

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
            'log_name'      => $request->post('log_name',null,'htmlspecialchars,trim'),
            'log_password'  => $request->post('log_password',null,'trim'),
            'user_name'     => $request->param('user_name',null,'htmlspecialchars,trim'),
            'state'         => 1,
            'email'         => $request->post('email',null,'trim'),
            'phone'         => $request->post('phone',null,'trim'),
            'position'       => $request->post('position',null),
            'sex'       => $request->post('sex',1),
            '__token__'     => $request->post('__token__',null),
        ];
        $validate = validate('UsersValidate');
        if(!$validate->scene('add')->check($data))
            return myJson(-1,$validate->getError());
        $framework = FrameworkModel::get($data['position']);
        if(empty($framework))
            return myJson(-1,'部门不存在');
        unset($data['__token__']); // 删除__token__
        $data['position_cn']  = $framework['title'];
        $data['create_time']  = time();
        $data['update_time']  = time();
        $data['log_password'] = md5($data['create_time'].$data['log_password']); // 将用户的创建时间和明文密码加密作为保存密码
        Db::startTrans(); // 开启事务
        try {
            $user = Users::create($data);
            $uid = $user->id;
            $group = $request->post()['group'];
            $groups = [];
            foreach ($group as $vo)
            {
                $groups[]  =['uid'=>$uid,'group_id'=>$vo];
            }
            (new AuthGroupAccess)->saveAll($groups);
            Db::commit();
            return myJson();
        } catch (Exception $exception) {
            Db::rollback();
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
        $users = Users::get($id);
        if(empty($users))
            die("用户不存在");
        if($users['is_super']===10 && $this->userInfo['is_super']!==10)
            die("你无法查看超级管理员信息");
        $uGroup = AuthGroupAccess::where('uid','=',$id)->select();
        $wherein = [];
        foreach($uGroup as $v)
        {
            $wherein[] =  $v['group_id'];
        }
        // 查找组织架构
        $data = FrameworkModel::where('id','=',$users['position'])->field('title')->find();
        $group = AuthGroup::where('id','in',$wherein)->field('title')->select();  // 查找所有的

        return view('',[
            'data'  => $data,
            'group' => $group,
            'users' => $users,
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

        $users = Users::get($id);
        if(empty($users))
            die("用户不存在");
        if($users['is_super']===10 && $this->userInfo['is_super']!==10)
            die("你无法修改超级管理员信息");
        $uGroup = AuthGroupAccess::where('uid','=',$id)->select();
        foreach($uGroup as $v)
        {
           $groupChecked[$v['group_id']] = 'checked';
        }
        // 查找组织架构
        $data = FrameworkModel::all();
        $data = RecursionType::getFrameworks($data);  // 递归组织框架
        $group = AuthGroup::all();  // 查找所有的

        return view('',[
            'data'  => $data,
            'group' => $group,
            'users' => $users,
            'groupChecked' =>$groupChecked,
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
        $users = Users::get($id);
        if(empty($users))
            return myJson(-1,"你修改的用户已经删除或不存在！");
        if($users['is_super']===10 && $this->userInfo['is_super']!==10)
             return myJson(-1,"你无法修改超级管理员的信息！");

        $data = [
            'log_name'      => $request->post('log_name',null,'htmlspecialchars,trim'),
//            'log_password'  => $request->post('log_password',null,'trim'),
            'user_name'     => $request->param('user_name',null,'htmlspecialchars,trim'),
            'state'         => 1,
            'email'         => $request->post('email',null,'trim'),
            'phone'         => $request->post('phone',null,'trim'),
            'position'      => $request->post('position',null),   // 部门
            'sex'           => $request->post('sex',1),
            '__token__'     => $request->post('__token__',null),
        ];
        $rules = [
            'log_name|用户名'      => 'require|min:5',
            'user_name|用户姓名'   => 'require',
            'email'                => 'require|email',
            'phone|手机号'         => ["regex"=>"/0?(13|14|15|17|18|19)[0-9]{9}/"],
            'position|部门'         => 'require'
        ];
        $validate = validate();
        if(!$validate->rule($rules)->check($data))
            return myJson(-1,$validate->getError());
        if(!(new Users())->is_null('log_name',$id,$data['log_name']))
            return myJson(-1,'用户名已存在');
        if(!(new Users())->is_null('phone',$id,$data['phone']))
            return myJson(-1,'手机已存在');
        if(!(new Users())->is_null('email',$id,$data['email']))
            return myJson(-1,'邮箱已存在');
        $framework = FrameworkModel::get($data['position']);
        if(empty($framework))
            return myJson(-1,'部门不存在或者是已经被删除');
        unset($data['__token__']); // 删除__token__
        $data['position_cn']  = $framework['title'];
        $data['update_time']  = time();  // 修改时间

        Db::startTrans(); // 开启事务
        try {
            Users::where('id','=',$id)->update($data);
            $group = $request->post()['group'];
            $groups = [];
            foreach ($group as $vo)
            {
                $groups[]  =['uid'=>$id,'group_id'=>$vo];
            }
            AuthGroupAccess::where('uid','=',$id)->delete();
            $ss=AuthGroupAccess::getLastSql();

            (new AuthGroupAccess)->saveAll($groups);
            Db::commit();
            return myJson();
        } catch (Exception $exception) {
            Db::rollback();
            return myJson(-1,$exception->getMessage(),[$id,$ss]);
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
        //
        $user = Users::get($id);
        if($user['is_super']==10)
            return myJson(-1,'此用户禁止删除');
        if($this->userInfo['id'] == $id)
            return myJson(-1,'自己无法删除自己');
        $kh = Customers::where('uid','=',$id)->count();
        if($kh>0)
            return myJson(-1,'此用户还有客户，请先转移再删除！');
        try {
            Users::destroy($id);
            return myJson();
        } catch (Exception $exception) {
            myJson(-1,$exception->getMessage());
        }


    }
}
