<?php
/**
 * Created by PhpStorm.
 * User: CWPC001
 * Date: 2018/01/02
 * Time: 上午 10:34
 */

namespace app\index\controller;


use app\index\model\Follow;
use app\index\model\Scheme;
use app\index\model\Users;
use app\index\model\Visits;
use think\Controller;
use think\Exception;
use think\Request;

class Api extends Controller
{
    /**
     * 判断用户名是否存在
     */
    public function isUserName(Request $request)
    {
        $id = $request->post('id');
        $userName = $request->post('userName');
        $u = Users::where('log_name','=',$userName)->find();
        if(empty($u) || $u['id']==$id)
            return myJson();
        return myJson(-1,'用户名已存在!');
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * 判断邮箱是否存在
     */
    public function isEmail(Request $request)
    {
        $id = $request->post('id');
        $email = $request->post('email');
        $u = Users::where('email','=',$email)->find();
        if(empty($u) || $u['id']==$id)
            return myJson();
        return myJson(-1,'邮箱已存在!',$u);
    }

    public function isPhone(Request $request)
    {
        $id = $request->post('id');
        $phone = $request->post('phone');
        $u = Users::where('phone','=',$phone)->find();
        if(empty($u) || $u['id']==$id)
            return myJson();
        return myJson(-1,'手机号已存在!');
    }

    /**
     * 保存产品方案
     */
    public function seveScheme(Request $request)
    {
       $cid = $request->post('cid',null);
       $id = $request->post('id',null);
       $data['scheme'] = $request->post('scheme',null,'htmlspecialchars');

        try {
            if(empty($id)|| $id==0)
            {
                $data['cid'] = $cid;
                $s = Scheme::create($data);
                $id = $s->id;
            }else
            {
                Scheme::where('id','=',$id)->update($data);
            }
            return myJson(2000,'成功',['id'=>$id]);
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());
        }
    }

    /**
     * 删除产品方案
     */

    public function delScheme($id)
    {
        try {
            Scheme::destroy($id);
            return myJson();
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());
        }
    }

    /**
     * 保存跟单情况
     */
    public function seveFollow(Request $request)
    {
        $cid = $request->post('cid',null);
        $id = $request->post('id',null);
        $data['contents'] = $request->post('scheme',null,'htmlspecialchars');

        try {
            if(empty($id)|| $id==0)
            {
                $data['cid'] = $cid;
                $data['create_time'] = time();
                $s = Follow::create($data);
                $id = $s->id;
            }else
            {
                Follow::where('id','=',$id)->update($data);
            }
            return myJson(2000,'成功',['id'=>$id]);
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());
        }
    }


    public function delFollow($id)
    {
        try {
            Follow::destroy($id);
            return myJson();
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());
        }
    }

    public function saveVisit(Request $request)
    {
        $cid = $request->post('cid',null);
        $id = $request->post('id',null);
        $data['visits_time'] = $request->post('scheme',null,'htmlspecialchars');

        try {
            if(empty($id)|| $id==0)
            {
                $data['cid'] = $cid;
                // $data['create_time'] = time();
                $s = Visits::create($data);
                $id = $s->id;
            }else
            {
                Visits::where('id','=',$id)->update($data);
            }
            return myJson(2000,'成功',['id'=>$id]);
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());
        }
    }
    public function delVisit($id)
    {
        try {
            Visits::destroy($id);
            return myJson();
        } catch (Exception $exception) {
            return myJson(-1,$exception->getMessage());
        }
    }
}