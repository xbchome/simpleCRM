<?php

namespace app\index\model;

use think\Db;
use think\Model;

class Users extends Model
{
    //

    /**
     * @param $name
     * @param int $id
     * @param $value
     * @return bool
     * 计算当前用户是否存在或者等于本身
     */
    public function is_null($name,$id=0,$value)
    {
        $u = $this->where($name,'=',$value)->find();
        if(empty($u) || $u['id']==$id)
            return true;  // 如果没有记录存在或者记录id等于当前id返回true
        return false;
    }

    public function getFrameworkUser($position,$uid,$map='')
    {
       return $this->name('framework')
            ->alias('f')
            ->join('users u','f.id=u.position')
            ->where('f.paht','like','%,'.$position.',%')
            ->whereOr('u.id','=',$uid)
           ->whereOr($map)
            ->field('u.id,u.user_name')
            ->select();
    }

    /**
     * 根据组织架构获取用户信息
     */
    public function getUsers($mapOr,$position,$map=[],$page=1,$limit=10)
    {
        $count = Db::name('framework')
            ->alias('f')
            ->join("users u",'f.id=u.position')
            ->whereOr('f.paht','like','%,'.$position.',%')
            ->whereOr($map)
            ->whereOr($mapOr)
            ->count();
       $data = Db::name('framework')
            ->alias('f')
            ->join("users u",'f.id=u.position')
            ->whereOr($map)
           ->whereOr('f.paht','like','%,'.$position.',%')
            ->whereOr($mapOr)
           ->page($page,$limit)
            ->select();
       return ['count'=>$count,'data'=>$data];
    }
}
